<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\ProductVariant;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function index()
    {
        $products = Product::withCount('variants')->latest()->paginate(10);
        return view('admin.products.index', compact('products'));
    }

    public function create()
    {
        return view('admin.products.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string',
            'price' => 'required|numeric',
            'category' => 'required|string',
            'description' => 'required|string',
            'image' => 'required|image|mimes:jpg,jpeg,png,webp|max:2048',
            'variants.*.color' => 'required|string',
            'variants.*.image' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
        ]);

        // Upload gambar produk
        $imageName = time() . '_' . $request->image->getClientOriginalName();
        $request->image->move(public_path('images/products'), $imageName);

        $product = Product::create([
            'name' => $request->name,
            'price' => $request->price,
            'category' => $request->category,
            'description' => $request->description,
            'image' => $imageName,
            'is_featured' => $request->has('is_featured'),
        ]);

        // Simpan Variants
        if ($request->has('variants')) {
            foreach ($request->input('variants') as $index => $variantData) {
                $variantImage = null;

                if ($request->hasFile("variants.$index.image")) {
                    $file = $request->file("variants.$index.image");
                    $variantImage = time() . '_' . $file->getClientOriginalName();
                    $file->move(public_path('images/variants'), $variantImage);
                }

                ProductVariant::create([
                    'product_id' => $product->id,
                    'color' => $variantData['color'],
                    'image' => $variantImage,
                ]);
            }
        }

        return redirect()->route('admin.products.index')->with('success', 'Produk berhasil ditambahkan.');
    }

    public function edit(Product $product)
    {
        $product->load('variants');
        return view('admin.products.edit', compact('product'));
    }

    public function update(Request $request, Product $product)
    {
        $request->validate([
            'name' => 'required|string',
            'price' => 'required|numeric',
            'category' => 'required|string',
            'description' => 'required|string',
            'image' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
        ]);

        $data = $request->only(['name', 'price', 'category', 'description']);
        $data['is_featured'] = $request->has('is_featured');

        if ($request->hasFile('image')) {
            $imageName = time() . '_' . $request->image->getClientOriginalName();
            $request->image->move(public_path('images/products'), $imageName);
            $data['image'] = $imageName;
        }

        $product->update($data);

        return redirect()->route('admin.products.index')->with('success', 'Produk berhasil diperbarui.');
    }

    public function destroy(Product $product)
    {
        $product->delete();
        return redirect()->route('admin.products.index')->with('success', 'Produk berhasil dihapus.');
    }

    public function toggleFeatured(Product $product)
    {
        $product->is_featured = !$product->is_featured;
        $product->save();

        return redirect()->back()->with('success', 'Status unggulan berhasil diperbarui.');
    }
}
