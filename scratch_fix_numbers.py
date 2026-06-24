import os
import re

numeric_fields = ['productLine', 'quantityInStock', 'customerNumber', 'salesRepEmployeeNumber', 'employeeNumber', 'reportsTo', 'orderNumber']
decimal_fields = ['buyPrice', 'MSRP', 'creditLimit']

base_path = "c:/phpproj/bi-project/resources/views/crud"

for root, dirs, files in os.walk(base_path):
    for file in files:
        if file in ['create.blade.php', 'edit.blade.php']:
            filepath = os.path.join(root, file)
            with open(filepath, "r", encoding="utf-8") as f:
                content = f.read()
            
            # replace text with number for numeric fields
            for field in numeric_fields:
                content = re.sub(
                    rf'<input type="text" name="{field}"',
                    f'<input type="number" name="{field}"',
                    content
                )
            
            # replace text with number step="any" for decimal fields
            for field in decimal_fields:
                content = re.sub(
                    rf'<input type="text" name="{field}"',
                    f'<input type="number" step="any" name="{field}"',
                    content
                )
            
            with open(filepath, "w", encoding="utf-8") as f:
                f.write(content)

print("Updated input types for numeric fields in all create and edit views.")
