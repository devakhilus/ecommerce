<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;

class ProductController extends Controller
{

    public function index(Request $request)
    {
        $sortField = $request->get('sort', 'id');
        $sortDirection = $request->get('direction', 'asc');

        $validSorts = ['id', 'name', 'price', 'stock', 'category_id', 'created_at'];
        if (!in_array($sortField, $validSorts)) {
            $sortField = 'id';
        }

        $products = Product::with('category')
            ->orderBy($sortField, $sortDirection)
            ->get();

        return view('admin.product.index', compact('products', 'sortField', 'sortDirection'));
    }


    public function create()
    {
        $categories = Category::all();
        return view('admin.product.create', compact('categories'));
    }


    public function store(Request $request)
    {
        $request->validate([
            'name'        => 'required|string|max:255',
            'price'       => 'required|numeric',
            'stock'       => 'required|integer|min:0',
            'category_id' => 'required|exists:categories,id',
            'picture'     => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
        ]);

        $data = $request->only(['name', 'price', 'stock', 'category_id']);

        if ($request->hasFile('picture')) {
            $file = $request->file('picture');
            $filename = time() . '_' . $file->getClientOriginalName();
            $base64 = base64_encode(file_get_contents($file));

            $githubResponse = Http::withToken(env('GITHUB_TOKEN'))->put(
                "https://api.github.com/repos/" . env('GITHUB_REPO') . "/contents/" . env('GITHUB_PATH') . "/$filename",
                [
                    'message' => "Add product image: $filename",
                    'content' => $base64,
                    'branch'  => env('GITHUB_BRANCH'),
                ]
            );

            if ($githubResponse->failed()) {
                \Log::error('GitHub upload failed', [
                    'status' => $githubResponse->status(),
                    'body' => $githubResponse->body(),
                ]);
                return back()->withErrors(['picture' => 'Failed to upload image to GitHub.']);
            }

            $data['picture'] = $filename;
        }

        Product::create($data);

        return redirect()->route('products.index')->with('success', 'Product created successfully.');
    }

    // Show form to edit a product
    public function edit(Product $product)
    {
        $categories = Category::all();
        return view('admin.product.edit', compact('product', 'categories'));
    }

    // Update product (image upload to GitHub)
    public function update(Request $request, Product $product)
    {
        $request->validate([
            'name'        => 'required|string|max:255',
            'price'       => 'required|numeric',
            'stock'       => 'required|integer|min:0',
            'category_id' => 'required|exists:categories,id',
            'picture'     => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
        ]);

        $data = $request->only(['name', 'price', 'stock', 'category_id']);

        if ($request->hasFile('picture')) {
            $file = $request->file('picture');
            $filename = time() . '_' . $file->getClientOriginalName();
            $base64 = base64_encode(file_get_contents($file));

            $githubPath = env('GITHUB_PATH') . '/' . $filename;
            $apiUrl = "https://api.github.com/repos/" . env('GITHUB_REPO') . "/contents/" . $githubPath;

            // Step 1: Check if new image already exists (to get sha if overwrite needed)
            $sha = null;
            $existing = Http::withToken(env('GITHUB_TOKEN'))->get($apiUrl, [
                'ref' => env('GITHUB_BRANCH'),
            ]);
            if ($existing->ok()) {
                $sha = $existing->json('sha');
            }

            // Step 2: Upload new image to GitHub
            $uploadPayload = [
                'message' => "Update product image: $filename",
                'content' => $base64,
                'branch'  => env('GITHUB_BRANCH'),
            ];
            if ($sha) {
                $uploadPayload['sha'] = $sha;
            }

            $githubResponse = Http::withToken(env('GITHUB_TOKEN'))->put($apiUrl, $uploadPayload);

            if ($githubResponse->failed()) {
                \Log::error('GitHub upload failed', [
                    'status' => $githubResponse->status(),
                    'body' => $githubResponse->body(),
                ]);
                return back()->withErrors(['picture' => 'Failed to upload image to GitHub.']);
            }

            // Step 3: Delete old image from GitHub (if it exists)
            if ($product->picture) {
                $oldFile = $product->picture;
                $oldPath = env('GITHUB_PATH') . '/' . $oldFile;
                $oldApiUrl = "https://api.github.com/repos/" . env('GITHUB_REPO') . "/contents/" . $oldPath;

                $oldResponse = Http::withToken(env('GITHUB_TOKEN'))->get($oldApiUrl, [
                    'ref' => env('GITHUB_BRANCH'),
                ]);

                if ($oldResponse->ok()) {
                    $oldSha = $oldResponse->json('sha');
                    Http::withToken(env('GITHUB_TOKEN'))->delete($oldApiUrl, [
                        'message' => "Delete old image: $oldFile",
                        'branch' => env('GITHUB_BRANCH'),
                        'sha'    => $oldSha,
                    ]);
                }
            }

            $data['picture'] = $filename;
        }

        $product->update($data);

        return redirect()->route('products.index')->with('success', 'Product updated successfully.');
    }



    // Delete product
    public function destroy(Product $product)
    {
        $product->delete();
        return redirect()->route('products.index')->with('success', 'Product deleted successfully.');
    }
}
