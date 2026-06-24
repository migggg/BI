<?php

namespace App\Http\Controllers;

use App\Models\Office;
use Illuminate\Http\Request;

class OfficeController extends Controller
{
    public function index(Request $request)
    {
        $query = Office::query();
        if ($request->has('search')) {
            $query->where('city', 'like', '%' . $request->search . '%');
        }
        $items = $query->get();
        return view('crud.offices.index', compact('items'));
    }

    public function create()
    {
        return view('crud.offices.create');
    }

    public function store(Request $request)
    {
        Office::create($request->all());
        return redirect()->route('dashboard', ['tab' => 'offices'])->with('success', 'Office created successfully.');
    }

    public function show(Office $office)
    {
        return view('crud.offices.show', ['item' => $office]);
    }


    public function edit(Office $office)
    {
        return view('crud.offices.edit', ['item' => $office]);
    }

    public function update(Request $request, Office $office)
    {
        $office->update($request->all());
        return redirect()->route('dashboard', ['tab' => 'offices'])->with('success', 'Office updated successfully.');
    }

    public function destroy(Office $office)
    {
        $office->delete();
        return redirect()->route('dashboard', ['tab' => 'offices'])->with('success', 'Office deleted successfully.');
    }
}
