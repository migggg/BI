import os
import re

entities = {
    'employees': ('EmployeeNumber', 'employeeNumber'),
    'offices': ('OfficeCode', 'officeCode'),
    'customers': ('CustomerNumber', 'customerNumber'),
    'products': ('ProductCode', 'productCode'),
    'orders': ('OrderNumber', 'orderNumber'),
}

for folder, (label, name) in entities.items():
    filepath = f"c:/phpproj/bi-project/resources/views/crud/{folder}/create.blade.php"
    if os.path.exists(filepath):
        with open(filepath, "r", encoding="utf-8") as f:
            content = f.read()
        
        # Check if the field already exists
        if f'name="{name}"' not in content:
            # We want to inject it right after @csrf
            inject_html = f"""
                        <div class="mb-4">
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">{label}</label>
                            <input type="text" name="{name}" class="border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm block w-full" required>
                        </div>"""
            
            content = content.replace("@csrf", "@csrf" + inject_html)
            
            with open(filepath, "w", encoding="utf-8") as f:
                f.write(content)
                
print("Added primary key fields to all create forms.")
