import os
import re
import json

models_dir = "c:/phpproj/bi-project/app/Models"
output = {}

for filename in os.listdir(models_dir):
    if filename.endswith(".php"):
        model_name = filename[:-4]
        with open(os.path.join(models_dir, filename), "r", encoding="utf-8") as f:
            content = f.read()
            
        match = re.search(r'protected\s+\$fillable\s*=\s*\[(.*?)\];', content, re.DOTALL)
        if match:
            fields_str = match.group(1)
            # Find all strings in quotes
            fields = re.findall(r"'(.*?)'", fields_str)
            output[model_name] = fields

with open("c:/phpproj/bi-project/scratch_model_fields.json", "w") as f:
    json.dump(output, f, indent=4)
print("Extracted fields to scratch_model_fields.json")
