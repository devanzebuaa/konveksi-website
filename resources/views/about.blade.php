@extends('layouts.app')

@section('title', 'Tentang Kami')

@section('content')

<!-- Tentang Dinara Konveksi Modern -->
<section class="py-5 bg-light">
    <div class="container">
        <div class="row align-items-center justify-content-center">
            <div class="col-lg-7 mb-4 mb-lg-0">
                <div class="mb-4">
                    <h1 class="fw-bold mb-3" style="font-size:2.5rem; letter-spacing:0.5px;">Dinara Konveksi</h1>
                    <div class="mb-3" style="font-size:1.15rem; color:#444; text-align:justify; line-height:1.7;">
                        Dinara Konveksi adalah platform e-commerce yang menyediakan solusi lengkap untuk kebutuhan pakaian custom, seragam, dan fashion berkualitas. Kami menghubungkan pelanggan dengan layanan konveksi modern, proses pemesanan online yang mudah, serta dukungan tim profesional dari desain hingga pengiriman. Dengan sistem digital, pelanggan dapat memantau status pesanan, memilih bahan, desain, dan melakukan pembayaran secara aman dari mana saja.
                    </div>
                </div>
                <div class="mb-4">
                    <ul class="ps-3" style="font-size:1.08rem; color:#333;">
                        <li class="mb-2">Pesan pakaian custom, seragam, dan fashion secara online.</li>
                        <li class="mb-2">Pilihan bahan, warna, dan desain sesuai kebutuhan.</li>
                        <li class="mb-2">Proses produksi transparan dan terpantau.</li>
                        <li>Pembayaran aman dan pengiriman ke seluruh Indonesia.</li>
                    </ul>
                </div>
                <a href="{{ route('products.index') }}" class="btn btn-primary btn-lg shadow">Lihat Produk</a>
            </div>
            <div class="col-lg-5 text-center d-flex align-items-center justify-content-center" style="gap:0;">
                <img src="/images/Gambar.jpg" alt="Dinara Konveksi" class="img-fluid shadow-lg" style="max-width:480px; width:100%; height:420px; object-fit:cover; border-radius:18px; border:8px solid #fff; margin-bottom:0;">
            </div>
        </div>
    </div>
</section>


@endsection
