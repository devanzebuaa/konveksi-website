<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\ProductVariant;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class ProductVariantController extends Controller
{
    public function index(Product $product)
    {
        return view('admin.variants.index', [
            'product' => $product,
            'variants' => $product->variants
        ]);
    }

    public function create(Product $product)
    {
        return view('admin.variants.create', compact('product'));
    }

    public function store(Request $request, Product $product)
    {
        $validated = $request->validate([
            'color' => 'required|string|max:50',
            'image' => 'nullable|image|max:2048',
            'stock' => 'required|integer|min:0',
        ]);

        // dd($validated);
        $imagePath = null;
        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('variants', 'public');
        }

        $product->variants()->create([
            'color' => $validated['color'],
            'image' => $imagePath,
            'stock' => (int)$validated['stock'],
        ]);

        return redirect()->route('admin.variants.index', $product->id)
                         ->with('success', 'Varian berhasil ditambahkan');
    }

    public function edit(Product $product, ProductVariant $variant)
    {
        return view('admin.variants.edit', compact('product', 'variant'));
    }

    public function update(Request $request, Product $product, ProductVariant $variant)
    {
        $validated = $request->validate([
            'color' => 'required|string|max:50',
            'image' => 'nullable|image|max:2048',
            'stock' => 'required|integer|min:0',
        ]);

        if ($request->hasFile('image')) {
            if ($variant->image && Storage::disk('public')->exists($variant->image)) {
                Storage::disk('public')->delete($variant->image);
            }

            $variant->image = $request->file('image')->store('variants', 'public');
        }

        $variant->color = $validated['color'];
        $variant->stock = $validated['stock'];
        $variant->save();

        return redirect()->route('admin.variants.index', $product->id)
                         ->with('success', 'Varian berhasil diupdate');
    }

    public function destroy(Product $product, ProductVariant $variant)
    {
        if ($variant->image && Storage::disk('public')->exists($variant->image)) {
            Storage::disk('public')->delete($variant->image);
        }

        $variant->delete();

        return back()->with('success', 'Varian berhasil dihapus');
    }
}
