<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreSubcategoryRequest;
use App\Models\MenuCategory;
use App\Models\MenuSubcategory;
use Illuminate\Http\Request;

class MenuSubcategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $subcategories = MenuSubcategory::with('menuCategory.menuSection', 'menuItems')->get();
        return view('admin.subcategories.view-subcategories', compact('subcategories'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $categories = MenuCategory::with('menuSection')->get();
        return view('admin.subcategories.create-subcategory',compact('categories'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreSubcategoryRequest $request)
    {
        $validatedData = $request->validated();
        MenuSubcategory::create($validatedData);
        return redirect()->route('subcategory.index')->with('success', 'Subcategory created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $subcategory = MenuSubcategory::with('menuCategory.menuSection', 'menuItems')->findOrFail($id);
        return view('admin.subcategories.show-subcategory', compact('subcategory'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $subcategory = MenuSubcategory::findOrFail($id);
        $categories = MenuCategory::with('menuSection')->get();
        return view('admin.subcategories.edit-subcategory', compact('subcategory', 'categories'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $subcategory = MenuSubcategory::findOrFail($id);
        $validatedData = $request->validate([
            'name' => 'required|string|max:255' . $subcategory->id,
            'menu_category_id' => 'required|exists:menu_categories,id',
        ]);

        $subcategory->update($validatedData);
        return redirect()->route('subcategory.index')->with('success', 'Subcategory updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(MenuSubcategory $subcategory)
    {
        if ($subcategory->menuItems()->exists()) {
            return redirect()->route('subcategory.index')->with('error', 'Cannot delete subcategory with associated menu items.');
        }
        $subcategory->delete();
        return redirect()->route('subcategory.index')->with('success', 'Subcategory deleted successfully.');
    }

    /**
     * Get subcategories by category (API endpoint)
     */
    public function getByCategory(Request $request)
    {
        $categoryId = $request->query('category_id');
        
        if (!$categoryId) {
            return response()->json([]);
        }

        $subcategories = MenuSubcategory::where('menu_category_id', $categoryId)
            ->where('status', 'active')
            ->select('id', 'name')
            ->get();

        return response()->json($subcategories);
    }
}
