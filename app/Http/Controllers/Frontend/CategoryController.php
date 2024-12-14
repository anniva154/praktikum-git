<?php 
namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Category;

class CategoryController extends Controller
{
    public function index()
{
    // Mengambil kategori induk beserta subkategori terkait
    $categories = Category::with('child_categories')->whereNull('parent_id')->get();

    // Pastikan $categories tidak null atau kosong
    if ($categories->isEmpty()) {
        // Tangani jika tidak ada kategori
        // Anda bisa memberi pesan atau melog sesuatu untuk pengecekan lebih lanjut
        return view('frontend.pages.category-all', ['categories' => []]);
    }

    return view('frontend.pages.category-all', compact('categories'));
}


    public function show($slug)
    {
        $category = Category::where('slug', $slug)->firstOrFail();
        return view('frontend.categories.show', compact('category'));
    }
}
