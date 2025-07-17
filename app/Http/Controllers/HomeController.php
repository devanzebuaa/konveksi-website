<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Order;
use Illuminate\Support\Facades\Auth;

class HomeController extends Controller
{
    /**
     * Halaman Beranda Publik
     */
    public function index()
    {
        $featuredProducts = Product::where('is_featured', true)->get();
        $recentStatus = collect(); // Kosong default

        if (Auth::check()) {
            $recentStatus = Order::with('product')
                ->where('user_id', Auth::id())
                ->where('user_notified', false)
                ->where('status', '!=', 'Menunggu Pembayaran')
                ->latest()
                ->take(5)
                ->get();

            // Tandai notifikasi sebagai sudah dibaca
            foreach ($recentStatus as $order) {
                $order->update(['user_notified' => true]);
            }
        }

        return view('home', compact('featuredProducts', 'recentStatus'));
    }

    /**
     * Dashboard User Login
     */
    public function dashboard()
    {
        $user = Auth::user();

        $orders = Order::where('user_id', $user->id)->latest()->get();

        $recentStatus = Order::where('user_id', $user->id)
            ->where('user_notified', false)
            ->where('status', '!=', 'Menunggu Pembayaran')
            ->latest()
            ->get();

        return view('home', compact('user', 'orders', 'recentStatus'));
    }
}
