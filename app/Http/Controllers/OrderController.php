<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Product;
use App\Models\ProductVariant;
use App\Models\ProductSizePrice;
use App\Models\CartItem;
use App\Models\OrderItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class OrderController extends Controller
{
    public function index()
    {
        $orders = Order::with(['items.product', 'items.variant'])
            ->where('user_id', Auth::id())
            ->latest()
            ->get();

        return view('orders.index', compact('orders'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'product_id'      => 'required|exists:products,id',
            'warna'           => 'required|string',
            'ukuran'          => 'required|string',
            'jumlah'          => 'required|integer|min:1',
            'payment_method'  => 'required|in:bank,e-wallet',
            'bank_name'       => 'required_if:payment_method,bank|nullable|string',
            'wallet_type'     => 'required_if:payment_method,e-wallet|nullable|string',
            'payment_proof'   => 'required|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
        ]);

        $product = Product::findOrFail($request->product_id);
        $variant = ProductVariant::where('product_id', $product->id)
                                 ->where('color', $request->warna)
                                 ->first();

        if (!$variant || $variant->stock < $request->jumlah) {
            return back()->with('error', 'Stok tidak mencukupi.');
        }

        $sizePrice = $product->sizePrices()
                             ->where('size', $request->ukuran)
                             ->first();

        if (!$sizePrice) {
            return back()->with('error', 'Harga untuk ukuran tidak ditemukan.');
        }

        $proofPath = $request->file('payment_proof')->store('bukti', 'public');

        DB::transaction(function () use ($request, $product, $variant, $sizePrice, $proofPath) {
            $total_harga = $sizePrice->price * $request->jumlah;

            $order = Order::create([
                'user_id'        => Auth::id(),
                'status'         => 'Menunggu Pembayaran',
                'payment_method' => $request->payment_method,
                'bank_name'      => $request->payment_method === 'bank' ? $request->bank_name : null,
                'wallet_type'    => $request->payment_method === 'e-wallet' ? $request->wallet_type : null,
                'payment_proof'  => $proofPath,
                'total_harga'    => $total_harga,
            ]);

            OrderItem::create([
                'order_id'   => $order->id,
                'product_id' => $product->id,
                'variant_id' => $variant->id,
                'size'       => $request->ukuran,
                'quantity'   => $request->jumlah,
                'price'      => $sizePrice->price,
                'total'      => $total_harga,
            ]);

            $variant->decrement('stock', $request->jumlah);
        });

        return redirect()->route('orders.index')->with('success', 'Order berhasil dibuat!');
    }

    public function checkout(Request $request)
    {
        $request->validate([
            'item_ids'       => 'required|array|min:1',
            'item_ids.*'     => 'exists:cart_items,id',
            'payment_method' => 'required|in:bank,e-wallet',
            'bank_name'      => 'required_if:payment_method,bank|nullable|string',
            'wallet_type'    => 'required_if:payment_method,e-wallet|nullable|string',
            'payment_proof'  => 'required|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
        ]);

        $user = Auth::user();

        $cartItems = $user->cartItems()
                          ->whereIn('id', $request->item_ids)
                          ->with(['product', 'variant'])
                          ->get();

        if ($cartItems->isEmpty()) {
            return back()->with('error', 'Tidak ada item valid untuk checkout.');
        }

        $proofPath = $request->file('payment_proof')->store('bukti', 'public');

        try {
            DB::transaction(function () use ($cartItems, $request, $proofPath, $user) {
                $totalOrder = 0;

                $order = Order::create([
                    'user_id'        => $user->id,
                    'status'         => 'Menunggu Pembayaran',
                    'payment_method' => $request->payment_method,
                    'bank_name'      => $request->payment_method === 'bank' ? $request->bank_name : null,
                    'wallet_type'    => $request->payment_method === 'e-wallet' ? $request->wallet_type : null,
                    'payment_proof'  => $proofPath,
                ]);

                foreach ($cartItems as $item) {
                    $variant = $item->variant;
                    $product = $item->product;

                    if (!$product || !$variant || $variant->stock < $item->jumlah) {
                        throw new \Exception("Produk {$product->name} tidak valid atau stok habis.");
                    }

                    $sizePrice = $product->sizePrices()
                                         ->where('size', $item->ukuran)
                                         ->first();

                    if (!$sizePrice) {
                        throw new \Exception("Harga untuk ukuran {$item->ukuran} tidak ditemukan.");
                    }

                    $subtotal = $sizePrice->price * $item->jumlah;
                    $totalOrder += $subtotal;

                    OrderItem::create([
                        'order_id'   => $order->id,
                        'product_id' => $product->id,
                        'variant_id' => $variant->id,
                        'size'       => $item->ukuran,
                        'quantity'   => $item->jumlah,
                        'price'      => $sizePrice->price,
                        'total'      => $subtotal,
                    ]);

                    $variant->decrement('stock', $item->jumlah);
                    $item->delete();
                }

                $order->update(['total_harga' => $totalOrder]);
            });

            return redirect()->route('orders.index')->with('success', 'Checkout berhasil! Silakan tunggu konfirmasi.');
        } catch (\Exception $e) {
            return back()->with('error', 'Checkout gagal: ' . $e->getMessage());
        }
    }
}
