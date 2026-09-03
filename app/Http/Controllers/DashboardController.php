<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Requests\ProfileUpdateRequest;
use App\Models\Order;
use App\Models\Table;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;

class DashboardController extends Controller
{
    public function index()
    {
        $orders = Order::with('table')->latest()->paginate(10);
        $tables = Table::all();
        return view('dashboard.dashboard', compact('orders', 'tables'));
    }
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

    public function orders(){
        return view('orders_page');
    }

    public function billing()
    {
        return view('billing_page');
    }

    public function kitchen()
    {
        $pendingOrders = Order::with(['menuItems', 'table'])
            ->where('status', 'pending')
            ->orderBy('created_at')
            ->get()
            ->groupBy('order_number');

        $inPreparationOrders = Order::with(['menuItems', 'table'])
            ->whereIn('status', ['in preparation', 'preparing'])
            ->orderBy('preparation_started_at')
            ->orderBy('created_at')
            ->get()
            ->groupBy('order_number');

        return view('kitchen_view', compact('pendingOrders', 'inPreparationOrders'));
    }

    public function startPreparing(string $order_number)
    {
        Order::where('order_number', $order_number)
            ->where('status', 'pending')
            ->update([
                'status' => 'preparing',
                'preparation_started_at' => now(),
            ]);

        return redirect()->route('kitchen')->with('success', "Order {$order_number} moved to preparation.");
    }

    public function markReady(string $order_number)
    {
        Order::where('order_number', $order_number)
            ->whereIn('status', ['in preparation', 'preparing'])
            ->update(['status' => 'ready']);

        return redirect()->route('kitchen')->with('success', "Order {$order_number} marked ready.");
    }

    public function CreateStaff()
    {
        return view('admin.create-staff');
    }


}

