<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Testimonial;

class TestimonialController extends Controller
{
    // ✅ Tampilkan semua testimonial ke halaman publik
    public function index()
    {
        $testimonials = Testimonial::latest()->get();
        return view('testimonials.index', compact('testimonials'));
    }

    // ✅ Simpan testimonial baru dari form
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'rating' => 'required|integer|min:1|max:5',
            'comment' => 'required',
            'photo' => 'nullable|image|max:2048',
        ]);

        $photoPath = null;
        if ($request->hasFile('photo')) {
            $photoPath = $request->file('photo')->store('testimonials', 'public');
        }

        Testimonial::create([
            'name' => $request->name,
            'rating' => $request->rating,
            'comment' => $request->comment,
            'photo' => $photoPath,
        ]);

        return redirect()->back()->with('success', 'Terima kasih atas testimoni Anda!');
    }
}
