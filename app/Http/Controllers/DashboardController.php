<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        if ($user->hasRole('admin')) {
            return view('dashboard.admin');
        } elseif ($user->hasRole('owner')) {
            return view('dashboard.owner');
        } elseif ($user->hasRole('cashier')) {
            return view('dashboard.cashier');
        } elseif ($user->hasRole('inventory_staff')) {
            return view('dashboard.inventory_staff');
        }
    }
}
