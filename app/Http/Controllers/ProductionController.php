<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ProductionController extends Controller
{
    public function index()
    {
        $steps = [
            ['title' => 'Desain', 'desc' => 'Konsultasi Desain Dengan Tim Kreatif Kami'],
            ['title' => 'Pemilihan Bahan', 'desc' => 'Pilih Bahan Nerkualitas Dari Berbagai Pilihan'],
            ['title' => 'Pemotongan', 'desc' => 'Pemotongan Bahan Dengan Presisi Tinggi'],
            ['title' => 'Penjahitan', 'desc' => 'Dijahit Oleh Ahli Berpengalaman'],
            ['title' => 'Quality Control', 'desc' => 'Pemeriksaan Ketat Sebelum Pengiriman'],
            ['title' => 'Pengemasan', 'desc' => 'Pengemasan Rapi Dan Aman']
        ];
        
        return view('production', compact('steps'));
    }
}