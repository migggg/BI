import re

file_path = r"c:\phpproj\bi-project\resources\views\BusinessInt(view)\Display.blade.php"

with open(file_path, "r", encoding="utf-8") as f:
    content = f.read()

# Pattern to match the Update <a> tag and the Delete <form> tag that follow each other
pattern = re.compile(
    r'\s*<a href="javascript:void\(0\)" class="tab-action-update.*?</a>'
    r'\s*<form class="tab-action-delete-form.*?</form>',
    re.DOTALL
)

content = pattern.sub('', content)

with open(file_path, "w", encoding="utf-8") as f:
    f.write(content)

print("Top level Update and Delete buttons removed successfully")
