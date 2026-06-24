<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    public function index()
    {
        $items = Order::all();
        return view('crud.orders.index', compact('items'));
    }

    public function create()
    {
        return view('crud.orders.create');
    }

    public function store(Request $request)
    {
        Order::create($request->all());
        return redirect()->route('dashboard', ['tab' => 'sales'])->with('success', 'Order created successfully.');
    }

    public function show(Order $order)
    {
        return view('crud.orders.show', ['item' => $order]);
    }

    public function edit(Order $order)
    {
        return view('crud.orders.edit', ['item' => $order]);
    }

    public function update(Request $request, Order $order)
    {
        $order->update($request->all());
        return redirect()->route('dashboard', ['tab' => 'sales'])->with('success', 'Order updated successfully.');
    }

    public function destroy(Order $order)
    {
        $order->delete();
        return redirect()->route('dashboard', ['tab' => 'sales'])->with('success', 'Order deleted successfully.');
    }
}
