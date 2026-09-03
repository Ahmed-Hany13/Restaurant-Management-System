<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreCategoryRequest;
use App\Models\MenuCategory;
use App\Models\MenuSection;
use Illuminate\Http\Request;

class MenuCategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $categories = MenuCategory::with('menuSection')->paginate(10);
        return view('admin.categories.view-categories',compact('categories'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $sections = MenuSection::all();
        return view('admin.categories.create-category',compact('sections'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreCategoryRequest $request)
    {
        MenuCategory::create($request->validated());
        return redirect()->route('category.index')->with('success', 'Category created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $category = MenuCategory::with('menuSection')->findOrFail($id);
        return view('admin.categories.show-category', compact('category'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $category = MenuCategory::findOrFail($id);
        $sections = MenuSection::all();
        return view('admin.categories.edit-category', compact('category', 'sections'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, MenuCategory $category)
    {

        $validatedData = $request->validate([
            'name' => 'required|string|max:255' . $category->id,
            'description' => 'nullable|string',
            'display_order' => 'required|integer|min:1',
            'menu_section_id' => 'required|exists:menu_sections,id',
            'status' => 'required|in:active,inactive',
        ]);

        $category->update($validatedData);

        return redirect()->route('category.index')->with('success', 'Category updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(MenuCategory $category)
    {
        if ($category->menuSubcategories()->exists()) {
            return redirect()->route('category.index')->with('error', 'Cannot delete section because it contains subcategories.');
        }
        $category->delete();
        return redirect()->route('category.index')->with('success', 'Category deleted successfully.');
    }


    public function getBySection(Request $request)
    {
        $sectionId = $request->query('section_id');

        if (!$sectionId) {
            return response()->json([]);
        }

        $categories = MenuCategory::where('menu_section_id', $sectionId)
            ->where('status', 'active')
            ->select('id', 'name')
            ->get();

        return response()->json($categories);
    }
}
