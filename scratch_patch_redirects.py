import os
import re

controllers = {
    'OfficeController': 'offices',
    'EmployeeController': 'employees',
    'ProductController': 'products',
    'CustomerController': 'customers',
    'OrderController': 'sales',
}

for ctrl_name, tab_name in controllers.items():
    filepath = f"c:/phpproj/bi-project/app/Http/Controllers/{ctrl_name}.php"
    if os.path.exists(filepath):
        with open(filepath, "r", encoding="utf-8") as f:
            content = f.read()
        
        # We need to replace `redirect()->route('ENTITY.index')` with `redirect()->route('dashboard', ['tab' => 'TAB'])`
        # Find: return redirect()->route('[a-z]+.index')
        new_redirect = f"return redirect()->route('dashboard', ['tab' => '{tab_name}'])"
        
        content = re.sub(r"return\s+redirect\(\)->route\('[a-z]+\.index'\)", new_redirect, content)
        
        with open(filepath, "w", encoding="utf-8") as f:
            f.write(content)

print("Controllers patched with dashboard redirects.")
