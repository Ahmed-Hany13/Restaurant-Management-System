<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreTableRequest;
use App\Models\MenuItem;
use App\Models\Table;
use Illuminate\Http\Request;
use SimpleSoftwareIO\QrCode\Facades\QrCode;

class TableController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $tables = Table::all();
        return view('admin.tables.view-tables', compact('tables'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.tables.create-table');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreTableRequest $request)
    {
        $validated = $request->validated();
        $validated['unique_token'] = bin2hex(random_bytes(16));


        Table::create($validated);

        return redirect()->route('table.index')->with('success', 'Table created successfully!');
    }

    /**
     * Display the specified resource.
     */
    public function show(int $id)
    {

        $table = Table::findOrFail($id);
        if (empty($table->unique_token)){
            $table->unique_token = bin2hex(random_bytes(16));
            $table->save();
        }

        if(!$table){
            return redirect()->back()->with('error','Table not found');
        }

        $menuItems = MenuItem::paginate(10);

        return view('admin.tables.show-table',compact('table','menuItems'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $table = Table::findOrFail($id);
        return view('admin.tables.edit-table', compact('table'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $validated = $request->validate([
            'table_number' => 'required|string|unique:tables,table_number,' . $id . '|regex:/^[a-zA-Z0-9\-]+$/',
            'type' => 'required|in:private,public',
            'min_capacity' => 'required|integer|min:1',
            'max_capacity' => 'required|integer|gte:min_capacity',
            'location' => 'nullable|string|max:100',
            'status' => 'required|in:available,occupied,reserved,maintenance',
            'notes' => 'nullable|string|max:1000',
        ]);
        $table = Table::findOrFail($id);

        $table->update($validated);
        return redirect()->route('table.index')->with('success', 'Table updated successfully!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $table = Table::findOrFail($id);
        $table->delete();
        return redirect()->route('table.index')->with('success', 'Table deleted successfully!');
    }

    public function generateQrCodes()
    {
        $tables = Table::where('status', 'available')->get();
        $qrCodes = [];

        foreach ($tables as $table) {
            if (empty($table->unique_token)){
                $table->unique_token = bin2hex(random_bytes(16));
                $table->save();
            }

            $url = url("/table/{$table->id}/{$table->unique_token}");
            $qrCode = QrCode::format('png')->size(300)->generate($url);

            $qrCodes[] = [
                'table_id' => $table->id,
                'table_name' => $table->name,
                'url' => $url,
                'image' => base64_encode($qrCode),
            ];

            return response()->json($qrCodes);
        }
    }
}
