import os
import json

with open("c:/phpproj/bi-project/scratch_model_fields.json", "r") as f:
    model_fields = json.load(f)

entities_map = {
    'Customer': 'customers',
    'Employee': 'employees',
    'Office': 'offices',
    'Order': 'orders',
    'Product': 'products'
}

number_fields = ['productLine', 'quantityInStock', 'customerNumber', 'salesRepEmployeeNumber', 'employeeNumber', 'reportsTo', 'orderNumber']
decimal_fields = ['buyPrice', 'MSRP', 'creditLimit']
date_fields = ['orderDate', 'requiredDate', 'shippedDate']

def get_input_type(field):
    if field in number_fields:
        return 'type="number"'
    elif field in decimal_fields:
        return 'type="number" step="any"'
    elif field in date_fields:
        return 'type="date"'
    return 'type="text"'

for model_name, folder in entities_map.items():
    fields = model_fields.get(model_name, [])
    if not fields:
        continue
    
    view_dir = f"c:/phpproj/bi-project/resources/views/crud/{folder}"
    os.makedirs(view_dir, exist_ok=True)
    
    # --- CREATE VIEW ---
    create_html = f"""<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{{{ __('Create {model_name}') }}}}
        </h2>
    </x-slot>
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 dark:text-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-white">
                    <form action="{{{{ route('{folder}.store') }}}}" method="POST">
                        @csrf
"""
    for field in fields:
        input_type = get_input_type(field)
        create_html += f"""                        <div class="mb-4">
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">{field}</label>
                            <input {input_type} name="{field}" class="border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm block w-full" required>
                        </div>
"""
    create_html += f"""                        <div class="flex items-center justify-end mt-4">
                            <button type="submit" class="inline-flex items-center px-4 py-2 bg-blue-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-700 focus:bg-blue-700 active:bg-blue-900 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition ease-in-out duration-150 shadow-sm">
                                Save {model_name}
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
"""
    with open(f"{view_dir}/create.blade.php", "w", encoding="utf-8") as f:
        f.write(create_html)

    # --- EDIT VIEW ---
    edit_html = f"""<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{{{ __('Edit {model_name}') }}}}
        </h2>
    </x-slot>
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 dark:text-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-white">
                    <form action="{{{{ route('{folder}.update', $item) }}}}" method="POST">
                        @csrf
                        @method('PUT')
"""
    for field in fields:
        input_type = get_input_type(field)
        # make all edit fields required EXCEPT we can manually patch if needed later, but standard is required
        edit_html += f"""                        <div class="mb-4">
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">{field}</label>
                            <input {input_type} name="{field}" value="{{{{ $item->{field} }}}}" class="border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm block w-full" required>
                        </div>
"""
    edit_html += f"""                        <div class="flex items-center justify-end mt-4">
                            <button type="submit" class="inline-flex items-center px-4 py-2 bg-blue-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-700 focus:bg-blue-700 active:bg-blue-900 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition ease-in-out duration-150 shadow-sm">
                                Update {model_name}
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
"""
    with open(f"{view_dir}/edit.blade.php", "w", encoding="utf-8") as f:
        f.write(edit_html)

    # --- SHOW VIEW ---
    show_html = f"""<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{{{ __('View {model_name}') }}}}
        </h2>
    </x-slot>
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 dark:text-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-white">
"""
    for field in fields:
        show_html += f"""                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">{field}</label>
                        <p class="border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 rounded-md shadow-sm block w-full p-2">{{{{ $item->{field} }}}}</p>
                    </div>
"""
    show_html += f"""                    <div class="flex items-center justify-end mt-4 gap-2">
                        <a href="{{{{ route('{folder}.edit', $item) }}}}" class="inline-flex items-center px-4 py-2 bg-amber-500 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-amber-600 focus:bg-amber-600 active:bg-amber-900 focus:outline-none focus:ring-2 focus:ring-amber-500 focus:ring-offset-2 transition ease-in-out duration-150 shadow-sm text-decoration-none">
                            Edit {model_name}
                        </a>
                        <a href="{{{{ route('dashboard', ['tab' => '{folder}']) }}}}" class="inline-flex items-center px-4 py-2 bg-gray-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 focus:bg-gray-700 active:bg-gray-900 focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2 transition ease-in-out duration-150 shadow-sm text-decoration-none">
                            Back
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
"""
    with open(f"{view_dir}/show.blade.php", "w", encoding="utf-8") as f:
        f.write(show_html)

print("Regenerated all CRUD views with full fields mapping.")
