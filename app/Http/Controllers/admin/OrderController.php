<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    /**
     * Tampilkan semua order untuk admin
     */
    public function index()
    {
        $orders = Order::with(['product', 'user'])->latest()->get();
        return view('admin.orders.index', compact('orders'));
    }

    /**
     * Tampilkan detail satu order
     */
    public function show(Order $order)
    {
        return view('admin.orders.show', compact('order'));
    }

    /**
     * Update status order dan tandai user belum diberi notifikasi
     */
    public function updateStatus(Request $request, Order $order)
    {
        $request->validate([
            'status' => 'required|in:Menunggu Pembayaran,Sudah Dibayar,Diproses,Dikirim,Selesai,Dibatalkan'
        ]);

        $order->update([
            'status' => $request->status,
            'user_notified' => false, // agar muncul notif di dashboard user
        ]);

        return back()->with('success', "Status order #{$order->id} berhasil diperbarui menjadi {$request->status}.");
    }

    /**
     * Hapus order dari database (admin only)
     */
    public function destroy(Order $order)
    {
        $order->delete();

        return redirect()->route('admin.orders.index')
            ->with('success', "Order #{$order->id} berhasil dihapus.");
    }
}
