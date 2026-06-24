<?php

$entities = [
    'Customer' => ['customerName', 'contactLastName', 'contactFirstName', 'phone', 'addressLine1', 'city', 'country', 'creditLimit'],
    'Employee' => ['lastName', 'firstName', 'extension', 'email', 'officeCode', 'jobTitle'],
    'Office' => ['city', 'phone', 'addressLine1', 'country'],
    'Product' => ['productName', 'productLine', 'productVendor', 'quantityInStock', 'buyPrice'],
    'Order' => ['orderDate', 'requiredDate', 'status', 'customerNumber'],
    'Role' => ['name']
];

foreach ($entities as $model => $fields) {
    $plural = strtolower($model) . 's';
    if ($model == 'Office') $plural = 'offices';
    
    @mkdir(__DIR__ . "/resources/views/crud/{$plural}", 0777, true);
    
    // Index View (Beautiful Dashboard Style)
    $indexView = "<x-app-layout>\n    <x-slot name=\"header\">\n        <h2 class=\"font-semibold text-xl text-gray-800 leading-tight\">Management</h2>\n    </x-slot>\n";
    $indexView .= "<div class=\"py-8\">\n    <div class=\"max-w-7xl mx-auto sm:px-6 lg:px-8\">\n        <div class=\"container my-4 dash-wrapper\">\n";
    
    // Action Buttons
    $indexView .= "            <div class=\"flex justify-end mb-4 gap-2\">\n";
    $indexView .= "                <a href=\"{{ route('{$plural}.index') }}\" class=\"inline-flex items-center px-4 py-2 bg-blue-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-700 focus:bg-blue-700 active:bg-blue-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150 shadow\">\n                    <i class=\"bi bi-list-ul mr-2\"></i> View All\n                </a>\n";
    $indexView .= "                <a href=\"{{ route('{$plural}.create') }}\" class=\"inline-flex items-center px-4 py-2 bg-emerald-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-emerald-700 focus:bg-emerald-700 active:bg-emerald-900 focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:ring-offset-2 transition ease-in-out duration-150 shadow\">\n                    <i class=\"bi bi-plus-circle-fill mr-2\"></i> Create\n                </a>\n";
    $indexView .= "            </div>\n";
    
    // Table Card
    $indexView .= "            <div class=\"table-card\">\n                <div class=\"d-flex justify-content-between align-items-center mb-4\">\n                    <h5 class=\"m-0\"><i class=\"bi bi-table text-primary me-2\"></i> Manage {$model}s</h5>\n                </div>\n";
    $indexView .= "                <div class=\"table-responsive-wrapper\">\n                    <table class=\"custom-table\">\n                        <thead><tr>";
    foreach ($fields as $field) {
        $indexView .= "<th>" . ucfirst($field) . "</th>";
    }
    $indexView .= "<th class=\"text-end\">Actions</th></tr></thead>\n                        <tbody>\n";
    $indexView .= "                            @foreach(\$items as \$item)\n                            <tr>\n";
    foreach ($fields as $field) {
        $indexView .= "                                <td>{{ \$item->{$field} }}</td>\n";
    }
    
    // Actions Column
    $indexView .= "                                <td class=\"text-end\">\n";
    $indexView .= "                                    <a href=\"{{ route('{$plural}.edit', \$item) }}\" class=\"inline-flex items-center justify-center w-8 h-8 rounded border border-blue-500 text-blue-500 hover:bg-blue-50 transition-colors me-1 bg-white\" title=\"Edit\">\n                                        <i class=\"bi bi-pencil-fill\"></i>\n                                    </a>\n";
    $indexView .= "                                    <form action=\"{{ route('{$plural}.destroy', \$item) }}\" method=\"POST\" class=\"inline\" style=\"display: inline;\">\n                                        @csrf\n                                        @method('DELETE')\n                                        <button type=\"submit\" class=\"inline-flex items-center justify-center w-8 h-8 rounded border border-red-500 text-red-500 hover:bg-red-50 transition-colors bg-white\" title=\"Delete\" onclick=\"return confirm('Are you sure you want it deleted?')\">\n                                            <i class=\"bi bi-trash\"></i>\n                                        </button>\n                                    </form>\n                                </td>\n";
    $indexView .= "                            </tr>\n                            @endforeach\n                        </tbody>\n                    </table>\n                </div>\n            </div>\n";
    $indexView .= "        </div>\n    </div>\n</div>\n</x-app-layout>";
    
    file_put_contents(__DIR__ . "/resources/views/crud/{$plural}/index.blade.php", $indexView);
    
    // Create View (Beautiful Dashboard Style)
    $createView = "<x-app-layout>\n    <x-slot name=\"header\">\n        <h2 class=\"font-semibold text-xl text-gray-800 leading-tight\">Management</h2>\n    </x-slot>\n";
    $createView .= "<div class=\"py-8\">\n    <div class=\"max-w-7xl mx-auto sm:px-6 lg:px-8\">\n        <div class=\"container my-4 dash-wrapper\">\n            <div class=\"table-card\" style=\"max-width: 800px; margin: 0 auto;\">\n";
    $createView .= "<h5 class=\"m-0 mb-4\"><i class=\"bi bi-plus-circle text-primary me-2\"></i> Create {$model}</h5>\n";
    $createView .= "<form action=\"{{ route('{$plural}.store') }}\" method='POST'>\n@csrf\n";
    foreach ($fields as $field) {
        $createView .= "<div class='mb-4'>\n<label class='block text-sm font-bold mb-2' style='color: var(--text-main);'>".ucfirst($field)."</label>\n";
        $createView .= "<input type='text' name='{$field}' class='form-control' required>\n</div>\n";
    }
    $createView .= "<button type='submit' class=\"inline-flex items-center px-4 py-2 bg-emerald-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-emerald-700 focus:bg-emerald-700 active:bg-emerald-900 focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:ring-offset-2 transition ease-in-out duration-150 shadow\">Save {$model}</button>\n";
    $createView .= "</form>\n</div></div></div></div>\n</x-app-layout>";
    file_put_contents(__DIR__ . "/resources/views/crud/{$plural}/create.blade.php", $createView);
    
    // Edit View (Beautiful Dashboard Style)
    $editView = "<x-app-layout>\n    <x-slot name=\"header\">\n        <h2 class=\"font-semibold text-xl text-gray-800 leading-tight\">Management</h2>\n    </x-slot>\n";
    $editView .= "<div class=\"py-8\">\n    <div class=\"max-w-7xl mx-auto sm:px-6 lg:px-8\">\n        <div class=\"container my-4 dash-wrapper\">\n            <div class=\"table-card\" style=\"max-width: 800px; margin: 0 auto;\">\n";
    $editView .= "<h5 class=\"m-0 mb-4\"><i class=\"bi bi-pencil-square text-primary me-2\"></i> Edit {$model}</h5>\n";
    $editView .= "<form action=\"{{ route('{$plural}.update', \$item) }}\" method='POST'>\n@csrf\n@method('PUT')\n";
    foreach ($fields as $field) {
        $editView .= "<div class='mb-4'>\n<label class='block text-sm font-bold mb-2' style='color: var(--text-main);'>".ucfirst($field)."</label>\n";
        $editView .= "<input type='text' name='{$field}' value=\"{{ \$item->{$field} }}\" class='form-control' required>\n</div>\n";
    }
    $editView .= "<button type='submit' class=\"inline-flex items-center px-4 py-2 bg-amber-500 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-amber-600 focus:bg-amber-600 active:bg-amber-700 focus:outline-none focus:ring-2 focus:ring-amber-500 focus:ring-offset-2 transition ease-in-out duration-150 shadow\">Update {$model}</button>\n";
    $editView .= "</form>\n</div></div></div></div>\n</x-app-layout>";
    file_put_contents(__DIR__ . "/resources/views/crud/{$plural}/edit.blade.php", $editView);
}

echo "Beautiful UI Views regenerated successfully.\n";
