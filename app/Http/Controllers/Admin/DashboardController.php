<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\Order;
use App\Models\User;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        return view('admin.dashboard');
    }

    public function stats()
    {
        $productCount = Product::count();
        $orderCount = \App\Models\Order::count();
        $userCount = User::count();
        $revenue = \App\Models\Order::sum('total');

        return response()->json([
            'products' => $productCount,
            'orders' => $orderCount,
            'users' => $userCount,
            'revenue' => number_format($revenue, 2)
        ]);
    }
}
