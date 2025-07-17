<?php

namespace App\Http\Controllers;

use App\Models\Gallery;
use Illuminate\Http\Request;

class GalleryController extends Controller
{
    /**
     * Tampilkan halaman galeri dengan gambar-gambar dari database.
     */
    public function index()
    {
        // Ambil 12 gambar per halaman (bisa disesuaikan)
        $galleries = Gallery::latest()->paginate(12);

        return view('gallery', compact('galleries'));
    }
}
