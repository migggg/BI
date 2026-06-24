import re

file_path = r"c:\phpproj\bi-project\resources\views\BusinessInt(view)\Display.blade.php"

with open(file_path, "r", encoding="utf-8") as f:
    content = f.read()

# 1. Replace hardcoded Eloquent queries with Controller variables
content = re.sub(r'@foreach\(\\App\\Models\\Office::.*?->get\(\) as \$off\)', r'@foreach($offices as $off)', content)
content = re.sub(r'@foreach\(\\App\\Models\\Employee::.*?->get\(\) as \$emp\)', r'@foreach($employees as $emp)', content)
content = re.sub(r'@foreach\(\\App\\Models\\Product::.*?->get\(\) as \$prod\)', r'@foreach($products as $prod)', content)

# 2. Replace Search Bar HTML with <form>
def get_search_form(tab_name, placeholder):
    return f"""            <form action="{{{{ url()->current() }}}}" method="GET" class="flex items-center gap-2 w-full sm:w-auto m-0">
                <input type="hidden" name="tab" value="{tab_name}">
                @if(request('sort'))
                    <input type="hidden" name="sort" value="{{{{ request('sort') }}}}">
                    <input type="hidden" name="direction" value="{{{{ request('direction') }}}}">
                @endif
                <div class="relative w-full sm:w-72">
                    <i class="bi bi-search absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-400"></i>
                    <input type="text" name="search" value="{{{{ request('tab') === '{tab_name}' ? request('search') : '' }}}}" class="form-control ps-5 w-full text-gray-900 dark:text-gray-100 bg-white dark:bg-gray-800 border-gray-300 dark:border-gray-700" placeholder="{placeholder}" style="height: 40px; border-radius: 8px;">
                </div>
                <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 transition" style="height: 40px;">Search</button>
                @if(request('tab') === '{tab_name}' && request('search'))
                    <a href="{{{{ url()->current() }}}}?tab={tab_name}" class="px-4 py-2 bg-gray-500 text-white rounded-md hover:bg-gray-600 transition flex items-center" style="height: 40px;"><i class="bi bi-x-lg"></i></a>
                @endif
            </form>"""

# Replace Offices search
offices_old = r'<div class="flex items-center gap-2 w-full sm:w-auto">\s*<div class="relative w-full sm:w-72">\s*<i class="bi bi-search absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-400"></i>\s*<input type="text" class="tab-search-input form-control ps-5 w-full text-gray-900 dark:text-gray-100 bg-white dark:bg-gray-800 border-gray-300 dark:border-gray-700" placeholder="Search offices..." style="height: 40px; border-radius: 8px;">\s*</div>\s*<button class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 transition tab-search-btn" style="height: 40px;">Search</button>\s*</div>'
content = re.sub(offices_old, get_search_form('offices', 'Search offices...'), content)

# Replace Employees search
emp_old = r'<div class="flex items-center gap-2 w-full sm:w-auto">\s*<div class="relative w-full sm:w-72">\s*<i class="bi bi-search absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-400"></i>\s*<input type="text" class="tab-search-input form-control ps-5 w-full text-gray-900 dark:text-gray-100 bg-white dark:bg-gray-800 border-gray-300 dark:border-gray-700" placeholder="Search employees..." style="height: 40px; border-radius: 8px;">\s*</div>\s*<button class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 transition tab-search-btn" style="height: 40px;">Search</button>\s*</div>'
content = re.sub(emp_old, get_search_form('employees', 'Search employees...'), content)

# Replace Products search
prod_old = r'<div class="flex items-center gap-2 w-full sm:w-auto">\s*<div class="relative w-full sm:w-72">\s*<i class="bi bi-search absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-400"></i>\s*<input type="text" class="tab-search-input form-control ps-5 w-full text-gray-900 dark:text-gray-100 bg-white dark:bg-gray-800 border-gray-300 dark:border-gray-700" placeholder="Search products..." style="height: 40px; border-radius: 8px;">\s*</div>\s*<button class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 transition tab-search-btn" style="height: 40px;">Search</button>\s*</div>'
content = re.sub(prod_old, get_search_form('products', 'Search products...'), content)

# Replace Customers search
cust_old = r'<div class="flex items-center gap-2 w-full sm:w-auto">\s*<div class="relative w-full sm:w-72">\s*<i class="bi bi-search absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-400"></i>\s*<input type="text" class="tab-search-input form-control ps-5 w-full text-gray-900 dark:text-gray-100 bg-white dark:bg-gray-800 border-gray-300 dark:border-gray-700" placeholder="Search customers..." style="height: 40px; border-radius: 8px;">\s*</div>\s*<button class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 transition tab-search-btn" style="height: 40px;">Search</button>\s*</div>'
content = re.sub(cust_old, get_search_form('customers', 'Search customers...'), content)

# Replace Sales search
sales_old = r'<div class="flex items-center gap-2 w-full sm:w-auto">\s*<div class="relative w-full sm:w-72">\s*<i class="bi bi-search absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-400"></i>\s*<input type="text" class="tab-search-input form-control ps-5 w-full text-gray-900 dark:text-gray-100 bg-white dark:bg-gray-800 border-gray-300 dark:border-gray-700" placeholder="Search orders..." style="height: 40px; border-radius: 8px;">\s*</div>\s*<button class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 transition tab-search-btn" style="height: 40px;">Search</button>\s*</div>'
content = re.sub(sales_old, get_search_form('sales', 'Search orders...'), content)


# 3. Remove Obsolete JS Logic
js_remove_regex = r"        // --- SEARCH BAR LOGIC ---.*?\n        \}\);\n\n"
content = re.sub(js_remove_regex, "", content, flags=re.DOTALL)


with open(file_path, "w", encoding="utf-8") as f:
    f.write(content)
print("Display.blade.php updated successfully")
