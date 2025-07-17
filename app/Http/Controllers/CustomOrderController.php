<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use App\Models\CustomOrder;

class CustomOrderController extends Controller
{
    // Formulir untuk user buat custom order
    public function create()
    {
        return view('custom-order.form');
    }

    // Simpan data pesanan custom dari user
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'product_type' => 'required',
            'size_detail' => 'nullable|string',
            'description' => 'nullable|string',
            'design_file' => 'nullable|image|max:2048',
            'quantity' => 'required|integer|min:1',
            'payment_method' => 'required',
            'contact' => 'required',
        ]);

        // Upload file jika ada
        $filePath = $request->file('design_file')?->store('custom-orders', 'public');

        CustomOrder::create([
            'name' => $request->name,
            'product_type' => $request->product_type,
            'size_detail' => $request->size_detail,
            'description' => $request->description,
            'design_file' => $filePath,
            'quantity' => $request->quantity,
            'payment_method' => $request->payment_method,
            'contact' => $request->contact,
        ]);

        return redirect()->route('home.index')->with('success', 'Pesanan custom berhasil dikirim. Kami akan segera menghubungi Anda.');
    }

    // Menampilkan semua custom order untuk admin
    public function adminIndex()
    {
        $orders = CustomOrder::latest()->paginate(10);
        return view('admin.custom-orders.index', compact('orders'));
    }

    // Hapus custom order (admin)
    public function destroy(CustomOrder $order)
    {
        if ($order->design_file && Storage::disk('public')->exists($order->design_file)) {
            Storage::disk('public')->delete($order->design_file);
        }

        $order->delete();

        return redirect()->route('admin.custom-orders.index')->with('success', 'Pesanan berhasil dihapus.');
    }
}
