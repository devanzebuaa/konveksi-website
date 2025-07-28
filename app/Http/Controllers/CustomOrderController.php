<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use App\Models\CustomOrder;
use App\Models\Message;
use Illuminate\Support\Facades\Auth;

class CustomOrderController extends Controller
{
    // Formulir untuk user buat custom order
    public function create()
    {
        $user = Auth::user();
        $orders = CustomOrder::where('user_id', $user->id)->latest()->get();
        return view('custom-order.form', compact('orders'));
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
            'quantity' => 'required|integer|min:24',
            'contact' => 'required',
        ]);

        // Upload file jika ada
        $filePath = $request->file('design_file')?->store('custom-orders', 'public');

        CustomOrder::create([
            'user_id' => Auth::id(),
            'name' => $request->name,
            'product_type' => $request->product_type,
            'size_detail' => $request->size_detail,
            'description' => $request->description,
            'design_file' => $filePath,
            'quantity' => $request->quantity,
            'contact' => $request->contact,
        ]);


        return redirect()->route('custom-order.form')->with('success', 'Pesanan custom berhasil dikirim. Kami akan segera menghubungi Anda.');
    }

    // Chat untuk custom order (user)
    public function chat($orderId)
    {
        $order = CustomOrder::findOrFail($orderId);
        // Pastikan user hanya bisa akses order miliknya
        $user = Auth::user();
        if ($order->user_id !== $user->id) {
            abort(403);
        }
        $messages = Message::where('order_id', $order->id)->with('sender')->orderBy('created_at')->get();
        return view('chat.index', compact('order', 'messages'));
    }

    public function sendMessage(Request $request, $orderId)
    {
        $order = CustomOrder::findOrFail($orderId);
        $user = Auth::user();
        if ($order->user_id !== $user->id) {
            abort(403);
        }
        $request->validate([
            'content' => 'required|string|max:1000',
        ]);
        Message::create([
            'order_id' => $order->id,
            'sender_id' => $user->id,
            'sender_type' => 'user',
            'content' => $request->content,
        ]);
        return redirect()->route('custom-order.chat', $order->id);
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

    // Chat admin untuk custom order
    public function adminChat($orderId)
    {
        $order = CustomOrder::findOrFail($orderId);
        $messages = $order->messages()->with('sender')->orderBy('created_at')->get();
        return view('chat.index', [
            'order' => $order,
            'messages' => $messages,
            'isAdmin' => true,
        ]);
    }

    public function adminSendMessage(Request $request, $orderId)
    {
        $order = CustomOrder::findOrFail($orderId);
        $user = Auth::user();
        if (!$user->is_admin) {
            abort(403);
        }
        $request->validate([
            'content' => 'required|string|max:1000',
        ]);
        Message::create([
            'order_id' => $order->id,
            'sender_id' => $user->id,
            'sender_type' => 'admin',
            'content' => $request->content,
        ]);
        return redirect()->route('admin.custom-orders.chat', $order->id);
    }
}
