<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Requests\ProfileUpdateRequest;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Http\RedirectResponse;

class DashboardController extends Controller
{
    public function myAccount()
    {
        $getRecord = User::findOrFail(Auth::user()->id);
        return view('dashboard.my-account', compact('getRecord'));
    }
    public function updateMyAccount(ProfileUpdateRequest $request): RedirectResponse
    {
        $request->user()->fill($request->validated());

        if ($request->user()->isDirty('email')) {
            $request->user()->email_verified_at = null;
        }

        $request->user()->save();

        return Redirect::route('dashboard.my-account')->with('success', 'Profile updated successfully.');
    }

    public function orders()
    {
        return view('orders_page');
    }

    public function billing()
    {
        return view('billing_page');
    }

    public function kitchen()
    {
        return view('kitchen_view');
    }

    public function CreateStaff()
    {
        return view('admin.create-staff');
    }

    public function menuView()
    {
        return view('view-menu');
    }

}

