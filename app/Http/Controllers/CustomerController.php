<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use Illuminate\Http\Request;

class CustomerController extends Controller
{
    public function index(Request $request)
    {
        $query = Customer::query();
        if ($request->has('search')) {
            $query->where('customerName', 'like', '%' . $request->search . '%');
        }
        $items = $query->get();
        return view('crud.customers.index', compact('items'));
    }

    public function create()
    {
        return view('crud.customers.create');
    }

    public function store(Request $request)
    {
        Customer::create($request->all());
        return redirect()->route('dashboard', ['tab' => 'customers'])->with('success', 'Customer created successfully.');
    }

    public function show(Customer $customer)
    {
        return view('crud.customers.show', ['item' => $customer]);
    }


    public function edit(Customer $customer)
    {
        return view('crud.customers.edit', ['item' => $customer]);
    }

    public function update(Request $request, Customer $customer)
    {
        $customer->update($request->all());
        return redirect()->route('dashboard', ['tab' => 'customers'])->with('success', 'Customer updated successfully.');
    }

    public function destroy(Customer $customer)
    {
        $customer->delete();
        return redirect()->route('dashboard', ['tab' => 'customers'])->with('success', 'Customer deleted successfully.');
    }
}
