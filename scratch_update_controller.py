import re

file_path = r"c:\phpproj\bi-project\app\Http\Controllers\BusinessInt\DashboardController.php"

with open(file_path, "r", encoding="utf-8") as f:
    content = f.read()

# Add search and sort parameters extraction at the top of Dashboard()
insertion_marker = "        $year = $request->input('year', '2026'); // default to 2026"
new_params = """        $year = $request->input('year', '2026'); // default to 2026
        
        $search = $request->input('search');
        $activeTab = $request->input('tab', 'overview');
        $sort = $request->input('sort');
        $direction = $request->input('direction', 'asc');"""

content = content.replace(insertion_marker, new_params)

# Payments query logic enhancement
payments_marker = """        if ($endDate) {
            $paymentsQuery->where('payments.paymentDate', '<=', $endDate);
        }

        $payments = $paymentsQuery
            ->orderByDesc('payments.amount')
            ->take(100) // Limits to top 100 on screen to avoid freezing
            ->get();"""

payments_new = """        if ($endDate) {
            $paymentsQuery->where('payments.paymentDate', '<=', $endDate);
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
            $paymentsQuery->orderByDesc('payments.amount');
        }

        $payments = $paymentsQuery
            ->take(100) // Limits to top 100 on screen to avoid freezing
            ->get();"""

content = content.replace(payments_marker, payments_new)

# Customer query logic replacement
customer_marker = "        $allCustomers = \\App\\Models\\Customer::orderByRaw(\"CAST(customerNumber AS UNSIGNED) ASC\")->get();"

entities_new = """        // Offices
        $officesQuery = \\App\\Models\\Office::query();
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
        $employeesQuery = \\App\\Models\\Employee::with('office');
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
        $productsQuery = \\App\\Models\\Product::query();
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
        $customersQuery = \\App\\Models\\Customer::query();
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
        $allCustomers = $customersQuery->get();"""

content = content.replace(customer_marker, entities_new)

# Update compact() return
compact_marker = "'payments', 'citiesList', 'startDate', 'endDate', 'location', 'period', 'year', 'allCustomers',"
compact_new = "'payments', 'citiesList', 'startDate', 'endDate', 'location', 'period', 'year', 'allCustomers', 'offices', 'employees', 'products',"

content = content.replace(compact_marker, compact_new)

with open(file_path, "w", encoding="utf-8") as f:
    f.write(content)
print("DashboardController updated successfully")
