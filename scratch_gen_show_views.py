import os
import re

entities = {
    'Customer': ('customers', ['customerName', 'contactLastName', 'contactFirstName', 'phone', 'addressLine1', 'city', 'country', 'creditLimit']),
    'Employee': ('employees', ['lastName', 'firstName', 'extension', 'email', 'officeCode', 'jobTitle']),
    'Office': ('offices', ['city', 'phone', 'addressLine1', 'country']),
    'Product': ('products', ['productName', 'productLine', 'productVendor', 'quantityInStock', 'buyPrice']),
}

for model, (plural, fields) in entities.items():
    # 1. Update Controller
    controller_path = f"c:/phpproj/bi-project/app/Http/Controllers/{model}Controller.php"
    if os.path.exists(controller_path):
        with open(controller_path, "r") as f:
            content = f.read()
        
        if "public function show" not in content:
            # find store method and inject after it
            var_name = model.lower()
            show_method = f"""
    public function show({model} ${var_name})
    {{
        return view('crud.{plural}.show', ['item' => ${var_name}]);
    }}
"""
            content = re.sub(r'(public function store\(.*?\)\s*\{.*?\n    \})', r'\1\n' + show_method, content, flags=re.DOTALL)
            with open(controller_path, "w") as f:
                f.write(content)

    # 2. Create show.blade.php
    view_dir = f"c:/phpproj/bi-project/resources/views/crud/{plural}"
    os.makedirs(view_dir, exist_ok=True)
    view_path = f"{view_dir}/show.blade.php"
    
    view_content = f"""<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{{{ __('View {model}') }}}}
        </h2>
    </x-slot>
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 dark:text-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-white">
"""
    for field in fields:
        view_content += f"""                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">{field.capitalize()}</label>
                        <p class="border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 rounded-md shadow-sm block w-full p-2">{{{{ $item->{field} }}}}</p>
                    </div>
"""
    
    view_content += f"""                    <div class="flex items-center justify-end mt-4 gap-2">
                        <a href="{{{{ route('{plural}.edit', $item) }}}}" class="inline-flex items-center px-4 py-2 bg-amber-500 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-amber-600 focus:bg-amber-600 active:bg-amber-900 focus:outline-none focus:ring-2 focus:ring-amber-500 focus:ring-offset-2 transition ease-in-out duration-150 shadow-sm text-decoration-none">
                            Edit {model}
                        </a>
                        <a href="{{{{ route('{plural}.index') }}}}" class="inline-flex items-center px-4 py-2 bg-gray-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 focus:bg-gray-700 active:bg-gray-900 focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2 transition ease-in-out duration-150 shadow-sm text-decoration-none">
                            Back
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
"""
    with open(view_path, "w") as f:
        f.write(view_content)
        
print("Updated controllers and views.")
