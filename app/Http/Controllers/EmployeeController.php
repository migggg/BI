<?php

namespace App\Http\Controllers;

use App\Models\Employee;
use Illuminate\Http\Request;

class EmployeeController extends Controller
{
    public function index()
    {
        $items = Employee::all();
        return view('crud.employees.index', compact('items'));
    }

    public function create()
    {
        return view('crud.employees.create');
    }

    public function store(Request $request)
    {
        Employee::create($request->all());
        return redirect()->route('dashboard', ['tab' => 'employees'])->with('success', 'Employee created successfully.');
    }

    public function show(Employee $employee)
    {
        return view('crud.employees.show', ['item' => $employee]);
    }


    public function edit(Employee $employee)
    {
        return view('crud.employees.edit', ['item' => $employee]);
    }

    public function update(Request $request, Employee $employee)
    {
        $employee->update($request->all());
        return redirect()->route('dashboard', ['tab' => 'employees'])->with('success', 'Employee updated successfully.');
    }

    public function destroy(Employee $employee)
    {
        $employee->delete();
        return redirect()->route('dashboard', ['tab' => 'employees'])->with('success', 'Employee deleted successfully.');
    }
}
