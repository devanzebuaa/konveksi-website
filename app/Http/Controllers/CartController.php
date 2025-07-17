<?php

namespace App\Http\Controllers;

use App\Models\CartItem;
use App\Models\ProductVariant;
use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CartController extends Controller
{
    public function store(Request $request)
    {
        $validated = $request->validate([
            'product_id' => 'required|exists:products,id',
            'warna'      => 'required|string',
            'ukuran'     => 'required|string|max:20',
            'jumlah'     => 'required|integer|min:1',
        ]);

        $variant = ProductVariant::where('product_id', $validated['product_id'])
                                 ->where('color', $validated['warna'])
                                 ->first();

        if (!$variant) {
            return back()->with('error', 'Varian tidak ditemukan.');
        }

        if ($variant->stock < $validated['jumlah']) {
            return back()->with('error', 'Stok tidak mencukupi untuk varian ini.');
        }

        CartItem::create([
            'user_id'     => Auth::id(),
            'product_id'  => $validated['product_id'],
            'variant_id'  => $variant->id,
            'warna'       => $validated['warna'],
            'ukuran'      => $validated['ukuran'],
            'jumlah'      => $validated['jumlah'],
        ]);

        return redirect()->route('cart.index')->with('success', 'Produk berhasil disimpan ke keranjang.');
    }

    public function index()
    {
        $items = Auth::check()
            ? Auth::user()->cartItems()->with(['product', 'variant'])->get()
            : collect();

        return view('cart.index', compact('items'));
    }

    public function destroy(CartItem $item)
    {
        if ($item->user_id !== Auth::id()) {
            abort(403, 'Tidak diizinkan menghapus item ini.');
        }

        $item->delete();

        return back()->with('success', 'Item berhasil dihapus dari keranjang.');
    }

    public function checkout(Request $request)
    {
        $validated = $request->validate([
            'item_ids'       => 'required|array',
            'payment_method' => 'required|in:bank,e-wallet',
            'bank_name'      => 'nullable|string',
            'wallet_type'    => 'nullable|string',
            'payment_proof'  => 'required|image|max:2048',
        ]);

        $user = Auth::user();
        $bayarSatuId = $request->input('bayar_satu');
        $itemIds = $bayarSatuId ? [$bayarSatuId] : $validated['item_ids'];

        $items = CartItem::whereIn('id', $itemIds)
                         ->where('user_id', $user->id)
                         ->with(['product', 'variant'])
                         ->get();

        if ($items->isEmpty()) {
            return back()->with('error', 'Tidak ada item yang valid untuk diproses.');
        }

        $paymentProofPath = $request->file('payment_proof')->store('payment_proofs', 'public');

        foreach ($items as $item) {
            if (!$item->variant || !$item->product) continue;

            if ($item->variant->stock < $item->jumlah) {
                return back()->with('error', "Stok tidak cukup untuk varian {$item->variant->color}.");
            }

            $item->variant->decrement('stock', $item->jumlah);

            Order::create([
                'user_id'        => $user->id,
                'product_id'     => $item->product_id,
                'variant_id'     => $item->variant_id,
                'warna'          => $item->warna,
                'ukuran'         => $item->ukuran,
                'jumlah'         => $item->jumlah,
                'total_harga'    => $item->jumlah * $item->product->price,
                'status'         => 'Menunggu Pembayaran',
                'payment_method' => $validated['payment_method'],
                'bank_name'      => $validated['payment_method'] === 'bank' ? $validated['bank_name'] : null,
                'wallet_type'    => $validated['payment_method'] === 'e-wallet' ? $validated['wallet_type'] : null,
                'payment_proof'  => $paymentProofPath,
            ]);

            $item->delete();
        }

        $pesan = $bayarSatuId ? 'Pembayaran berhasil untuk 1 item!' : 'Pembayaran berhasil! Semua pesanan sedang diproses.';
        return redirect()->route('orders.index')->with('success', $pesan);
    }

    public function checkoutItem(Request $request, CartItem $item)
    {
        $validated = $request->validate([
            'payment_method' => 'required|in:bank,e-wallet',
            'bank_name'      => 'nullable|string',
            'wallet_type'    => 'nullable|string',
            'payment_proof'  => 'required|image|max:2048',
        ]);

        $user = Auth::user();

        if ($item->user_id !== $user->id) {
            abort(403, 'Item tidak ditemukan.');
        }

        if (!$item->variant || !$item->product) {
            return back()->with('error', 'Data produk tidak lengkap.');
        }

        if ($item->variant->stock < $item->jumlah) {
            return back()->with('error', 'Stok tidak cukup.');
        }

        $item->variant->decrement('stock', $item->jumlah);

        $paymentProofPath = $request->file('payment_proof')->store('payment_proofs', 'public');

        Order::create([
            'user_id'        => $user->id,
            'product_id'     => $item->product_id,
            'variant_id'     => $item->variant_id,
            'warna'          => $item->warna,
            'ukuran'         => $item->ukuran,
            'jumlah'         => $item->jumlah,
            'total_harga'    => $item->jumlah * $item->product->price,
            'status'         => 'Menunggu Pembayaran',
            'payment_method' => $validated['payment_method'],
            'bank_name'      => $validated['payment_method'] === 'bank' ? $validated['bank_name'] : null,
            'wallet_type'    => $validated['payment_method'] === 'e-wallet' ? $validated['wallet_type'] : null,
            'payment_proof'  => $paymentProofPath,
        ]);

        $item->delete();

        return redirect()->route('orders.index')->with('success', 'Pembayaran berhasil untuk 1 item!');
    }
}
