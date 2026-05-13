<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Requests\CreateStaffRequest;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Support\Facades\Hash;

class CreateStaffController extends Controller
{
    public function store(CreateStaffRequest $request)
    {

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => $request->role,
        ]);

        event(new Registered($user));

        return redirect()->route('dashboard')->with('success', 'Operation completed successfully!');

        // return redirect()->route('dashboard');
    }
}
