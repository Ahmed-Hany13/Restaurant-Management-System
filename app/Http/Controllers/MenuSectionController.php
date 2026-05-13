<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreSectionRequest;
use App\Http\Requests\UpdateSectionRequest;
use App\Models\MenuSection;
use Illuminate\Http\Request;

class MenuSectionController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $sections = MenuSection::all();
        return view('admin.sections.view-sections',compact('sections'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.sections.create-section');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreSectionRequest $request)
    {
        MenuSection::create($request->validated());
        return redirect()->route('section.index')->with('success', 'Section created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $section = MenuSection::findOrFail($id);
        return view('admin.sections.show-section',compact('section'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $section = MenuSection::findOrFail($id);
        return view('admin.sections.edit-section',compact('section'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, MenuSection $section)
    {
        $request->validate([
            'name' => 'required|unique:menu_sections,name,' . $section->id,
            'description' => 'nullable|string',
            'display_order' => 'required|integer|min:1',
            'status' => 'required',
        ]);
        $data = $request->only('name', 'description', 'display_order', 'status');
        $section->update($data);
        return redirect()->route('section.index')->with('success','Section updated successfully');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(MenuSection $section)
    {
        if ($section->menuCategories()->exists()) {
            return redirect()->route('section.index')->with('error', 'Cannot delete section because it contains categories.');
        }
        $section->delete();
        return redirect()->route('section.index')->with('success','Section deleted successfully');
    }

    public function getActive()
    {
        $sections = MenuSection::where('status', 'active')
            ->select('id', 'name', 'display_order')
            ->orderBy('display_order')
            ->get();

        return response()->json($sections);
    }
}
