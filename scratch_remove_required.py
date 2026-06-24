import os

base_path = "c:/phpproj/bi-project/resources/views/crud"

for root, dirs, files in os.walk(base_path):
    for file in files:
        if file in ['create.blade.php', 'edit.blade.php']:
            filepath = os.path.join(root, file)
            with open(filepath, "r", encoding="utf-8") as f:
                content = f.read()
            
            # Remove the string " required>" and replace with ">"
            content = content.replace(' required>', '>')
            
            with open(filepath, "w", encoding="utf-8") as f:
                f.write(content)

print("Removed strict required attributes from inputs.")
