<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;

class HomeController extends Controller
{
    // Show latest 6 products to welcome view
    public function welcome()
    {
        $products = Product::latest()->take(6)->get();

        $products->transform(function ($product) {
            $product->image_url = $product->picture
                ? asset('images/products/' . $product->picture)
                : 'https://via.placeholder.com/300x200';
            return $product;
        });

        return view('welcome', compact('products'));
    }

    // API endpoint for "Load More"
    public function apiProducts(Request $request)
    {
        $limit  = (int) $request->input('limit', 6);
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

        $products = $query->skip($offset)->take($limit)->get();

        $products->transform(function ($product) {
            $product->image_url = $product->picture
                ? asset('images/products/' . $product->picture)
                : 'https://via.placeholder.com/300x200';
            return $product;
        });

        return response()->json($products);
    }
}
