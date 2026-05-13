<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreMenuItemRequest;
use App\Models\MenuItem;
use App\Models\MenuSection;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class MenuItemController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $items = MenuItem::with('menuSubcategory.menuCategory.menuSection')->get();
        return view('admin.items.view-items', compact('items'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $sections = MenuSection::where('status', 'active')->get();
        return view('admin.items.create-item', compact('sections'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreMenuItemRequest $request)
    {
        $validated = $request->validated();
        unset($validated['menu_section_id'], $validated['menu_category_id']);

        if ($request->hasFile('image')) {
            $file = $request->file('image');
            // Ensure the directory exists
            $path = Storage::disk('public')->putFile('menu-items', $file);
            $validated['image'] = $path;
        }
        MenuItem::create($validated);
        return redirect()->route('item.index')->with('success', 'Menu item created successfully!');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $item = MenuItem::with('menuSubcategory.menuCategory.menuSection')->findOrFail($id);
        return view('admin.items.show-item', compact('item'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $item = MenuItem::with('menuSubcategory.menuCategory.menuSection')->findOrFail($id);
        $sections = MenuSection::where('status', 'active')->get();
        return view('admin.items.edit-item', compact('item', 'sections'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, MenuItem $item)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:200|unique:menu_items,name,' . $item->id,
            'menu_section_id' => 'required|exists:menu_sections,id',
            'menu_category_id' => 'required|exists:menu_categories,id',
            'menu_subcategory_id' => 'required|exists:menu_subcategories,id',
            'description' => 'nullable|string|max:500',
            'price' => 'required|numeric|min:0',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'preparation_time' => 'nullable|integer|min:0',
            'status' => 'required|in:active,inactive',
        ]);
        unset($validated['menu_section_id'], $validated['menu_category_id']);

        if ($request->hasFile('image')) {
            $file = $request->file('image');
            $path = Storage::disk('public')->putFile('menu-items', $file);
            $validated['image'] = $path;
        }

        $item->update($validated);
        return redirect()->route('item.index')->with('success', 'Menu item updated successfully!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(MenuItem $item)
    {
        if ($item->image) {
            Storage::disk('public')->delete($item->image);
        }
        $item->delete();
        return redirect()->route('item.index')->with('success', 'Menu item deleted successfully!');
    }


    public function getBySubcategory(Request $request)
    {
        $subcategoryId = $request->query('subcategory_id');

        if (!$subcategoryId) {
            return response()->json([], 400);
        }

        $items = MenuItem::where('menu_subcategory_id', $subcategoryId)
            ->where('status', 'active')
            ->select('id', 'name', 'description', 'price', 'image', 'preparation_time')
            ->get();

        return response()->json($items);
    }
}
