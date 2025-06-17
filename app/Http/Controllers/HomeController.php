<?php


namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;

class HomeController extends Controller
{
    public function welcome()
    {
        // Load latest 6 products from the database
        $products = Product::latest()->take(6)->get();

        // Pass them to the welcome view
        return view('welcome', compact('products'));
    }
    public function apiProducts(Request $request)
    {
        $limit = (int) $request->input('limit', 6);
        $offset = (int) $request->input('offset', 0);
        $search = $request->input('search');

        $query = Product::with('category')->latest();

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhereHas('category', function ($q2) use ($search) {
                        $q2->where('name', 'like', "%{$search}%");
                    });
            });
        }

        return response()->json($query->skip($offset)->take($limit)->get());
    }
}
