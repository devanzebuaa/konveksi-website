<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Gallery;
use App\Models\Testimonial;
use App\Models\CustomOrder;
use App\Models\Order; // ✅ Tambahkan ini

class AdminController extends Controller
{
    public function dashboard()
    {
        $products = Product::count();
        $galleries = Gallery::count();
        $testimonials = Testimonial::count();
        $customOrders = CustomOrder::count();
        $userOrders = Order::count();

        return view('admin.dashboard', compact(
            'products',
            'galleries',
            'testimonials',
            'customOrders',
            'userOrders'
        ));
    }
}
