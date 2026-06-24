<?php

namespace App\Http\Controllers\BusinessInt;

use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        return view('Dashboard');
    }

    public function Dashboard(Request $request)
    {
        // Automatically seed demo data if the tables are empty, or if specifically requested
        if ($request->has('seed') || DB::table('dim_customers')->count() === 0) {
            $this->seedDemoData();
            if ($request->has('seed')) {
                return redirect('/')->with('success', '🇵🇭 Mabuhay! McDonald\'s Philippine Franchise demo data has been loaded successfully!');
            }
        }

        // 1. Get request parameters for interactive filters
        $startDate = $request->input('start_date');
        $endDate = $request->input('end_date');
        $location = $request->input('location');
        $period = $request->input('period', 'custom');
        $year = $request->input('year', '2026'); // default to 2026
        
        $search = $request->input('search');
        $activeTab = $request->input('tab', 'overview');
        $sort = $request->input('sort');
        $direction = $request->input('direction', 'asc');

        // 2. Handle Quarterly, Semi-Annually, and Annually filters
        if ($period === 'quarterly') {
            $quarter = $request->input('quarter', 'Q1');
            if ($quarter === 'Q1') {
                $startDate = "$year-01-01";
                $endDate = "$year-03-31";
            } elseif ($quarter === 'Q2') {
                $startDate = "$year-04-01";
                $endDate = "$year-06-30";
            } elseif ($quarter === 'Q3') {
                $startDate = "$year-07-01";
                $endDate = "$year-09-30";
            } else {
                $startDate = "$year-10-01";
                $endDate = "$year-12-31";
            }
        } elseif ($period === 'semi-annually') {
            $half = $request->input('half', 'H1');
            if ($half === 'H1') {
                $startDate = "$year-01-01";
                $endDate = "$year-06-30";
            } else {
                $startDate = "$year-07-01";
                $endDate = "$year-12-31";
            }
        } elseif ($period === 'annually') {
            $startDate = "$year-01-01";
            $endDate = "$year-12-31";
        }

        // Fetch unique cities from both tables dynamically
        $dimCities = DB::table('dim_customers')
            ->whereNotNull('city')
            ->where('city', '<>', '')
            ->distinct()
            ->pluck('city');

        $custCities = DB::table('customers')
            ->whereNotNull('city')
            ->where('city', '<>', '')
            ->distinct()
            ->pluck('city');

        $citiesList = $dimCities->merge($custCities)
            ->unique()
            ->sort()
            ->values()
            ->toArray();

        // --- 10 BUSINESS QUESTIONS ANALYTICS ---

        // Q1: Best City Market
        $q1_CitySales = DB::table('fact_sales')
            ->join('dim_customers', 'fact_sales.customer_key', '=', 'dim_customers.customer_key')
            ->select('dim_customers.city', DB::raw('SUM(fact_sales.total_sales_amount) as total'))
            ->groupBy('dim_customers.city')
            ->orderByDesc('total')
            ->take(5)->get();

        // Q2: Highest Sales Product
        $q2_ProductSales = DB::table('fact_sales')
            ->join('dim_products', 'fact_sales.product_key', '=', 'dim_products.product_key')
            ->select('dim_products.product_name', DB::raw('SUM(fact_sales.total_sales_amount) as total'))
            ->groupBy('dim_products.product_name')
            ->orderByDesc('total')
            ->take(5)->get();

        // Q3: Best Office Support
        $q3_OfficeSales = DB::table('fact_sales')
            ->join('dim_offices', 'fact_sales.employee_key', '=', 'dim_offices.employee_key')
            ->select('dim_offices.office_city', DB::raw('SUM(fact_sales.total_sales_amount) as total'))
            ->groupBy('dim_offices.office_city')
            ->orderByDesc('total')
            ->take(5)->get();

        // Q4: Best Product Line
        $q4_ProductLines = DB::table('fact_sales')
            ->join('dim_products', 'fact_sales.product_key', '=', 'dim_products.product_key')
            ->join('productlines', 'dim_products.product_line', '=', 'productlines.productLine')
            ->select('productlines.textDescription', DB::raw('SUM(fact_sales.total_sales_amount) as total'))
            ->groupBy('productlines.textDescription')
            ->orderByDesc('total')
            ->get();

        // Q5: Office Managing Highest-Revenue Products
        // We find the top product for each office
        $q5_OfficeTopProducts = DB::table('fact_sales')
            ->join('dim_offices', 'fact_sales.employee_key', '=', 'dim_offices.employee_key')
            ->join('dim_products', 'fact_sales.product_key', '=', 'dim_products.product_key')
            ->select('dim_offices.office_city', 'dim_products.product_name', DB::raw('SUM(fact_sales.total_sales_amount) as total'))
            ->groupBy('dim_offices.office_city', 'dim_products.product_name')
            ->orderBy('dim_offices.office_city')
            ->orderByDesc('total')
            ->get()
            ->groupBy('office_city')
            ->map(function ($items) { return $items->first(); }) // Get highest product per office
            ->values();

        // Q6: Delayed Products
        $q6_DelayedProducts = DB::table('orderdetails')
            ->join('orders', 'orderdetails.orderNumber', '=', 'orders.orderNumber')
            ->join('products', 'orderdetails.productCode', '=', 'products.productCode')
            ->whereRaw('orders.shippedDate > orders.requiredDate')
            ->select('products.productName', DB::raw('COUNT(orders.orderNumber) as delay_count'))
            ->groupBy('products.productName')
            ->orderByDesc('delay_count')
            ->take(5)->get();

        // 6. Query 4: Payments List & Summary (Order Details)
        // We join to orders, orderdetails, and products to show what gadget was ordered
        $paymentsQuery = DB::table('orders')
            ->join('customers', 'orders.customerNumber', '=', 'customers.customerNumber')
            ->leftJoin('orderdetails', 'orders.orderNumber', '=', 'orderdetails.orderNumber')
            ->leftJoin('products', 'orderdetails.productCode', '=', 'products.productCode')
            ->leftJoin('payments', function ($join) {
                $join->on('customers.customerNumber', '=', 'payments.customerNumber')
                     ->on('orders.orderDate', '=', 'payments.paymentDate');
            })
            ->select(
                'customers.customerNumber',
                'customers.city',
                'customers.customerName',
                'customers.country',
                'payments.checkNumber',
                'orders.orderDate as paymentDate',
                DB::raw('(orderdetails.quantityOrdered * orderdetails.priceEach) as amount'),
                'products.productName as purchasedGadget',
                'orderdetails.quantityOrdered',
                'orders.orderNumber'
            )->groupBy(
                'orders.orderNumber', 'customers.customerNumber', 'customers.city', 'customers.customerName', 'customers.country',
                'payments.checkNumber', 'orders.orderDate', 'amount', 'purchasedGadget', 'orderdetails.quantityOrdered'
            );

        if ($location) {
            $paymentsQuery->where('customers.city', $location);
        }
        if ($startDate) {
            $paymentsQuery->where('orders.orderDate', '>=', $startDate);
        }
        if ($endDate) {
            $paymentsQuery->where('orders.orderDate', '<=', $endDate);
        }

        if ($activeTab === 'sales' && $search) {
            $paymentsQuery->where(function($q) use ($search) {
                $q->where('customers.customerNumber', 'LIKE', "%{$search}%")
                  ->orWhere('customers.customerName', 'LIKE', "%{$search}%")
                  ->orWhere('customers.city', 'LIKE', "%{$search}%")
                  ->orWhere('customers.country', 'LIKE', "%{$search}%")
                  ->orWhere('products.productName', 'LIKE', "%{$search}%")
                  ->orWhere('payments.checkNumber', 'LIKE', "%{$search}%");
            });
        }
        
        if ($activeTab === 'sales' && $sort) {
            $paymentsQuery->orderBy($sort, $direction);
        } else {
            $paymentsQuery->orderBy('orders.orderNumber', 'asc');
        }

        $payments = $paymentsQuery
            ->take(100) // Limits to top 100 on screen to avoid freezing
            ->get();

        // Q7: Top Country/Region by Orders
        $q7_RegionOrders = DB::table('fact_sales')
            ->join('dim_customers', 'fact_sales.customer_key', '=', 'dim_customers.customer_key')
            ->select('dim_customers.country', DB::raw('COUNT(*) as total_orders'))
            ->groupBy('dim_customers.country')
            ->orderByDesc('total_orders')
            ->take(5)->get();

        // Q8 & Q10: MoM Sales Trend & Highest Month
        $q8_MoMTrend = DB::table('fact_sales')
            ->select(
                DB::raw("DATE_FORMAT(order_date, '%Y-%m') as month_year"),
                DB::raw('SUM(total_sales_amount) as total_sales')
            )
            ->groupBy('month_year')
            ->orderBy('month_year')
            ->get();
            
        $q10_HighestMonth = $q8_MoMTrend->sortByDesc('total_sales')->first();

        // Q9: Efficient Employee (Revenue-to-Customer)
        $q9_EmployeeEfficiency = DB::table('fact_sales')
            ->join('dim_offices', 'fact_sales.employee_key', '=', 'dim_offices.employee_key')
            ->select(
                'dim_offices.employee_name',
                DB::raw('SUM(fact_sales.total_sales_amount) as total_revenue'),
                DB::raw('COUNT(DISTINCT fact_sales.customer_key) as distinct_customers')
            )
            ->groupBy('dim_offices.employee_name')
            ->get()
            ->map(function ($emp) {
                $emp->efficiency_ratio = $emp->distinct_customers > 0 ? ($emp->total_revenue / $emp->distinct_customers) : 0;
                return $emp;
            })
            ->sortByDesc('efficiency_ratio')
            ->take(5)
            ->values();

        // Offices
        $officesQuery = \App\Models\Office::query();
        if ($activeTab === 'offices' && $search) {
            $officesQuery->where(function($q) use ($search) {
                $q->where('officeCode', 'LIKE', "%{$search}%")
                  ->orWhere('city', 'LIKE', "%{$search}%");
            });
        }
        if ($activeTab === 'offices' && $sort) {
            $officesQuery->orderBy($sort, $direction);
        } else {
            $officesQuery->orderByRaw("CAST(officeCode AS UNSIGNED) ASC");
        }
        $offices = $officesQuery->get();

        // Employees
        $employeesQuery = \App\Models\Employee::with('office');
        if ($activeTab === 'employees' && $search) {
            $employeesQuery->where(function($q) use ($search) {
                $q->where('firstName', 'LIKE', "%{$search}%")
                  ->orWhere('lastName', 'LIKE', "%{$search}%")
                  ->orWhere('jobTitle', 'LIKE', "%{$search}%")
                  ->orWhere('officeCode', 'LIKE', "%{$search}%")
                  ->orWhereHas('office', function($q2) use ($search) {
                      $q2->where('city', 'LIKE', "%{$search}%");
                  });
            });
        }
        if ($activeTab === 'employees' && $sort) {
            $employeesQuery->orderBy($sort, $direction);
        } else {
            $employeesQuery->orderByRaw("CAST(employeeNumber AS UNSIGNED) ASC");
        }
        $employees = $employeesQuery->get();

        // Products
        $productsQuery = \App\Models\Product::query();
        if ($activeTab === 'products' && $search) {
            $productsQuery->where(function($q) use ($search) {
                $q->where('productCode', 'LIKE', "%{$search}%")
                  ->orWhere('productName', 'LIKE', "%{$search}%")
                  ->orWhere('productLine', 'LIKE', "%{$search}%");
            });
        }
        if ($activeTab === 'products' && $sort) {
            $productsQuery->orderBy($sort, $direction);
        } else {
            $productsQuery->orderByRaw("CAST(productCode AS UNSIGNED) ASC");
        }
        $products = $productsQuery->get();

        // Customers
        $customersQuery = \App\Models\Customer::query();
        if ($activeTab === 'customers' && $search) {
            $customersQuery->where(function($q) use ($search) {
                $q->where('customerNumber', 'LIKE', "%{$search}%")
                  ->orWhere('customerName', 'LIKE', "%{$search}%")
                  ->orWhere('city', 'LIKE', "%{$search}%")
                  ->orWhere('country', 'LIKE', "%{$search}%");
            });
        }
        if ($activeTab === 'customers' && $sort) {
            $customersQuery->orderBy($sort, $direction);
        } else {
            $customersQuery->orderByRaw("CAST(customerNumber AS UNSIGNED) ASC");
        }
        $allCustomers = $customersQuery->get();

        return view('BusinessInt(View).Display', compact(
            'payments', 'citiesList', 'startDate', 'endDate', 'location', 'period', 'year', 'allCustomers', 'offices', 'employees', 'products',
            'q1_CitySales', 'q2_ProductSales', 'q3_OfficeSales', 'q4_ProductLines', 'q5_OfficeTopProducts',
            'q6_DelayedProducts', 'q7_RegionOrders', 'q8_MoMTrend', 'q9_EmployeeEfficiency', 'q10_HighestMonth'
        ));
    }

    /**
     * Seeds exactly 20 records for a fast, clean demo.
     */
    public function seedDemoData()
    {
        // 1. Clear existing massive data (Base tables first due to FK constraints if any, or just truncate)
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        DB::table('payments')->truncate();
        DB::table('orderdetails')->truncate();
        DB::table('orders')->truncate();
        DB::table('customers')->truncate();
        DB::table('employees')->truncate();
        DB::table('offices')->truncate();
        DB::table('products')->truncate();
        DB::table('productlines')->truncate();
        
        DB::table('fact_sales')->truncate();
        DB::table('dim_customers')->truncate();
        DB::table('dim_products')->truncate();
        DB::table('offices')->truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        // 2. Seed Offices (NCR only - 17 LGUs)
        $ncrCities = ['Manila', 'Quezon City', 'Caloocan', 'Las Piñas', 'Makati', 'Malabon', 'Mandaluyong', 'Marikina', 'Muntinlupa', 'Navotas', 'Parañaque', 'Pasay', 'Pasig', 'San Juan', 'Taguig', 'Valenzuela', 'Pateros'];
        $officesData = [];
        foreach ($ncrCities as $index => $city) {
            $officesData[] = [
                'officeCode' => (string)($index + 1),
                'city' => $city,
                'phone' => '+63 2 8' . rand(100, 999) . ' ' . rand(1000, 9999),
                'addressLine1' => 'Tech Hub ' . $city,
                'addressLine2' => '',
                'state' => 'NCR'
            ];
        }
        DB::table('offices')->insert($officesData);

        // 3. Seed Employees (30 Employees)
        $firstNames = ['Juan', 'Maria', 'Jose', 'Pedro', 'Ana', 'Miguel', 'John', 'Michael', 'Emily', 'Sarah', 'David', 'James', 'Christopher', 'Daniel', 'Jessica', 'Ramon', 'Ricardo'];
        $lastNames = ['Dela Cruz', 'Santos', 'Reyes', 'Bautista', 'Ocampo', 'Garcia', 'Smith', 'Johnson', 'Williams', 'Brown', 'Jones', 'Miller', 'Davis', 'Rodriguez', 'Martinez'];
        $employeesData = [];
        // President
        $employeesData[] = ['employeeNumber' => 1001, 'lastName' => 'Valenzuela', 'firstName' => 'Ramon', 'extension' => 'x101', 'email' => 'rvalenzuela@techgadgets.com', 'officeCode' => '1', 'reportsTo' => null, 'jobTitle' => 'President'];
        // VPs
        for($i=2; $i<=5; $i++) {
            $employeesData[] = ['employeeNumber' => 1000 + $i, 'lastName' => $lastNames[array_rand($lastNames)], 'firstName' => $firstNames[array_rand($firstNames)], 'extension' => 'x10'.$i, 'email' => 'vp'.$i.'@techgadgets.com', 'officeCode' => (string)rand(1, 17), 'reportsTo' => 1001, 'jobTitle' => 'VP Sales'];
        }
        // Sales Reps
        for($i=6; $i<=30; $i++) {
            $employeesData[] = ['employeeNumber' => 1000 + $i, 'lastName' => $lastNames[array_rand($lastNames)], 'firstName' => $firstNames[array_rand($firstNames)], 'extension' => 'x10'.$i, 'email' => 'rep'.$i.'@techgadgets.com', 'officeCode' => (string)rand(1, 17), 'reportsTo' => rand(1002, 1005), 'jobTitle' => 'Sales Rep'];
        }
        DB::table('employees')->insert($employeesData);

        // 4. Seed Product Lines
        $productLinesData = [
            ['productLine' => 1, 'textDescription' => 'Smartphones', 'htmlDescription' => null, 'image' => null],
            ['productLine' => 2, 'textDescription' => 'Laptops', 'htmlDescription' => null, 'image' => null],
            ['productLine' => 3, 'textDescription' => 'Wearables', 'htmlDescription' => null, 'image' => null],
            ['productLine' => 4, 'textDescription' => 'Audio', 'htmlDescription' => null, 'image' => null],
        ];
        DB::table('productlines')->insert($productLinesData);

        // 5. Seed Products (20 Realistic Gadgets)
        $realProducts = [
            ['iPhone 15 Pro Max', 'Apple', 1, 75000],
            ['iPhone 14', 'Apple', 1, 45000],
            ['PlayStation 5 (Disc Edition)', 'Sony', 3, 28000],
            ['Xbox Series X', 'Microsoft', 3, 27500],
            ['Nintendo Switch OLED', 'Nintendo', 3, 17000],
            ['Xiaomi 14 Ultra', 'Xiaomi', 1, 55000],
            ['Redmi Note 13 Pro+', 'Xiaomi', 1, 20000],
            ['Vivo X100 Pro', 'Vivo', 1, 52000],
            ['Vivo V30 5G', 'Vivo', 1, 24000],
            ['Oppo Find X7 Ultra', 'Oppo', 1, 60000],
            ['Oppo Reno 11', 'Oppo', 1, 22000],
            ['Google Pixel 8 Pro', 'Google', 1, 58000],
            ['Google Pixel 7a', 'Google', 1, 28000],
            ['Samsung Galaxy S24 Ultra', 'Samsung', 1, 70000],
            ['MacBook Pro 16" M3', 'Apple', 2, 120000],
            ['Dell XPS 15', 'Dell', 2, 85000],
            ['Sony WH-1000XM5', 'Sony', 4, 15000],
            ['AirPods Pro (2nd Gen)', 'Apple', 4, 13500],
            ['Samsung Galaxy Watch 6', 'Samsung', 3, 16000],
            ['Asus ROG Ally', 'Asus', 3, 38000]
        ];

        $productsData = [];
        foreach ($realProducts as $index => $prod) {
            $buyPrice = $prod[3] * 0.8; // 20% margin
            $productsData[] = [
                'productCode' => 'G' . str_pad($index + 1, 3, '0', STR_PAD_LEFT),
                'productName' => $prod[0],
                'productLine' => $prod[2],
                'productScale' => '1:1',
                'productVendor' => $prod[1],
                'productDescription' => 'High performance tech device.',
                'quantityInStock' => rand(100, 1000),
                'buyPrice' => $buyPrice,
                'MSRP' => $prod[3]
            ];
        }
        DB::table('products')->insert($productsData);

        // 6. Seed Customers (30 Customers)
        $customersData = [];
        for ($i = 1; $i <= 30; $i++) {
            $city = $ncrCities[array_rand($ncrCities)];
            $fName = $firstNames[array_rand($firstNames)];
            $lName = $lastNames[array_rand($lastNames)];
            
            $customersData[] = [
                'customerNumber' => $i,
                'customerName' => $fName . ' ' . $lName,
                'contactLastName' => $lName,
                'contactFirstName' => $fName,
                'phone' => '+63 9' . rand(10000000, 99999999),
                'addressLine1' => rand(1, 999) . ' Main Street',
                'addressLine2' => '',
                'city' => $city,
                'state' => 'NCR',
                'postalCode' => rand(1000, 1999),
                'country' => 'Philippines',
                'salesRepEmployeeNumber' => rand(1006, 1030),
                'creditLimit' => rand(50000, 200000),
            ];
        }
        DB::table('customers')->insert($customersData);

        // 7. Generate exactly 50 Orders, OrderDetails, and Payments
        $ordersData = [];
        $orderDetailsData = [];
        $paymentsData = [];
        
        for ($i = 1; $i <= 50; $i++) {
            // Guarantee duplicates by picking from a smaller pool for the first 15 orders, 
            // and ensure no consecutive customers.
            $pool = ($i <= 15) ? array_slice($customersData, 0, 5) : $customersData;
            do {
                $cust = $pool[array_rand($pool)];
            } while (isset($previousCustomerNumber) && $cust['customerNumber'] === $previousCustomerNumber);
            
            $previousCustomerNumber = $cust['customerNumber'];
            
            $orderDate = date('Y-m-d', rand(strtotime('2026-01-01'), strtotime('now')));
            $orderNumber = $i;
            
            $ordersData[] = [
                'orderNumber' => $orderNumber,
                'orderDate' => $orderDate,
                'requiredDate' => date('Y-m-d', strtotime($orderDate . ' + 3 days')),
                'shippedDate' => date('Y-m-d', strtotime($orderDate . ' + 1 days')),
                'status' => 'Shipped',
                'comments' => '',
                'customerNumber' => $cust['customerNumber'],
            ];

            // 1 item per order due to DB primary key constraint on orderNumber
            $numItems = 1;
            $orderTotal = 0;
            $usedProducts = [];
            
            for ($j = 1; $j <= $numItems; $j++) {
                $prod = $productsData[array_rand($productsData)];
                while(in_array($prod['productCode'], $usedProducts)) {
                    $prod = $productsData[array_rand($productsData)];
                }
                $usedProducts[] = $prod['productCode'];

                $qty = rand(1, 10); // Retail quantities
                $price = $prod['buyPrice'];
                $orderTotal += ($qty * $price);

                $orderDetailsData[] = [
                    'orderNumber' => $orderNumber,
                    'productCode' => $prod['productCode'],
                    'quantityOrdered' => $qty,
                    'priceEach' => $price,
                    'orderLineNumber' => $j,
                ];
            }

            $paymentsData[] = [
                'customerNumber' => $cust['customerNumber'],
                'checkNumber' => 'CK-' . (100000 + $i),
                'paymentDate' => $orderDate,
                'amount' => $orderTotal,
            ];
        }

        DB::table('orderdetails')->insert($orderDetailsData);
        DB::table('orders')->insert($ordersData);
        DB::table('payments')->insert($paymentsData);

        // 8. Run the ETL Pipeline
        \Illuminate\Support\Facades\Artisan::call('etl:run', ['--trigger' => 'seed', '--source' => 'seedDemoData']);
    }


    // ETL MONITORING DASHBOARD
    // ==========================================

    /**
     * Returns ETL log data for the monitoring dashboard tab.
     */
    public function etlDashboard()
    {
        $etlLogs = DB::table('etl_logs')
            ->orderByDesc('created_at')
            ->take(50)
            ->get();

        $lastRun = $etlLogs->first();
        $totalRuns = DB::table('etl_logs')->count();
        $successRuns = DB::table('etl_logs')->where('status', 'completed')->count();
        $failedRuns = DB::table('etl_logs')->where('status', 'failed')->count();
        $avgDuration = DB::table('etl_logs')->where('status', 'completed')->avg('duration_seconds') ?? 0;

        // Trigger type breakdown
        $triggerBreakdown = DB::table('etl_logs')
            ->select('trigger_type', DB::raw('COUNT(*) as count'))
            ->groupBy('trigger_type')
            ->pluck('count', 'trigger_type')
            ->toArray();

        return response()->json([
            'logs'              => $etlLogs,
            'lastRun'           => $lastRun,
            'totalRuns'         => $totalRuns,
            'successRuns'       => $successRuns,
            'failedRuns'        => $failedRuns,
            'avgDuration'       => round($avgDuration, 3),
            'triggerBreakdown'  => $triggerBreakdown,
        ]);
    }

    /**
     * Manually trigger an ETL run from the dashboard UI.
     */
    public function triggerEtl()
    {
        \Illuminate\Support\Facades\Artisan::call('etl:run', ['--trigger' => 'manual', '--source' => 'Dashboard_UI']);
        return redirect('/?tab=etl')->with('success', '⚙️ ETL Pipeline executed successfully!');
    }
}
