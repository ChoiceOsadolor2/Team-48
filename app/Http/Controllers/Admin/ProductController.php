<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class ProductController extends Controller
{
    // List all products
    public function index()
    {
        $products = Product::with('category')->orderBy('created_at', 'desc')->get();

        return view('admin.products.index', compact('products'));
    }

    // Show form to create a product
    public function create()
    {
        $categories = Category::orderBy('name')->get();

        return view('admin.products.create', compact('categories'));
    }

    // Store new product in DB
    public function store(Request $request)
    {
        $data = $request->validate([
            'category_id' => ['required', 'exists:categories,id'],
            'name'        => ['required', 'string', 'max:255'],
            'description' => ['required', 'string'],
            'price'       => ['required', 'numeric', 'min:0'],
            'stock'       => ['required', 'integer', 'min:0'],
            'platform'    => ['nullable', 'string', 'max:255'],
            'image_url'   => ['nullable', 'string', 'max:2048'], // remote URL allowed
        ]);

        $data['slug'] = Str::slug($data['name']);

        Product::create($data);

        return redirect()->route('admin.products.index')
            ->with('status', 'Product created successfully.');
    }

    // Show form to edit a product
    public function edit(Product $product)
    {
        $categories = Category::orderBy('name')->get();

        return view('admin.products.edit', compact('product', 'categories'));
    }

    // Update existing product
    public function update(Request $request, Product $product)
    {
        $data = $request->validate([
            'category_id' => ['required', 'exists:categories,id'],
            'name'        => ['required', 'string', 'max:255'],
            'description' => ['required', 'string'],
            'price'       => ['required', 'numeric', 'min:0'],
            'stock'       => ['required', 'integer', 'min:0'],
            'platform'    => ['nullable', 'string', 'max:255'],
            'image_url'   => ['nullable', 'string', 'max:2048'],
        ]);

        $data['slug'] = Str::slug($data['name']);

        $product->update($data);

        return redirect()->route('admin.products.index')
            ->with('status', 'Product updated successfully.');
    }

    // Delete a product
    public function destroy(Product $product)
    {
        $product->delete();

        return redirect()->route('admin.products.index')
            ->with('status', 'Product deleted successfully.');
    }
}
