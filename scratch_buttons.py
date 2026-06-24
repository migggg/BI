import re

file_path = r"c:\phpproj\bi-project\resources\views\BusinessInt(view)\Display.blade.php"

with open(file_path, "r", encoding="utf-8") as f:
    content = f.read()

# 1. Replace inline row text buttons with icons
content = content.replace(">Update</a>", ' title="Update"><i class="bi bi-pencil-square"></i></a>')
content = content.replace(">Delete</button>", ' title="Delete"><i class="bi bi-trash"></i></button>')

# 2. Replace top-level action buttons (remove text, keep icons, add tooltips, remove mr-2 margin)
content = content.replace('<i class="bi bi-building mr-2"></i> View All', '<i class="bi bi-building" title="View All"></i>')
content = content.replace('<i class="bi bi-plus-circle-fill mr-2"></i> Create', '<i class="bi bi-plus-circle-fill" title="Create"></i>')
content = content.replace('<i class="bi bi-pencil-square mr-2"></i> Update', '<i class="bi bi-pencil-square" title="Update"></i>')
content = content.replace('<i class="bi bi-trash mr-2"></i> Delete', '<i class="bi bi-trash" title="Delete"></i>')

# Also remove px-4 from the top-level buttons to make them more square/iconic if they are icon-only
content = re.sub(r'class="([^"]*?)px-4 py-2 bg-blue-600', r'class="\1px-3 py-2 bg-blue-600', content)
content = re.sub(r'class="([^"]*?)px-4 py-2 bg-emerald-600', r'class="\1px-3 py-2 bg-emerald-600', content)
content = re.sub(r'class="([^"]*?)px-4 py-2 bg-amber-500', r'class="\1px-3 py-2 bg-amber-500', content)
content = re.sub(r'class="([^"]*?)px-4 py-2 bg-red-600', r'class="\1px-3 py-2 bg-red-600', content)


with open(file_path, "w", encoding="utf-8") as f:
    f.write(content)
print("Updated buttons to icons successfully")
