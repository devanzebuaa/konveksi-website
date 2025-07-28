<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\ProductVariant;
use App\Models\ProductSizePrice;
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
            'category' => 'required|string',
            'description' => 'required|string',
            'image' => 'required|image|mimes:jpg,jpeg,png,webp|max:2048',
            'variants.*.color' => 'required|string',
            'variants.*.image' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
            'size_prices' => 'required|array',
            'size_prices.*' => 'array',
            'size_prices.*.*' => 'nullable|numeric|min:0',
        ]);

        // Upload gambar utama produk
        $imageName = time() . '_' . $request->image->getClientOriginalName();
        $request->image->move(public_path('images/products'), $imageName);

        $product = Product::create([
            'name' => $request->name,
            'price' => $request->price ?? 0,
            'category' => $request->category,
            'description' => $request->description,
            'image' => $imageName,
            'is_featured' => $request->has('is_featured'),
        ]);

        // Simpan varian dan gambar varian
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

        // Simpan size_prices untuk semua varian
        if ($request->has('size_prices')) {
            foreach ($request->size_prices as $variantIdx => $sizes) {
                if (is_array($sizes)) {
                    foreach ($sizes as $size => $price) {
                        if ($price !== null && $price !== '') {
                            ProductSizePrice::create([
                                'product_id' => $product->id,
                                'size' => $size,
                                'price' => $price,
                            ]);
                        }
                    }
                }
            }
        }

        return redirect()->route('admin.products.index')->with('success', 'Produk berhasil ditambahkan.');
    }

    public function edit(Product $product)
    {
        $product->load(['variants', 'sizePrices']);
        return view('admin.products.edit', compact('product'));
    }

    public function update(Request $request, Product $product)
    {
        $request->validate([
            'name' => 'required|string',
            'category' => 'required|string',
            'description' => 'required|string',
            'price' => 'nullable|numeric|min:0',
            'image' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
            'size_prices' => 'required|array',
            'size_prices.*' => 'nullable|numeric|min:0',
        ]);

        $data = $request->only(['name', 'category', 'description', 'price']);
        $data['is_featured'] = $request->has('is_featured');

        if ($request->hasFile('image')) {
            $imageName = time() . '_' . $request->image->getClientOriginalName();
            $request->image->move(public_path('images/products'), $imageName);
            $data['image'] = $imageName;
        }

        $product->update($data);

        // Hapus harga lama
        $product->sizePrices()->delete();

        // Simpan ulang harga per ukuran (langsung dari size_prices[SIZE])
        if ($request->has('size_prices')) {
            foreach ($request->size_prices as $size => $price) {
                if ($price !== null && $price !== '') {
                    ProductSizePrice::create([
                        'product_id' => $product->id,
                        'size' => $size,
                        'price' => $price,
                    ]);
                }
            }
        }

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
