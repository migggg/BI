import os
import re

entities = ['customers', 'employees', 'offices', 'products', 'orders']

for entity in entities:
    filepath = f"c:/phpproj/bi-project/resources/views/crud/{entity}/index.blade.php"
    if os.path.exists(filepath):
        with open(filepath, "r", encoding="utf-8") as f:
            content = f.read()
        
        # Replace the Edit button with View + Edit.
        # Find: <a href="{{ route('entity.edit', $item) }}" ...>Edit</a>
        # Prepend: <a href="{{ route('entity.show', $item) }}" class="text-green-600 dark:text-green-400 hover:text-green-900 dark:hover:text-green-300 mr-3">View</a>
        
        def replace_edit(match):
            e = match.group(1)
            var = match.group(2)
            full_match = match.group(0)
            
            view_btn = f'<a href="{{{{ route(\'{e}.show\', {var}) }}}}" class="text-green-600 dark:text-green-400 hover:text-green-900 dark:hover:text-green-300 mr-3">View</a>\n                                        '
            return view_btn + full_match
        
        content = re.sub(r'<a href="\{\{\s*route\(\'([a-zA-Z_]+)\.edit\',\s*([^\)]+)\)\s*\}\}"[^>]*>Edit</a>', replace_edit, content)
        
        with open(filepath, "w", encoding="utf-8") as f:
            f.write(content)

print("index views patched.")
