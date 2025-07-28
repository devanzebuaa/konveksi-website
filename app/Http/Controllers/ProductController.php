<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function index()
    {
        $query = Product::query();

        if (request('search')) {
            $query->where('name', 'like', '%' . request('search') . '%');
        }

        if (request('category')) {
            $query->where('category', request('category'));
        }

        $products = $query->latest()->paginate(9)->withQueryString();

        return view('products.index', [
            'products' => $products,
            'selectedCategory' => request('category'),
        ]);
    }

    public function show(Product $product)
    {
        // Load relasi untuk variants dan sizePrices
        $product->load(['variants', 'sizePrices']);

        return view('products.show', [
            'product' => $product,
            'relatedProducts' => Product::where('category', $product->category)
                                        ->where('id', '!=', $product->id)
                                        ->limit(4)
                                        ->get()
        ]);
    }
}
