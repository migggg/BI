import re

filepath = "c:/phpproj/bi-project/resources/views/BusinessInt(View)/Display.blade.php"
with open(filepath, "r", encoding="utf-8") as f:
    content = f.read()

# Replace @role checks for top buttons
content = content.replace(
    "@role('super_admin|admin')",
    "@if(auth()->user()->roles->whereIn('name', ['super_admin', 'admin', 'Super Admin', 'Admin'])->isNotEmpty())"
)
content = content.replace("@endrole", "@endif")

# Add the 'View' button before 'Edit' button
# Find instances of: <a href="{{ route('ENTITY.edit', $VAR) }}" class="..."><i class="bi bi-pencil-square"></i></a>
# and prepend the View button.
def replace_edit(match):
    entity = match.group(1)
    var = match.group(2)
    full_match = match.group(0)
    
    view_btn = f'<a href="{{{{ route(\'{entity}.show\', {var}) }}}}" class="inline-block px-3 py-1 bg-blue-500 text-white rounded shadow hover:bg-blue-600 text-xs text-decoration-none" title="View"><i class="bi bi-eye"></i></a>\n        '
    return view_btn + full_match

content = re.sub(r'<a href="\{\{\s*route\(\'([a-zA-Z_]+)\.edit\',\s*([^\)]+)\)\s*\}\}"[^>]*title="Update"[^>]*>.*?</a>', replace_edit, content)

with open(filepath, "w", encoding="utf-8") as f:
    f.write(content)

print("Display.blade.php patched.")
