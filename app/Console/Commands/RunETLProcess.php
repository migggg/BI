<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class RunETLProcess extends Command
{
    protected $signature = 'etl:run {--trigger=manual : What triggered this ETL run (manual, scheduled, crud, seed)} {--source= : Which action triggered this run}';
    protected $description = 'Executes the data warehouse Extract, Transform, and Load process';

    public function handle()
    {
        $runId = Str::uuid()->toString();
        $triggerType = $this->option('trigger') ?: 'manual';
        $triggerSource = $this->option('source') ?: null;
        $startTime = microtime(true);

        // Validate trigger_type against allowed enum values
        $allowedTriggers = ['manual', 'scheduled', 'crud', 'seed'];
        if (!in_array($triggerType, $allowedTriggers)) {
            $triggerType = 'manual';
        }

        // Create the ETL log entry
        $logId = DB::table('etl_logs')->insertGetId([
            'run_id'         => $runId,
            'trigger_type'   => $triggerType,
            'trigger_source' => $triggerSource,
            'status'         => 'running',
            'created_at'     => now(),
            'updated_at'     => now(),
        ]);

        $this->info("ETL Pipeline Started [Run: {$runId}]");

        try {
            // ========================================================
            // 1. EXTRACT & TRANSFORM DIMENSIONS
            // ========================================================

            $this->info('Populating dim_customers...');
            $customers = DB::table('customers')->get();
            foreach ($customers as $cust) {
                DB::table('dim_customers')->updateOrInsert(
                    ['customer_key' => $cust->customerNumber],
                    [
                        'customer_name' => $cust->customerName,
                        'city'          => $cust->city,
                        'country'       => $cust->country,
                        'credit_limit'  => $cust->creditLimit ?? 0,
                        'updated_at'    => now()
                    ]
                );
            }
            $dimCustomersCount = $customers->count();

            $this->info('Populating dim_products...');
            $products = DB::table('products')->get();
            foreach ($products as $prod) {
                DB::table('dim_products')->updateOrInsert(
                    ['product_key' => $prod->productCode],
                    [
                        'product_name' => $prod->productName,
                        'product_line' => $prod->productLine,
                        'buy_price'    => $prod->buyPrice,
                        'updated_at'   => now()
                    ]
                );
            }
            $dimProductsCount = $products->count();

            // Using simple data flow for Employees and Offices since we decoupled them in demo data.
            $this->info('Populating dim_offices and dim_employees...');
            $employees = DB::table('employees')
                ->join('offices', 'employees.officeCode', '=', 'offices.officeCode')
                ->select(
                    'employees.employeeNumber',
                    'employees.firstName',
                    'employees.lastName',
                    'employees.jobTitle',
                    'offices.officeCode',
                    'offices.city as officeCity',
                    'offices.state as officeRegion' // Fallback
                )->get();

            foreach ($employees as $emp) {
                DB::table('dim_offices')->updateOrInsert(
                    ['employee_key' => $emp->employeeNumber],
                    [
                        'employee_name' => $emp->firstName . ' ' . $emp->lastName,
                        'job_title'     => $emp->jobTitle,
                        'office_code'   => $emp->officeCode,
                        'office_city'   => $emp->officeCity,
                        'updated_at'    => now()
                    ]
                );
            }
            $dimOfficesCount = $employees->count();

            // ========================================================
            // 2. REBUILD FACT TABLE WITH TRANSFORMED METRICS
            // ========================================================
            $this->info('Populating fact_sales...');
            DB::table('fact_sales')->truncate(); // Prevent duplicate records on reload

            // Pull raw order line data matching your layout relationships
            $salesEntries = DB::table('orderdetails')
                ->join('orders', 'orderdetails.orderNumber', '=', 'orders.orderNumber')
                ->join('customers', 'orders.customerNumber', '=', 'customers.customerNumber')
                ->select(
                    'orders.customerNumber',
                    'orderdetails.productCode',
                    'customers.salesRepEmployeeNumber',
                    'orders.orderDate',
                    'orders.status',
                    'orderdetails.quantityOrdered',
                    'orderdetails.priceEach'
                )->get();

            // Pull total customer payment allocations to resolve granular differences
            $paymentSummary = DB::table('payments')
                ->groupBy('customerNumber')
                ->select('customerNumber', DB::raw('SUM(amount) as totalPaid'))
                ->pluck('totalPaid', 'customerNumber');

            foreach ($salesEntries as $entry) {
                $totalSalesAmount = $entry->quantityOrdered * $entry->priceEach;

                DB::table('fact_sales')->insert([
                    'customer_key'         => $entry->customerNumber,
                    'product_key'          => $entry->productCode,
                    'employee_key'         => $entry->salesRepEmployeeNumber ?? 0, // Automatically accepts null
                    'order_date'           => $entry->orderDate,
                    'status'               => $entry->status,
                    'quantity_ordered'     => $entry->quantityOrdered,
                    'price_each'           => $entry->priceEach,
                    'total_sales_amount'   => $totalSalesAmount,
                    // Assign mapped payment values if matching lines exist
                    'total_payment_amount' => $paymentSummary[$entry->customerNumber] ?? 0.00,
                    'created_at'           => now(),
                    'updated_at'           => now()
                ]);
            }
            $factSalesCount = $salesEntries->count();

            $duration = round(microtime(true) - $startTime, 3);
            $totalRows = $dimCustomersCount + $dimProductsCount + $dimOfficesCount + $factSalesCount;

            // Update the log entry with success metrics
            DB::table('etl_logs')->where('id', $logId)->update([
                'status'               => 'completed',
                'dim_customers_count'  => $dimCustomersCount,
                'dim_products_count'   => $dimProductsCount,
                'dim_offices_count'    => $dimOfficesCount,
                'fact_sales_count'     => $factSalesCount,
                'total_rows_processed' => $totalRows,
                'duration_seconds'     => $duration,
                'updated_at'           => now(),
            ]);

            $this->info("ETL Process completed successfully in {$duration}s. Total rows: {$totalRows}");

        } catch (\Exception $e) {
            $duration = round(microtime(true) - $startTime, 3);

            DB::table('etl_logs')->where('id', $logId)->update([
                'status'           => 'failed',
                'duration_seconds' => $duration,
                'error_message'    => $e->getMessage(),
                'updated_at'       => now(),
            ]);

            $this->error("ETL Process failed: {$e->getMessage()}");
            throw $e;
        }
    }
}
