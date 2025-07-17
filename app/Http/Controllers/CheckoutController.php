<?php

namespace App\Http\Controllers;

use App\Models\CartItem;
use App\Models\Order;
use App\Models\ProductVariant;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CheckoutController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'item_ids'        => 'required|array',
            'item_ids.*'      => 'exists:cart_items,id',
            'payment_method'  => 'required|in:bank,e-wallet',
            'bank_name'       => 'nullable|required_if:payment_method,bank',
            'wallet_type'     => 'nullable|required_if:payment_method,e-wallet',
            'payment_proof'   => 'required|image|max:2048',
            'address'         => 'required|string|max:255',
        ]);

        $user = Auth::user();
        $items = CartItem::with(['product', 'variant'])
                    ->whereIn('id', $request->item_ids)
                    ->where('user_id', $user->id)
                    ->get();

        if ($items->isEmpty()) {
            return back()->with('error', 'Item tidak ditemukan.');
        }

        // Simpan bukti pembayaran
        $proofPath = $request->file('payment_proof')->store('payment_proofs', 'public');

        foreach ($items as $item) {
            $variant = $item->variant;

            // Validasi ulang stok
            if ($variant->stock < $item->jumlah) {
                return back()->with('error', "Stok tidak mencukupi untuk varian {$variant->color}.");
            }

            // Buat Order
            Order::create([
                'user_id'        => $user->id,
                'product_id'     => $item->product_id,
                'variant_id'     => $variant->id,
                'jumlah'         => $item->jumlah,
                'warna'          => $item->warna,
                'ukuran'         => $item->ukuran,
                'total_harga'    => $item->jumlah * $item->product->price,
                'status'         => 'Pembayaran Selesai',
                'payment_method' => $request->payment_method,
                'bank_name'      => $request->bank_name,
                'wallet_type'    => $request->wallet_type,
                'payment_proof'  => $proofPath,
                'user_notified'  => false,
                'address'        => $request->address,
            ]);

            // Kurangi stok
            $variant->decrement('stock', $item->jumlah);

            // Hapus item dari keranjang
            $item->delete();
        }

        return redirect()->route('orders.index')->with('success', 'Pesanan berhasil dibuat. Menunggu konfirmasi admin.');
    }
}
