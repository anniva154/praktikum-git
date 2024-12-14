<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Str;
use App\Models\Product;
use App\Models\Category;

class ProductController extends Controller
{
    public function index()
    {
        $products = Product::latest()->paginate(10);
        return view('backend.product.index', compact('products'));
    }

    public function create()
    {
        
        $categories = Category::where('is_parent', 1)->get();
        return view('backend.product.create', compact('categories'));
    }

    public function store(Request $request)
    {
        // Validate incoming request data
        $request->validate([
            'title' => 'required|string|max:255',
            'summary' => 'required|string',
            'description' => 'nullable|string',
            'photo' => 'required|string',
            'stock' => 'required|integer|min:1',
            'cat_id' => 'required|exists:categories,id',
            'child_cat_id' => 'nullable|exists:categories,id',
            'condition' => 'required|in:default,new,hot',
            'status' => 'required|in:active,inactive',
            'price' => 'required|numeric|min:0',
            'discount' => 'nullable|numeric|min:0|max:100',
            'is_featured' => 'sometimes|boolean',
        ]);

        // Prepare data for insertion
        $data = $request->all();

        // Convert price from Rupiah format to numeric
        $data['price'] = $this->convertToNumber($request->price);

        // Generate unique slug for the product
        $data['slug'] = $this->generateUniqueSlug($request->title);


        // Create product record in the database
        Product::create($data);

        // Redirect with success message
        return redirect()->route('product.index')->with('success', 'Product added successfully!');
    }

    public function edit($id)
    {
        // Fetch product and related data for editing
        $product = Product::findOrFail($id);
        $categories = Category::where('is_parent', 1)->get();

        return view('backend.product.edit', compact('product', 'categories'));
    }

    public function update(Request $request, $id)
    {
        // Find the product by ID
        $product = Product::findOrFail($id);

        // Validate incoming request data
        $request->validate([
            'title' => 'required|string|max:255',
            'summary' => 'required|string',
            'description' => 'nullable|string',
            'photo' => 'required|string',
            'stock' => 'required|integer|min:1',
            'cat_id' => 'required|exists:categories,id',
            'child_cat_id' => 'nullable|exists:categories,id',
            'condition' => 'required|in:default,new,hot',
            'status' => 'required|in:active,inactive',
            'price' => 'required|numeric|min:0',
            'discount' => 'nullable|numeric|min:0|max:100',
            'is_featured' => 'sometimes|boolean',
        ]);

        // Prepare data for updating the product
        $data = $request->all();

        // Convert price from Rupiah format to numeric
        $data['price'] = $this->convertToNumber($request->price);


        // Update product record in the database
        $product->update($data);

        // Redirect with success message
        return redirect()->route('product.index')->with('success', 'Product updated successfully!');
    }

    public function destroy($id)
    {
        // Find and delete the product
        $product = Product::findOrFail($id);
        $status = $product->delete();

        // Set flash message based on delete status
        if ($status) {
            request()->session()->flash('success', 'Product deleted');
        } else {
            request()->session()->flash('error', 'Error while deleting product');
        }

        // Redirect to product list
        return redirect()->route('product.index');
    }

    // Helper method to convert price from Rupiah to numeric
    private function convertToNumber($price)
    {
        $price = str_replace(['Rp', '.'], '', $price);
        return floatval($price);
    }

    // Helper method to generate a unique slug for the product
    private function generateUniqueSlug($title)
    {
        $slug = Str::slug($title);
        $count = Product::where('slug', $slug)->count();
        return $count ? $slug . '-' . time() : $slug;
    }
}
