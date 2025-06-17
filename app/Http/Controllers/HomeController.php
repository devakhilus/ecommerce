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
    $limit = $request->input('limit', 6);
    $offset = $request->input('offset', 0);
    $search = $request->input('search', '');

    $products = Product::with('category')
        ->when($search, function ($query, $search) {
            $query->where('name', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%")
                  ->orWhereHas('category', function ($q) use ($search) {
                      $q->where('name', 'like', "%{$search}%");
                  });
        })
        ->orderBy('created_at', 'desc')
        ->offset($offset)
        ->limit($limit)
        ->get();

    return response()->json($products);
}    }
