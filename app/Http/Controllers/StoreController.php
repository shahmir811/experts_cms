<?php

namespace App\Http\Controllers;

use App\Models\Store;
use App\Models\Customer;
use Illuminate\Http\Request;

class StoreController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $search = $request->input('search');
        
        $stores = Store::when($search, function ($query) use ($search) {
                $query->where('name', 'like', "%{$search}%");
            })
            ->orderBy('name')
            ->paginate(10); // 10 roles per page
        
        return view('pages.stores.index', compact('stores'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $customers = Customer::all();
        return view('pages.stores.create', compact('customers'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'wallmart_key' => 'required|string|max:255',
            'owner_id' => 'required|exists:customers,id',
        ], [
            'name.required' => 'Name is required',
            'wallmart_key.required' => 'Wallmart Key is required',
            'owner_id.required' => 'Owner is required',
        ]);

        Store::create($request->all());

        return redirect()->route('stores.index')->with('success', 'Store created successfully');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $slug)
    {
        $store = Store::where('slug', $slug)->first();
        $products = $store->products()->paginate(10); // Paginate with 10 items per page
        
        return view('pages.stores.show', compact('store', 'products'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $slug)
    {
        $customers = Customer::all();
        $store = Store::where('slug', $slug)->first();
        return view('pages.stores.edit', compact('store', 'customers'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $slug)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'wallmart_key' => 'required|string|max:255',
            'owner_id' => 'required|exists:customers,id',
        ], [
            'name.required' => 'Name is required',
            'wallmart_key.required' => 'Wallmart Key is required',
            'owner_id.required' => 'Owner is required',
        ]);

        $store = Store::where('slug', $slug)->first();
        $store->update($request->all());

        return redirect()->route('stores.index')->with('success', 'Store updated successfully');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
