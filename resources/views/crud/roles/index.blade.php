<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Role Management') }}
        </h2>
    </x-slot>
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 dark:text-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-white">
                    @if(session('success'))
                    <div class="bg-green-50 dark:bg-green-900/30 border border-green-200 dark:border-green-800 rounded-lg p-4 mb-6 flex justify-between items-center text-green-800 dark:text-green-300 font-bold">
                        <span><i class="bi bi-check-circle-fill me-2"></i> {{ session('success') }}</span>
                        <button onclick="this.parentElement.remove()" class="text-green-800 dark:text-green-300 hover:opacity-75" style="background: none; border: none; font-size: 1.25rem; cursor: pointer;">&times;</button>
                    </div>
                    @endif

                    @if(session('error'))
                    <div class="bg-red-50 dark:bg-red-900/30 border border-red-200 dark:border-red-800 rounded-lg p-4 mb-6 flex justify-between items-center text-red-800 dark:text-red-300 font-bold">
                        <span><i class="bi bi-exclamation-triangle-fill me-2"></i> {{ session('error') }}</span>
                        <button onclick="this.parentElement.remove()" class="text-red-800 dark:text-red-300 hover:opacity-75" style="background: none; border: none; font-size: 1.25rem; cursor: pointer;">&times;</button>
                    </div>
                    @endif

                    <div class="flex justify-between items-center mb-6">
                        <h3 class="text-lg font-medium">Role Management</h3>
                        <a href="{{ route('roles.create') }}" class="inline-flex items-center px-3 py-2 bg-emerald-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-emerald-700 focus:bg-emerald-700 active:bg-emerald-900 focus:outline-none focus:ring-2 focus:ring-emerald-500 transition ease-in-out duration-150 shadow">
                            <i class="bi bi-plus-lg" style="-webkit-text-stroke: 1px;" title="Create"></i>
                        </a>
                    </div>
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                            <thead class="bg-gray-50 dark:bg-gray-700">
                                <tr>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Name</th>
                                    <th scope="col" class="px-6 py-3 text-right text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Actions</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700 dark:text-white">
                                @foreach($items as $item)
                                <tr>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-white">{{ $item->name }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                        <div class="flex justify-end gap-2">
                                            <a href="{{ route('roles.edit', $item->id) }}" class="inline-block px-3 py-1 bg-amber-500 text-white rounded shadow hover:bg-amber-600 text-xs text-decoration-none" title="Update">
                                                <i class="bi bi-pencil-square"></i>
                                            </a>
                                            <form action="{{ route('roles.destroy', $item->id) }}" method="POST" class="inline" onsubmit="return confirm('Are you sure you want to delete this?')">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="inline-block px-3 py-1 bg-red-600 text-white rounded shadow hover:bg-red-700 text-xs border-0" title="Delete">
                                                    <i class="bi bi-trash"></i>
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>