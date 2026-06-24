import re

file_path = r"c:\phpproj\bi-project\resources\views\BusinessInt(view)\Display.blade.php"

with open(file_path, "r", encoding="utf-8") as f:
    content = f.read()

# Replace View All buttons (bg-blue-600) inner HTML with just the bi-eye icon
content = re.sub(
    r'(<a href="\{\{ route\(\'[^.]+\.index\'\) \}\}" class="[^"]*?bg-blue-600[^"]*?">)[^<]*<i class="bi bi-[^"]*?"(?: title="View All")?></i>(?: View All)?(.*?)</a>',
    r'\1<i class="bi bi-eye" title="View All"></i></a>',
    content
)

# Replace Create buttons (bg-emerald-600) inner HTML with just the bi-plus-lg icon
content = re.sub(
    r'(<a href="\{\{ route\(\'[^.]+\.create\'\) \}\}" class="[^"]*?bg-emerald-600[^"]*?">)[^<]*<i class="bi bi-[^"]*?"(?: title="Create")?></i>(?: Create)?(.*?)</a>',
    r'\1<i class="bi bi-plus-lg font-bold" title="Create"></i></a>',
    content
)

# Let me also be more robust in case the regex didn't catch the exact format
content = re.sub(
    r'(<a href="\{\{ route\(\'[^.]+\.index\'\) \}\}" class="[^"]*?bg-blue-600[^"]*?">).*?(</a>)',
    r'\1<i class="bi bi-eye" title="View All"></i>\2',
    content
)

content = re.sub(
    r'(<a href="\{\{ route\(\'[^.]+\.create\'\) \}\}" class="[^"]*?bg-emerald-600[^"]*?">).*?(</a>)',
    r'\1<i class="bi bi-plus-lg" style="-webkit-text-stroke: 1px;" title="Create"></i>\2',
    content
)

with open(file_path, "w", encoding="utf-8") as f:
    f.write(content)

print("Updated View All and Create buttons to icons successfully")
