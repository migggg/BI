<?php

namespace App\Http\Controllers;

use Spatie\Permission\Models\Role;
use Illuminate\Http\Request;

class RoleController extends Controller
{
    public function index()
    {
        $items = Role::all();
        return view('crud.roles.index', compact('items'));
    }

    public function create()
    {
        return view('crud.roles.create');
    }

    public function store(Request $request)
    {
        Role::create($request->all());
        return redirect()->route('roles.index')->with('success', 'Role created successfully.');
    }

    public function edit(Role $role)
    {
        $item = $role;
        return view('crud.roles.edit', compact('item'));
    }

    public function update(Request $request, Role $role)
    {
        $role->update($request->all());
        return redirect()->route('roles.index')->with('success', 'Role updated successfully.');
    }

    public function destroy(Role $role)
    {
        // Prevent deleting critical system roles
        if (in_array($role->name, ['super_admin', 'admin', 'Super Admin', 'Admin'])) {
            return redirect()->route('roles.index')->with('error', 'You cannot delete critical system roles (Super Admin, Admin).');
        }

        // Prevent deleting a role that is currently assigned to the logged-in user
        if (auth()->user()->roles->contains('id', $role->id)) {
            return redirect()->route('roles.index')->with('error', 'You cannot delete a role that is currently assigned to you.');
        }

        $role->delete();
        return redirect()->route('roles.index')->with('success', 'Role deleted successfully.');
    }
}
