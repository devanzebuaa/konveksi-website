<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\ProductSizePrice;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use App\Services\InvoiceService;

class OrderController extends Controller
{
    public function index()
    {
        $orders = Order::with(['product', 'user'])->latest()->get();
        return view('admin.orders.index', compact('orders'));
    }

    public function show(Order $order)
    {
        $order->load(['product', 'user']);

        // Ambil harga berdasarkan product_id dan ukuran
        $hargaUkuran = optional(
            ProductSizePrice::where('product_id', $order->product_id)
                ->where('size', $order->ukuran)
                ->first()
        )->price;

        if ($hargaUkuran) {
            $order->total_harga = $order->jumlah * $hargaUkuran;
        }

        return view('admin.orders.show', compact('order'));
    }

    public function updateStatus(Request $request, Order $order)
    {
        $request->validate([
            'status' => 'required|in:Menunggu Pembayaran,Sudah Dibayar,Diproses,Dikirim,Selesai,Dibatalkan'
        ]);

        $order->update([
            'status' => $request->status,
            'user_notified' => false,
        ]);

        if ($request->status === 'Diproses') {
            $productId = $order->product_id;
            $ukuran = $order->ukuran;
            $jumlah = $order->jumlah ?? 0;

            // ✅ Ambil harga dari tabel product_size_prices
            $hargaUkuran = optional(
                ProductSizePrice::where('product_id', $productId)
                    ->where('size', $ukuran)
                    ->first()
            )->price;

            if (!$hargaUkuran || $hargaUkuran <= 0) {
                return back()->with('error', "Harga untuk ukuran '{$ukuran}' tidak ditemukan pada produk #{$productId}.");
            }

            $totalHarga = $hargaUkuran * $jumlah;

            $order->update([
                'total_harga' => $totalHarga,
            ]);

            // ✅ Generate invoice jika belum ada
            if (!$order->invoice_path) {
                $invoicePath = (new InvoiceService)->generate($order);
                $order->update(['invoice_path' => $invoicePath]);
            }
        }

        return back()->with('success', "Status order #{$order->id} berhasil diperbarui menjadi {$request->status}.");
    }

    public function destroy(Order $order)
    {
        if ($order->invoice_path && Storage::disk('public')->exists($order->invoice_path)) {
            Storage::disk('public')->delete($order->invoice_path);
        }

        $order->delete();

        return redirect()->route('admin.orders.index')
            ->with('success', "Order #{$order->id} berhasil dihapus.");
    }
}
