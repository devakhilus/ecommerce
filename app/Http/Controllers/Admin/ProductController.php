<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\Category;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    // Show list of products with pagination and sorting
    public function index(Request $request)
{
    $sortField = $request->get('sort', 'id');
    $sortDirection = $request->get('direction', 'asc');

    // Optional: validate sort fields
    $validSorts = ['id', 'name', 'price', 'stock', 'category_id', 'created_at'];
    if (!in_array($sortField, $validSorts)) {
        $sortField = 'id';
    }

    $products = Product::with('category')
        ->orderBy($sortField, $sortDirection)
        ->get(); // fetch all products for client-side DataTables

    return view('admin.product.index', compact('products', 'sortField', 'sortDirection'));
}


    // Show form to create a new product
    public function create()
    {
        $categories = Category::all();
        return view('admin.product.create', compact('categories'));
    }

    // Store new product
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'price' => 'required|numeric',
            'stock' => 'required|integer|min:0',
            'category_id' => 'required|exists:categories,id',
        ]);

        Product::create($request->all());

        return redirect()->route('products.index')->with('success', 'Product created successfully.');
    }

    // Show form to edit a product
    public function edit(Product $product)
    {
        $categories = Category::all();
        return view('admin.product.edit', compact('product', 'categories'));
    }

    // Update product
    public function update(Request $request, Product $product)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'price' => 'required|numeric',
            'stock' => 'required|integer|min:0',
            'category_id' => 'required|exists:categories,id',
        ]);

        $product->update($request->all());

        return redirect()->route('products.index')->with('success', 'Product updated successfully.');
    }

    // Delete product
    public function destroy(Product $product)
    {
        $product->delete();
        return redirect()->route('products.index')->with('success', 'Product deleted successfully.');
    }
}
