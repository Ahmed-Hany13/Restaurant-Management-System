<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\MenuSection;
use Illuminate\Http\Request;

class MenuController extends Controller
{
    public function index()
    {
        $sections = MenuSection::all();
        return view('view-menu', compact('sections'));
    }
}
