<!-- DISPLAY_BLADE_MARKER_12345 -->
<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-white leading-tight">
            {{ __('BI Dashboard') }}
        </h2>
    </x-slot>

<div class="py-8">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="container my-4 dash-wrapper">

    @if(session('success'))
    <div class="bg-green-50 dark:bg-green-900/30 border border-green-200 dark:border-green-800 rounded-lg p-4 mb-6 flex justify-between items-center text-green-800 dark:text-green-300 font-bold">
        <span><i class="bi bi-check-circle-fill me-2"></i> {{ session('success') }}</span>
        <button onclick="this.parentElement.remove()" class="text-green-800 dark:text-green-300 hover:opacity-75" style="background: none; border: none; font-size: 1.25rem; cursor: pointer;">&times;</button>
    </div>
    @endif

    @if(session('error'))
    <div class="bg-red-50 dark:bg-red-900/30 border border-red-200 dark:border-red-800 rounded-lg p-4 mb-6 flex justify-between items-center text-red-800 dark:text-red-300 font-bold">
        <span><i class="bi bi-exclamation-triangle-fill me-2"></i> {{ session('error') }}</span>
        <button onclick="this.parentElement.remove()" class="text-red-800 dark:text-red-300 hover:opacity-75" style="background: none; border: none; font-size: 1.25rem; cursor: pointer;">&times;</button>
    </div>
    @endif

    @php $activeTab = request()->query('tab', 'overview'); @endphp

    {{-- ==================== TAB 1: DASHBOARD HOME (OVERVIEW) ==================== --}}
    @if($activeTab === 'overview' || empty($activeTab))
    <div id="tab-overview" class="tab-pane-content">


        <!-- 4 Hero Stat Cards -->
        <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-4 gap-6 mb-8">
            <!-- Q2: Highest Sales Product -->
            <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm border border-gray-100 dark:border-gray-700 p-6">
                <div class="flex items-center gap-4 mb-4">
                    <div class="w-12 h-12 rounded-full bg-blue-100 dark:bg-blue-900 flex items-center justify-center text-blue-600 dark:text-blue-400 text-xl">
                        <i class="bi bi-trophy-fill"></i>
                    </div>
                    <div>
                        <p class="text-sm text-gray-500 dark:text-gray-200 font-medium mb-0">Top Product</p>
                        <h4 class="text-xl font-bold text-gray-800 dark:text-white m-0">{{ $q2_ProductSales->first()->product_name ?? 'N/A' }}</h4>
                    </div>
                </div>
                <div class="text-sm text-green-600 dark:text-green-400 font-bold bg-green-50 dark:bg-green-900 rounded-lg py-2 px-3 inline-block">
                    ₱ {{ number_format($q2_ProductSales->first()->total ?? 0, 2) }}
                </div>
            </div>

            <!-- Q10: Highest Sales Month -->
            <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm border border-gray-100 dark:border-gray-700 p-6">
                <div class="flex items-center gap-4 mb-4">
                    <div class="w-12 h-12 rounded-full bg-purple-100 dark:bg-purple-900 flex items-center justify-center text-purple-600 dark:text-purple-400 text-xl">
                        <i class="bi bi-calendar-star-fill"></i>
                    </div>
                    <div>
                        <p class="text-sm text-gray-500 dark:text-gray-200 font-medium mb-0">Best Month</p>
                        <h4 class="text-xl font-bold text-gray-800 dark:text-white m-0">{{ $q10_HighestMonth ? date('F Y', strtotime($q10_HighestMonth->month_year)) : 'N/A' }}</h4>
                    </div>
                </div>
                <div class="text-sm text-green-600 dark:text-green-400 font-bold bg-green-50 dark:bg-green-900 rounded-lg py-2 px-3 inline-block">
                    ₱ {{ number_format($q10_HighestMonth->total_sales ?? 0, 2) }}
                </div>
            </div>

            <!-- Q3: Best Office Support -->
            <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm border border-gray-100 dark:border-gray-700 p-6">
                <div class="flex items-center gap-4 mb-4">
                    <div class="w-12 h-12 rounded-full bg-orange-100 dark:bg-orange-900 flex items-center justify-center text-orange-600 dark:text-orange-400 text-xl">
                        <i class="bi bi-building-fill-up"></i>
                    </div>
                    <div>
                        <p class="text-sm text-gray-500 dark:text-gray-200 font-medium mb-0">Top Office</p>
                        <h4 class="text-xl font-bold text-gray-800 dark:text-white m-0">{{ $q3_OfficeSales->first()->office_city ?? 'N/A' }}</h4>
                    </div>
                </div>
                <div class="text-sm text-green-600 dark:text-green-400 font-bold bg-green-50 dark:bg-green-900 rounded-lg py-2 px-3 inline-block">
                    ₱ {{ number_format($q3_OfficeSales->first()->total ?? 0, 2) }}
                </div>
            </div>

            <!-- Q1: Best City Market -->
            <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm border border-gray-100 dark:border-gray-700 p-6">
                <div class="flex items-center gap-4 mb-4">
                    <div class="w-12 h-12 rounded-full bg-teal-100 dark:bg-teal-900 flex items-center justify-center text-teal-600 dark:text-teal-400 text-xl">
                        <i class="bi bi-geo-alt-fill"></i>
                    </div>
                    <div>
                        <p class="text-sm text-gray-500 dark:text-gray-200 font-medium mb-0">Best City Market</p>
                        <h4 class="text-xl font-bold text-gray-800 dark:text-white m-0">{{ $q1_CitySales->first()->city ?? 'N/A' }}</h4>
                    </div>
                </div>
                <div class="text-sm text-green-600 dark:text-green-400 font-bold bg-green-50 dark:bg-green-900 rounded-lg py-2 px-3 inline-block">
                    ₱ {{ number_format($q1_CitySales->first()->total ?? 0, 2) }}
                </div>
            </div>
        </div>

<!-- Charts Row -->
<div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-8">
    <!-- Q8: MoM Sales Trend -->
    <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm border border-gray-100 dark:border-gray-700 p-6 lg:col-span-2">
        <h5 class="text-lg font-bold text-gray-800 dark:text-white mb-4"><i class="bi bi-graph-up-arrow text-blue-500 dark:text-blue-400 me-2"></i> Monthly Sales Trend</h5>
        <div style="position: relative; height: 300px;">
            <canvas id="chart-mom-trend"></canvas>
        </div>
    </div>

<!-- Q4: Best Product Line -->
<div class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm border border-gray-100 dark:border-gray-700 p-6">
    <h5 class="text-lg font-bold text-gray-800 dark:text-white mb-4"><i class="bi bi-pie-chart-fill text-purple-500 dark:text-purple-400 me-2"></i> Revenue by Line</h5>
    <div style="position: relative; height: 300px;">
        <canvas id="chart-product-lines"></canvas>
    </div>
</div>

<style>
    /* Sleek Designed Scrollbar */
    .custom-scrollbar::-webkit-scrollbar {
        width: 6px;
    }
    .custom-scrollbar::-webkit-scrollbar-track {
        background: transparent;
    }
    .custom-scrollbar::-webkit-scrollbar-thumb {
        background-color: #cbd5e1; /* Light gray */
        border-radius: 20px;       /* Makes it rounded */
    }
    .custom-scrollbar::-webkit-scrollbar-thumb:hover {
        background-color: #94a3b8; /* Darker gray on hover */
    }
</style>

<div class="flex flex-wrap -mx-2 mb-8">

    <div class="w-full md:w-1/2 p-2">
        <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm border border-gray-100 dark:border-gray-700 p-5 h-full">
            <h5 class="text-base font-bold text-gray-800 dark:text-white mb-4"><i class="bi bi-award text-yellow-500 dark:text-yellow-400 me-1"></i> Top Products</h5>
            <div class="space-y-4 custom-scrollbar" style="max-height: 300px; overflow-y: auto;">
                @foreach($q5_OfficeTopProducts as $officeProduct)
                <div class="flex justify-between items-center border-b border-gray-50 dark:border-gray-700 pb-2">
                    <div class="min-w-0 flex-1">
                        <p class="text-sm font-bold text-gray-800 dark:text-white m-0 truncate">{{ $officeProduct->office_city }}</p>
                        <p class="text-xs text-gray-500 dark:text-gray-200 m-0 truncate">{{ $officeProduct->product_name }}</p>
                    </div>
                    <span class="text-sm font-bold text-green-600 dark:text-green-400 ms-2 shrink-0">₱{{ number_format($officeProduct->total/1000, 1) }}k</span>
                </div>
                @endforeach
            </div>
        </div>
    </div>

    <div class="w-full md:w-1/2 p-2">
        <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm border border-gray-100 dark:border-gray-700 p-5 h-full">
            <h5 class="text-base font-bold text-gray-800 dark:text-white mb-4"><i class="bi bi-lightning-charge-fill text-orange-500 dark:text-orange-400 me-1"></i> Efficient Staff</h5>
            <div class="space-y-4 custom-scrollbar" style="max-height: 300px; overflow-y: auto;">
                @foreach($q9_EmployeeEfficiency as $emp)
                <div class="flex justify-between items-center border-b border-gray-50 dark:border-gray-700 pb-2">
                    <div class="min-w-0 flex-1">
                        <p class="text-sm font-bold text-gray-800 dark:text-white m-0 truncate">{{ $emp->employee_name }}</p>
                        <p class="text-xs text-gray-500 dark:text-gray-200 m-0 truncate">{{ $emp->distinct_customers }} Customers</p>
                    </div>
                    <span class="text-sm font-bold text-blue-600 dark:text-blue-400 ms-2 shrink-0">₱{{ number_format($emp->efficiency_ratio/1000, 1) }}k</span>
                </div>
                @endforeach
            </div>
        </div>
    </div>

    <div class="w-full md:w-1/2 p-2">
        <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm border border-gray-100 dark:border-gray-700 p-5 h-full">
            <h5 class="text-base font-bold text-gray-800 dark:text-white mb-4"><i class="bi bi-clock-history text-red-500 dark:text-red-400 me-1"></i> Delayed</h5>
            @if($q6_DelayedProducts->isEmpty())
            <div class="text-center py-8 text-gray-400">
                <i class="bi bi-check-circle text-3xl mb-2 text-green-500 block"></i>
                <p class="text-xs">No delays!</p>
            </div>
            @else
            <div class="space-y-4 custom-scrollbar" style="max-height: 300px; overflow-y: auto;">
                @foreach($q6_DelayedProducts as $delay)
                <div class="flex justify-between items-center border-b border-gray-50 dark:border-gray-700 pb-2">
                    <p class="text-xs font-bold text-gray-800 dark:text-white m-0 truncate">{{ $delay->productName }}</p>
                    <span class="text-xs font-bold text-red-500 dark:text-red-400 bg-red-50 px-1.5 py-0.5 rounded shrink-0">{{ $delay->delay_count }}</span>
                </div>
                @endforeach
            </div>
            @endif
        </div>
    </div>

    <div class="w-full md:w-1/2 p-2">
        <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm border border-gray-100 dark:border-gray-700 p-5 h-full">
            <h5 class="text-base font-bold text-gray-800 dark:text-white mb-4"><i class="bi bi-globe text-indigo-500 me-1"></i> Orders by Region</h5>
            <div style="position: relative; height: 240px;">
                <canvas id="chart-region-orders"></canvas>
            </div>
        </div>
    </div>

</div>
@endif

    {{-- ==================== TAB 2: OFFICES & HQs ==================== --}}
    @if($activeTab === 'offices')
    <div id="tab-offices" class="tab-pane-content">
        <div class="flex flex-col sm:flex-row justify-between items-center mb-4 gap-3">
            <div class="flex items-center gap-2 w-full sm:w-auto">
                <div class="relative w-full sm:w-72">
                    <i class="bi bi-search absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-400"></i>
                    <input type="text" class="tab-search-input form-control ps-5 w-full text-gray-900 dark:text-white dark:bg-gray-700" placeholder="Search offices..." style="height: 40px; border-radius: 8px;">
                </div>
                <button class="px-3 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 transition tab-search-btn" style="height: 40px;">Search</button>
            </div>
            @if(auth()->user()->roles->whereIn('name', ['super_admin', 'admin', 'Super Admin', 'Admin'])->isNotEmpty())
            <div class="flex justify-end gap-2 shrink-0">
                <a href="{{ route('offices.index') }}" class="inline-flex items-center px-3 py-2 bg-blue-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-700 focus:bg-blue-700 active:bg-blue-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 transition ease-in-out duration-150 shadow"><i class="bi bi-eye" title="View All"></i></a>
                <a href="{{ route('offices.create') }}" class="inline-flex items-center px-3 py-2 bg-emerald-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-emerald-700 focus:bg-emerald-700 active:bg-emerald-900 focus:outline-none focus:ring-2 focus:ring-emerald-500 transition ease-in-out duration-150 shadow"><i class="bi bi-plus-lg" style="-webkit-text-stroke: 1px;" title="Create"></i></a>
            </div>
            @endif
        </div>
        <div class="bg-white dark:bg-gray-800 dark:text-white overflow-hidden shadow-sm sm:rounded-lg p-6 mb-6 mt-2">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h5 class="m-0"><i class="bi bi-building text-primary me-2"></i> Corporate Headquarters Ledger</h5>
            </div>
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700 mt-4">
                    <thead class="bg-gray-50 dark:bg-gray-700 text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider"><tr><th class="px-4 py-3 text-left">HQ Office Code</th><th class="px-4 py-3 text-left">HQ City Location</th><th class="px-4 py-3 text-end"></th></tr></thead>
                    <tbody class="divide-y divide-gray-200 dark:divide-gray-700 dark:text-white">
                        @foreach($offices as $off)
                        <tr class="selectable-row" style="cursor: pointer;" data-id="{{ $off->officeCode }}">
                            <td class="px-4 py-3 whitespace-nowrap text-sm"><strong>{{ $off->officeCode }}</strong></td>
                            <td class="px-4 py-3 whitespace-nowrap text-sm">{{ $off->city }}</td>
<td class="px-4 py-3 whitespace-nowrap text-end text-sm font-medium">
    <div class="flex justify-end gap-2">
        <a href="{{ route('offices.show', $off->officeCode) }}" class="inline-block px-3 py-1 bg-blue-500 text-white rounded shadow hover:bg-blue-600 text-xs text-decoration-none" title="View"><i class="bi bi-eye"></i></a>
        <a href="{{ route('offices.edit', $off->officeCode) }}" class="inline-block px-3 py-1 bg-amber-500 text-white rounded shadow hover:bg-amber-600 text-xs text-decoration-none" title="Update"><i class="bi bi-pencil-square"></i></a>
        <form action="{{ route('offices.destroy', $off->officeCode) }}" method="POST" class="inline" onsubmit="return confirm('Are you sure you want to delete this?')">
            @csrf
            @method('DELETE')
            <button type="submit" class="inline-block px-3 py-1 bg-red-600 text-white rounded shadow hover:bg-red-700 text-xs border-0" title="Delete"><i class="bi bi-trash"></i></button>
        </form>
    </div>
</td>
</tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    @endif

    {{-- ==================== TAB 3: EMPLOYEES & STAFF ==================== --}}
    @if($activeTab === 'employees')
    <div id="tab-employees" class="tab-pane-content">
        <div class="flex flex-col sm:flex-row justify-between items-center mb-4 gap-3">
            <div class="flex items-center gap-2 w-full sm:w-auto">
                <div class="relative w-full sm:w-72">
                    <i class="bi bi-search absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-400"></i>
                    <input type="text" class="tab-search-input form-control ps-5 w-full text-gray-900 dark:text-white dark:bg-gray-700" placeholder="Search employees..." style="height: 40px; border-radius: 8px;">
                </div>
                <button class="px-3 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 transition tab-search-btn" style="height: 40px;">Search</button>
            </div>
            @if(auth()->user()->roles->whereIn('name', ['super_admin', 'admin', 'Super Admin', 'Admin'])->isNotEmpty())
            <div class="flex justify-end gap-2 shrink-0">
                <a href="{{ route('employees.index') }}" class="inline-flex items-center px-3 py-2 bg-blue-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-700 focus:bg-blue-700 active:bg-blue-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 transition ease-in-out duration-150 shadow"><i class="bi bi-eye" title="View All"></i></a>
                <a href="{{ route('employees.create') }}" class="inline-flex items-center px-3 py-2 bg-emerald-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-emerald-700 focus:bg-emerald-700 active:bg-emerald-900 focus:outline-none focus:ring-2 focus:ring-emerald-500 transition ease-in-out duration-150 shadow"><i class="bi bi-plus-lg" style="-webkit-text-stroke: 1px;" title="Create"></i></a>
            </div>
            @endif
        </div>
        <div class="bg-white dark:bg-gray-800 dark:text-white overflow-hidden shadow-sm sm:rounded-lg p-6 mb-6 mt-2">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h5 class="m-0"><i class="bi bi-people text-primary me-2"></i> Corporate Managers Directory</h5>
            </div>
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700 mt-4">
                    <thead class="bg-gray-50 dark:bg-gray-700 text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider"><tr><th class="px-4 py-3 text-left">Name</th><th class="px-4 py-3 text-left">Title</th><th class="px-4 py-3 text-left">HQ Code</th><th class="px-4 py-3 text-left">City</th><th class="px-4 py-3 text-end"></th></tr></thead>
                    <tbody class="divide-y divide-gray-200 dark:divide-gray-700 dark:text-white">
                        @foreach($employees as $emp)
                        <tr class="selectable-row" style="cursor: pointer;" data-id="{{ $emp->employeeNumber }}">
                            
                            <td class="px-4 py-3 whitespace-nowrap text-sm"><strong>{{ $emp->firstName }} {{ $emp->lastName }}</strong></td>
                            <td class="px-4 py-3 whitespace-nowrap text-sm">{{ $emp->jobTitle }}</td>
                            <td class="px-4 py-3 whitespace-nowrap text-sm"><code>{{ $emp->officeCode }}</code></td>
                            <td class="px-4 py-3 whitespace-nowrap text-sm">{{ $emp->office ? $emp->office->city : 'N/A' }}</td>
<td class="px-4 py-3 whitespace-nowrap text-end text-sm font-medium">
    <div class="flex justify-end gap-2">
        <a href="{{ route('employees.show', $emp->employeeNumber) }}" class="inline-block px-3 py-1 bg-blue-500 text-white rounded shadow hover:bg-blue-600 text-xs text-decoration-none" title="View"><i class="bi bi-eye"></i></a>
        <a href="{{ route('employees.edit', $emp->employeeNumber) }}" class="inline-block px-3 py-1 bg-amber-500 text-white rounded shadow hover:bg-amber-600 text-xs text-decoration-none" title="Update"><i class="bi bi-pencil-square"></i></a>
        <form action="{{ route('employees.destroy', $emp->employeeNumber) }}" method="POST" class="inline" onsubmit="return confirm('Are you sure you want to delete this?')">
            @csrf
            @method('DELETE')
            <button type="submit" class="inline-block px-3 py-1 bg-red-600 text-white rounded shadow hover:bg-red-700 text-xs border-0" title="Delete"><i class="bi bi-trash"></i></button>
        </form>
    </div>
</td>
</tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    @endif

   {{-- ==================== TAB 4: MENU & PRODUCTS ==================== --}}
    @if($activeTab === 'products')
    <div id="tab-products" class="tab-pane-content">
        <div class="flex flex-col sm:flex-row justify-between items-center mb-4 gap-3">
            <div class="flex items-center gap-2 w-full sm:w-auto">
                <div class="relative w-full sm:w-72">
                    <i class="bi bi-search absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-400"></i>
                    <input type="text" class="tab-search-input form-control ps-5 w-full text-gray-900 dark:text-white dark:bg-gray-700" placeholder="Search products..." style="height: 40px; border-radius: 8px;">
                </div>
                <button class="px-3 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 transition tab-search-btn" style="height: 40px;">Search</button>
            </div>
            @if(auth()->user()->roles->whereIn('name', ['super_admin', 'admin', 'Super Admin', 'Admin'])->isNotEmpty())
            <div class="flex justify-end gap-2 shrink-0">
                <a href="{{ route('products.index') }}" class="inline-flex items-center px-3 py-2 bg-blue-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-700 focus:bg-blue-700 active:bg-blue-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 transition ease-in-out duration-150 shadow"><i class="bi bi-eye" title="View All"></i></a>
                <a href="{{ route('products.create') }}" class="inline-flex items-center px-3 py-2 bg-emerald-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-emerald-700 focus:bg-emerald-700 active:bg-emerald-900 focus:outline-none focus:ring-2 focus:ring-emerald-500 transition ease-in-out duration-150 shadow"><i class="bi bi-plus-lg" style="-webkit-text-stroke: 1px;" title="Create"></i></a>
            </div>
            @endif
        </div>
        <div class="bg-white dark:bg-gray-800 dark:text-white overflow-hidden shadow-sm sm:rounded-lg p-6 mb-6">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h5 class="m-0"><i class="bi bi-cpu text-primary me-2"></i> Gadget Products Pricing & Inventory Catalog</h5>
            </div>
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700 mt-4">
                    <thead class="bg-gray-50 dark:bg-gray-700 text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                        <tr>
                            <th class="px-4 py-3 text-left">Product Key</th>
                            <th class="px-4 py-3 text-left">Product Name</th>
                            <th class="px-4 py-3 text-left">Category Line</th>
                            <th class="px-4 py-3 text-left">Unit Price (₱)</th>
                            <th class="px-4 py-3 text-left">Status</th>
                            <th class="text-end"></th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200 dark:divide-gray-700 dark:text-white">
                        @php
                            $liveProducts = \App\Models\Product::orderByRaw("CAST(productCode AS UNSIGNED) ASC")->get();
                        @endphp
                        @foreach($liveProducts as $prod)
                        <tr class="selectable-row" style="cursor: pointer;" data-id="{{ $prod->productCode }}">
                            <td class="px-4 py-3 whitespace-nowrap text-sm"><strong>{{ $prod->productCode }}</strong></td>
                            <td class="px-4 py-3 whitespace-nowrap text-sm">{{ $prod->productName }}</td>
                            <td class="px-4 py-3 whitespace-nowrap text-sm"><span class="badge" style="background: var(--bg-input); border: 1px solid var(--border-color); color: var(--text-sub);">{{ $prod->productLine }}</span></td>
                            <td class="px-4 py-3 whitespace-nowrap text-sm"><strong>₱{{ number_format($prod->buyPrice, 2) }}</strong></td>
                            <td class="px-4 py-3 whitespace-nowrap text-sm"><i class="bi bi-check-circle-fill text-success"></i> <span class="small text-muted">Active</span></td>
<td class="px-4 py-3 whitespace-nowrap text-end text-sm font-medium">
    <div class="flex justify-end gap-2">
        <a href="{{ route('products.show', $prod->productCode) }}" class="inline-block px-3 py-1 bg-blue-500 text-white rounded shadow hover:bg-blue-600 text-xs text-decoration-none" title="View"><i class="bi bi-eye"></i></a>
        <a href="{{ route('products.edit', $prod->productCode) }}" class="inline-block px-3 py-1 bg-amber-500 text-white rounded shadow hover:bg-amber-600 text-xs text-decoration-none" title="Update"><i class="bi bi-pencil-square"></i></a>
        <form action="{{ route('products.destroy', $prod->productCode) }}" method="POST" class="inline" onsubmit="return confirm('Are you sure you want to delete this?')">
            @csrf
            @method('DELETE')
            <button type="submit" class="inline-block px-3 py-1 bg-red-600 text-white rounded shadow hover:bg-red-700 text-xs border-0" title="Delete"><i class="bi bi-trash"></i></button>
        </form>
    </div>
</td>
</tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    @endif

    {{-- ==================== TAB 5: BRANCHES ==================== --}}
    @if($activeTab === 'customers')
    <div id="tab-customers" class="tab-pane-content">
        <div class="flex flex-col sm:flex-row justify-between items-center mb-4 gap-3">
            <div class="flex items-center gap-2 w-full sm:w-auto">
                <div class="relative w-full sm:w-72">
                    <i class="bi bi-search absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-400"></i>
                    <input type="text" class="tab-search-input form-control ps-5 w-full text-gray-900 dark:text-white dark:bg-gray-700" placeholder="Search customers..." style="height: 40px; border-radius: 8px;">
                </div>
                <button class="px-3 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 transition tab-search-btn" style="height: 40px;">Search</button>
            </div>
            @if(auth()->user()->roles->whereIn('name', ['super_admin', 'admin', 'Super Admin', 'Admin'])->isNotEmpty())
            <div class="flex justify-end gap-2 shrink-0">
                <a href="{{ route('customers.index') }}" class="inline-flex items-center px-3 py-2 bg-blue-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-700 focus:bg-blue-700 active:bg-blue-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 transition ease-in-out duration-150 shadow"><i class="bi bi-eye" title="View All"></i></a>
                <a href="{{ route('customers.create') }}" class="inline-flex items-center px-3 py-2 bg-emerald-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-emerald-700 focus:bg-emerald-700 active:bg-emerald-900 focus:outline-none focus:ring-2 focus:ring-emerald-500 transition ease-in-out duration-150 shadow"><i class="bi bi-plus-lg" style="-webkit-text-stroke: 1px;" title="Create"></i></a>
            </div>
            @endif
        </div>
        <div class="bg-white dark:bg-gray-800 dark:text-white overflow-hidden shadow-sm sm:rounded-lg p-6 mb-6 mt-2">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h5 class="m-0"><i class="bi bi-people text-primary me-2"></i> Gadget Retailer Customers Directory</h5>
            </div>
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700 mt-4">
                    <thead class="bg-gray-50 dark:bg-gray-700 text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                        <tr>
                            <th class="px-4 py-3 text-left">Customer #</th>
                            <th class="px-4 py-3 text-left">Customer Name</th>
                            <th class="px-4 py-3 text-left">City</th>
                            <th class="px-4 py-3 text-left">Region</th>
                            <th class="px-4 py-3 text-left">Credit Limit</th>
                            <th class="text-end"></th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200 dark:divide-gray-700 dark:text-white">
                        @forelse($allCustomers ?? [] as $cust)
                        <tr class="selectable-row" style="cursor: pointer;" data-id="{{ $cust->customerNumber }}">
                            <td class="px-4 py-3 whitespace-nowrap text-sm"><strong>#{{ $cust->customerNumber }}</strong></td>
                            <td class="px-4 py-3 whitespace-nowrap text-sm"><strong>{{ $cust->customerName }}</strong></td>
                            <td class="px-4 py-3 whitespace-nowrap text-sm">{{ $cust->city }}</td>
                            <td class="px-4 py-3 whitespace-nowrap text-sm"><span class="badge bg-light text-primary border border-primary">{{ $cust->country }}</span></td>
                            <td class="px-4 py-3 whitespace-nowrap text-sm">₱{{ number_format($cust->creditLimit, 2) }}</td>
<td class="px-4 py-3 whitespace-nowrap text-end text-sm font-medium">
    <div class="flex justify-end gap-2">
        <a href="{{ route('customers.show', $cust->customerNumber) }}" class="inline-block px-3 py-1 bg-blue-500 text-white rounded shadow hover:bg-blue-600 text-xs text-decoration-none" title="View"><i class="bi bi-eye"></i></a>
        <a href="{{ route('customers.edit', $cust->customerNumber) }}" class="inline-block px-3 py-1 bg-amber-500 text-white rounded shadow hover:bg-amber-600 text-xs text-decoration-none" title="Update"><i class="bi bi-pencil-square"></i></a>
        <form action="{{ route('customers.destroy', $cust->customerNumber) }}" method="POST" class="inline" onsubmit="return confirm('Are you sure you want to delete this?')">
            @csrf
            @method('DELETE')
            <button type="submit" class="inline-block px-3 py-1 bg-red-600 text-white rounded shadow hover:bg-red-700 text-xs border-0" title="Delete"><i class="bi bi-trash"></i></button>
        </form>
    </div>
</td>
</tr>
                        @empty
                        <tr>
                            <td colspan="6" class="text-center text-muted p-4">No branches found.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    @endif

    {{-- ==================== TAB 6: SALES LEDGER ==================== --}}
    @if($activeTab === 'sales')
    <div id="tab-sales" class="tab-pane-content">
        <div class="flex flex-col sm:flex-row justify-between items-center mb-4 gap-3">
            <div class="flex items-center gap-2 w-full sm:w-auto">
                <div class="relative w-full sm:w-72">
                    <i class="bi bi-search absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-400"></i>
                    <input type="text" class="tab-search-input form-control ps-5 w-full text-gray-900 dark:text-white dark:bg-gray-700" placeholder="Search orders..." style="height: 40px; border-radius: 8px;">
                </div>
                <button class="px-3 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 transition tab-search-btn" style="height: 40px;">Search</button>
            </div>
            @if(auth()->user()->roles->whereIn('name', ['super_admin', 'admin', 'Super Admin', 'Admin'])->isNotEmpty())
            <div class="flex justify-end gap-2 shrink-0">
                <a href="{{ route('orders.index') }}" class="inline-flex items-center px-3 py-2 bg-blue-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-700 focus:bg-blue-700 active:bg-blue-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 transition ease-in-out duration-150 shadow"><i class="bi bi-eye" title="View All"></i></a>
                <a href="{{ route('orders.create') }}" class="inline-flex items-center px-3 py-2 bg-emerald-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-emerald-700 focus:bg-emerald-700 active:bg-emerald-900 focus:outline-none focus:ring-2 focus:ring-emerald-500 transition ease-in-out duration-150 shadow"><i class="bi bi-plus-lg" style="-webkit-text-stroke: 1px;" title="Create"></i></a>
            </div>
            @endif
        </div>
        <div class="bg-white dark:bg-gray-800 dark:text-white overflow-hidden shadow-sm sm:rounded-lg p-6 mb-6">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h5 class="m-0"><i class="bi bi-table text-primary me-2"></i> Gadget Sales & Payments Order Details</h5>
            </div>

            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700 mt-4" id="payments-ledger-table">
                    <thead class="bg-gray-50 dark:bg-gray-700 text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                        <tr>
                            <th class="px-4 py-3 text-left">Order Details ID</th>
                            <th class="px-4 py-3 text-left">Customer Name</th>
                            <th class="px-4 py-3 text-left">City Location</th>
                            <th class="px-4 py-3 text-left">Gadget Ordered</th>
                            <th class="px-4 py-3 text-left">Quantity</th>
                            <th class="px-4 py-3 text-left">Payment Date</th>
                            <th class="px-4 py-3 text-left">Total Amount (₱)</th>
                            <th class="px-4 py-3 text-left">Status</th>
                            <th class="px-4 py-3 text-right">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200 dark:divide-gray-700 dark:text-white">
                        @forelse($payments ?? [] as $pay)
                        <tr class="payments-ledger-row selectable-row" style="cursor: pointer;" data-payment-json="{{ json_encode($pay) }}" data-id="{{ $pay->orderNumber }}">
                            <td class="px-4 py-3 whitespace-nowrap text-sm"><strong>#{{ $pay->orderNumber }}</strong></td>
                            <td class="customer-name-cell">{{ $pay->customerName }}</td>
                            <td class="city-cell">{{ $pay->city }}</td>
                            <td class="px-4 py-3 whitespace-nowrap text-sm"><span class="text-sm font-semibold text-gray-700">{{ $pay->purchasedGadget ?? 'N/A' }}</span></td>
                            <td class="text-center"><span class="badge bg-secondary">{{ $pay->quantityOrdered ?? 1 }}x</span></td>
                            <td class="px-4 py-3 whitespace-nowrap text-sm"><i class="bi bi-calendar-event text-muted me-1"></i> {{ date('M d, Y', strtotime($pay->paymentDate)) }}</td>
                            <td class="px-4 py-3 whitespace-nowrap text-sm"><strong>₱{{ number_format($pay->amount, 2) }}</strong></td>
                            <td class="px-4 py-3 whitespace-nowrap text-sm"><span class="status-badge">Completed</span></td>
                            <td class="px-4 py-3 whitespace-nowrap text-right text-sm font-medium">
                                <div class="flex justify-end gap-2">
                                    <a href="{{ route('orders.show', $pay->orderNumber) }}" class="inline-block px-3 py-1 bg-blue-500 text-white rounded shadow hover:bg-blue-600 text-xs text-decoration-none" title="View">
                                        <i class="bi bi-eye"></i>
                                    </a>
                                    <a href="{{ route('orders.edit', $pay->orderNumber) }}" class="inline-block px-3 py-1 bg-amber-500 text-white rounded shadow hover:bg-amber-600 text-xs text-decoration-none" title="Update">
                                        <i class="bi bi-pencil-square"></i>
                                    </a>
                                    <form action="{{ route('orders.destroy', $pay->orderNumber) }}" method="POST" class="inline" onsubmit="return confirm('Are you sure you want to delete this?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="inline-block px-3 py-1 bg-red-600 text-white rounded shadow hover:bg-red-700 text-xs border-0" title="Delete">
                                            <i class="bi bi-trash"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="8" style="text-align: center; color: var(--text-muted); padding: 4rem;">
                                <i class="bi bi-emoji-frown" style="font-size: 2.5rem; display: block; margin-bottom: 0.8rem; color: var(--text-muted);"></i>
                                <strong>Walang natagpuang data.</strong> Try resetting or changing the location focus.
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    @endif

{{-- TRANSACTION RECEIPT MODAL --}}
<x-modal name="receiptModal" maxWidth="2xl">
    <div class="p-6">
        <div class="flex justify-between items-center border-b pb-4 mb-4">
            <h5 class="text-xl font-bold"><i class="bi bi-receipt me-2 text-blue-600 dark:text-blue-400"></i> Store Purchase Receipt</h5>
            <button type="button" x-on:click="$dispatch('close-modal', 'receiptModal')" class="text-gray-400 hover:text-gray-600 dark:text-gray-300">
                <i class="bi bi-x-lg"></i>
            </button>
        </div>
        
        <div class="text-left mb-6">
            <h3 class="text-2xl font-extrabold m-0 text-blue-600 dark:text-blue-400 flex items-center gap-2"><i class="bi bi-cpu"></i> Gadget Dashboard System</h3>
            <p class="text-sm text-gray-500 dark:text-gray-200 mb-0 mt-1">Gadget Dashboard System | v2.0.0</p>
            <small class="text-gray-500 dark:text-gray-200">TechGadgets Corp., NCR, Philippines</small>
        </div>

        <div class="border-t border-b py-3 my-3 border-dashed border-gray-300">
            <div class="flex justify-between mb-2"><span class="text-sm text-gray-500 dark:text-gray-200">Reference Code:</span><strong class="text-sm font-mono" id="m-receipt-voucher">CK-90001</strong></div>
            <div class="flex justify-between mb-2"><span class="text-sm text-gray-500 dark:text-gray-200">Customer Name:</span><strong class="text-sm" id="m-receipt-customer">TechZone Quezon City</strong></div>
            <div class="flex justify-between mb-2"><span class="text-sm text-gray-500 dark:text-gray-200">City / Location:</span><strong class="text-sm" id="m-receipt-city">Quezon City</strong></div>
            <div class="flex justify-between mb-2"><span class="text-sm text-gray-500 dark:text-gray-200">Region:</span><strong class="text-sm" id="m-receipt-region">NCR</strong></div>
            <div class="flex justify-between mb-2"><span class="text-sm text-gray-500 dark:text-gray-200">Transaction Date:</span><strong class="text-sm" id="m-receipt-date">2026-01-12</strong></div>
        </div>

        <div class="bg-gray-50 dark:bg-gray-700 rounded-lg p-4 mb-4 border border-gray-100 dark:border-gray-700">
            <h6 class="font-bold text-gray-700 mb-3 text-sm">PURCHASED PRODUCTS (Wholesale / Retail)</h6>
            <div id="m-receipt-products-list" class="space-y-2">
                <!-- Dynamically populated via JS -->
            </div>
            
            <div class="border-t border-gray-300 mt-4 pt-3 flex justify-between items-center">
                <span class="font-bold text-gray-700">TOTAL AMOUNT</span>
                <h4 class="font-extrabold text-blue-600 dark:text-blue-400 m-0" id="m-receipt-total">₱ 1,250,000.00</h4>
            </div>
        </div>

        <div class="text-center text-gray-500 dark:text-gray-200 text-xs mt-6">
            <p class="mb-1"><i class="bi bi-info-circle me-1"></i> This serves as an official transaction record in the BI Database.</p>
            <p class="mb-0">Powered by Laravel Breeze & Tailwind CSS</p>
        </div>
        
        <div class="mt-6 flex justify-end">
            <button x-on:click="$dispatch('close-modal', 'receiptModal')" class="px-4 py-2 bg-gray-200 text-gray-800 dark:text-white rounded-md font-semibold text-sm hover:bg-gray-300 transition-colors">
                Close
            </button>
        </div>
    </div>
</x-modal>

<div id="themeToast" class="theme-toast" style="display: none;">
    <i class="bi bi-palette-fill text-primary"></i><span id="toastMessage">Theme updated successfully!</span>
</div>
    </div>
</div>

<script>
    // Adjust Chart defaults for dark mode if present
    if (typeof Chart !== 'undefined') {
        const isDark = document.documentElement.classList.contains('dark');
        Chart.defaults.color = isDark ? '#e5e7eb' : '#374151';
        Chart.defaults.scale.grid.color = isDark ? '#374151' : '#f3f4f6';
    }
</script>
</x-app-layout>


<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // --- WORKSPACES DRAWER LOGIC ---
        const workspaceToggleBtn = document.getElementById('workspaceToggleBtn');
        const workspaceTopDrawer = document.getElementById('workspaceTopDrawer');
        const workspaceChevron = document.getElementById('workspaceChevron');

        if (workspaceToggleBtn && workspaceTopDrawer) {
            const drawerState = localStorage.getItem('workspace-drawer-open') || 'true';
            if (drawerState === 'true') {
                workspaceTopDrawer.style.display = 'block';
                if (workspaceChevron) workspaceChevron.style.transform = 'rotate(180deg)';
            }

            workspaceToggleBtn.addEventListener('click', function(e) {
                e.preventDefault();
                const isOpen = workspaceTopDrawer.style.display === 'block';

                if (isOpen) {
                    workspaceTopDrawer.style.display = 'none';
                    if (workspaceChevron) workspaceChevron.style.transform = 'rotate(0deg)';
                    localStorage.setItem('workspace-drawer-open', 'false');
                    showToast('📁 Workspaces Portal hidden');
                } else {
                    workspaceTopDrawer.style.display = 'block';
                    if (workspaceChevron) workspaceChevron.style.transform = 'rotate(180deg)';
                    localStorage.setItem('workspace-drawer-open', 'true');
                    showToast('📂 Workspaces Portal expanded');
                }
            });
        }

        // --- TAB SWITCHING LOGIC ---
        const drawerLinks = document.querySelectorAll('.workspace-drawer-link');
        const tabPanes = document.querySelectorAll('.tab-pane-content');

        drawerLinks.forEach(link => {
            link.addEventListener('click', function(e) {
                e.preventDefault();
                const targetTab = this.getAttribute('data-tab');

                drawerLinks.forEach(btn => btn.classList.remove('active'));
                this.classList.add('active');

                tabPanes.forEach(pane => {
                    pane.style.display = (pane.id === 'tab-' + targetTab) ? 'block' : 'none';
                });

                localStorage.setItem('active-workspace-tab', targetTab);
                showToast(`📂 Switched to ${this.textContent.trim()} workspace!`);
            });
        });

        const savedTab = localStorage.getItem('active-workspace-tab') || 'overview';
        const activeNavTab = document.querySelector(`.workspace-drawer-link[data-tab="${savedTab}"]`);
        if (activeNavTab) activeNavTab.click();

        // --- THEME ALREADY HANDLED GLOBALLY IN app.blade.php ---

        // --- FILTER TOGGLE LOGIC ---
        const periodSelect = document.getElementById('period');
        function togglePeriodFields() {
            if (!periodSelect) return;
            const val = periodSelect.value;
            const customDateGroup = document.getElementById('customDateGroup');
            const yearGroup = document.getElementById('yearGroup');
            const quarterGroup = document.getElementById('quarterGroup');
            const halfGroup = document.getElementById('halfGroup');

            if (val === 'custom') {
                if (customDateGroup) customDateGroup.style.display = 'flex';
                if (yearGroup) yearGroup.style.display = 'none';
                if (quarterGroup) quarterGroup.style.display = 'none';
                if (halfGroup) halfGroup.style.display = 'none';
            } else {
                if (customDateGroup) customDateGroup.style.display = 'none';
                if (yearGroup) yearGroup.style.display = 'block';
                if (val === 'quarterly') {
                    if (quarterGroup) quarterGroup.style.display = 'block';
                    if (halfGroup) halfGroup.style.display = 'none';
                } else if (val === 'semi-annually') {
                    if (quarterGroup) quarterGroup.style.display = 'none';
                    if (halfGroup) halfGroup.style.display = 'block';
                } else if (val === 'annually') {
                    if (quarterGroup) quarterGroup.style.display = 'none';
                    if (halfGroup) halfGroup.style.display = 'none';
                }
            }
        }
        if (periodSelect) {
            periodSelect.addEventListener('change', togglePeriodFields);
            togglePeriodFields();
        }

        document.querySelectorAll('.tab-search-input').forEach(input => {
            input.addEventListener('keyup', function() {
                const term = this.value.toLowerCase().trim();
                const tabPane = this.closest('.tab-pane-content');
                const rows = tabPane.querySelectorAll('tbody tr');
                
                rows.forEach(row => {
                    if (row.children.length === 1) return; // skip empty state row
                    let match = false;
                    row.querySelectorAll('td').forEach(td => {
                        if (td.textContent.toLowerCase().includes(term)) {
                            match = true;
                        }
                    });
                    row.style.display = match ? '' : 'none';
                });
            });
        });

        // --- ROW SELECTION AND BUTTON UPDATE LOGIC ---
        let selectedRowId = null;
        let selectedRowElement = null;

        document.querySelectorAll('tr.selectable-row').forEach(row => {
            row.addEventListener('click', function(e) {
                // Ignore clicks on update/delete action buttons inside the row
                if (e.target.closest('a') || e.target.closest('button')) return;
                
                // Deselect previous
                if (selectedRowElement) {
                    selectedRowElement.classList.remove('selected-row');
                }
                
                // Toggle current
                if (selectedRowElement === this) {
                    selectedRowElement = null;
                    selectedRowId = null;
                } else {
                    this.classList.add('selected-row');
                    selectedRowElement = this;
                    selectedRowId = this.getAttribute('data-id');
                }
            });
        });

        // Top Update Action Buttons
        document.querySelectorAll('.tab-action-update').forEach(btn => {
            btn.addEventListener('click', function(e) {
                e.preventDefault();
                if (!selectedRowId) {
                    showToast('Please select a row first by clicking on it.');
                    return;
                }
                const baseUrl = this.getAttribute('data-base-url');
                window.location.href = `${baseUrl}/${selectedRowId}/edit`;
            });
        });

        // Top Delete Action Buttons
        document.querySelectorAll('.tab-action-delete').forEach(btn => {
            btn.addEventListener('click', function(e) {
                e.preventDefault();
                if (!selectedRowId) {
                    showToast('Please select a row first to delete.');
                    return;
                }
                if (confirm('Are you sure you want to delete the selected row?')) {
                    const baseUrl = this.getAttribute('data-base-url');
                    const form = this.closest('form');
                    form.action = `${baseUrl}/${selectedRowId}`;
                    form.submit();
                }
            });
        });
    });

    // 2. Receipt Modal Function
    function openReceiptModal(pay) {
        document.getElementById('m-receipt-voucher').textContent = pay.checkNumber;
        document.getElementById('m-receipt-customer').textContent = pay.customerName;
        document.getElementById('m-receipt-city').textContent = pay.city;
        document.getElementById('m-receipt-region').textContent = pay.country;
        document.getElementById('m-receipt-date').textContent = pay.paymentDate;

        let pr = pay.purchasedGadget ? pay.purchasedGadget : 'Unknown Gadget';

        document.getElementById('m-receipt-products-list').innerHTML = `<p class="text-sm font-bold text-gray-800 dark:text-white">${pr}</p>`;
        document.getElementById('m-receipt-total').innerHTML = `<span class="text-green-600 dark:text-green-400">₱ ${Number(pay.amount).toLocaleString(undefined, {minimumFractionDigits: 2})}</span>`;

        // Trigger AlpineJS modal
        window.dispatchEvent(new CustomEvent('open-modal', { detail: 'receiptModal' }));
    }
    
    document.querySelectorAll('.payments-ledger-row').forEach(row => {
        row.addEventListener('click', function() {
            try {
                const payment = JSON.parse(this.getAttribute('data-payment-json'));
                openReceiptModal(payment);
            } catch (e) { console.error('Error parsing payment JSON:', e); }
        });
    });

    // 5. Chart.js Render Logic
    const ctxMoM = document.getElementById('chart-mom-trend');
    if (ctxMoM) {
        new Chart(ctxMoM, {
            type: 'line',
            data: {
                labels: {!! json_encode($q8_MoMTrend->pluck('month_year')) !!},
                datasets: [{
                    label: 'Monthly Sales (₱)',
                    data: {!! json_encode($q8_MoMTrend->pluck('total_sales')) !!},
                    borderColor: '#3b82f6',
                    backgroundColor: 'rgba(59, 130, 246, 0.1)',
                    borderWidth: 3,
                    tension: 0.4,
                    fill: true,
                    pointBackgroundColor: '#3b82f6',
                    pointRadius: 4
                }]
            },
            options: { responsive: true, maintainAspectRatio: false, plugins: { legend: { display: false } } }
        });
    }

    const ctxLines = document.getElementById('chart-product-lines');
    if (ctxLines) {
        new Chart(ctxLines, {
            type: 'doughnut',
            data: {
                labels: {!! json_encode($q4_ProductLines->pluck('textDescription')) !!},
                datasets: [{
                    data: {!! json_encode($q4_ProductLines->pluck('total')) !!},
                    backgroundColor: ['#8b5cf6', '#ec4899', '#3b82f6', '#10b981', '#f59e0b'],
                    borderWidth: 0
                }]
            },
            options: { responsive: true, maintainAspectRatio: false, cutout: '70%' }
        });
    }

    const ctxRegion = document.getElementById('chart-region-orders');
    if (ctxRegion) {
        new Chart(ctxRegion, {
            type: 'bar',
            data: {
                labels: {!! json_encode($q7_RegionOrders->pluck('country')) !!},
                datasets: [{
                    label: 'Total Orders',
                    data: {!! json_encode($q7_RegionOrders->pluck('total_orders')) !!},
                    backgroundColor: '#6366f1',
                    borderRadius: 6
                }]
            },
            options: { responsive: true, maintainAspectRatio: false, indexAxis: 'y', plugins: { legend: { display: false } }, scales: { x: { grid: { color: '#cbd5e1' }, ticks: { color: '#475569', callback: v => '₱' + v.toLocaleString() } }, y: { grid: { display: false }, ticks: { color: 'var(--text-main)', font: { weight: '600' } } } } }
        });
    }
    
    function showToast(msg) {
        const toast = document.getElementById('themeToast');
        const msgEl = document.getElementById('toastMessage');
        if (toast && msgEl) {
            msgEl.textContent = msg;
            toast.style.display = 'flex';
            if (window.toastTimer) clearTimeout(window.toastTimer);
            window.toastTimer = setTimeout(() => { toast.style.display = 'none'; }, 2500);
        }
    }
</script>
