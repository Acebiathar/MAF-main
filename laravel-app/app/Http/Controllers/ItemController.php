<?php

namespace App\Http\Controllers;

use App\Models\Item; // This line is crucial!
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ItemController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->input('search', '');

        $items = Item::query()
            ->when($search, fn($query) => $query->where('name', 'like', "%{$search}%"))
            ->orderBy('created_at', 'desc')
            ->paginate(10)
            ->withQueryString();

        $pharmacies = DB::table('pharmacies')->where('status', 'approved')->limit(4)->get();

        return view('index', [
            'items' => $items,
            'pharmacies' => $pharmacies,
            'results' => collect([]),
            'query' => $search,
            'alternatives' => collect([]),
            'currentUser' => auth()->user(),
        ]);
    }

    public function create()
    {
        return view('items.create');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'price' => 'required|numeric|min:0',
            'stock_quantity' => 'required|integer|min:0',
        ]);

        Item::create($data);

        flash('success', 'Item created successfully.');
        return redirect()->route('items.index');
    }

    public function show(Item $item)
    {
        return redirect()->route('items.index');
    }

    public function edit(Item $item)
    {
        return view('items.edit', compact('item'));
    }

    public function update(Request $request, Item $item)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'price' => 'required|numeric|min:0',
            'stock_quantity' => 'required|integer|min:0',
        ]);

        $item->update($data);

        flash('success', 'Item updated successfully.');
        return redirect()->route('items.index');
    }

    public function destroy(Item $item)
    {
        $item->delete();

        flash('success', 'Item deleted successfully.');
        return redirect()->route('items.index');
    }
}
