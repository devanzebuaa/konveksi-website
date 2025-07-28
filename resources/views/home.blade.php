@extends('layouts.app')

@section('title', 'Beranda')

@section('content')

{{-- Notifikasi Status Order --}}
@if(Auth::check() && isset($recentStatus) && $recentStatus->count())
<div id="notif-alert" class="container mt-4">
    <div class="alert alert-info alert-dismissible fade show d-flex align-items-start justify-content-between shadow-sm rounded px-4 py-3" role="alert">
        <div>
            <h5 class="mb-2"><i class="fas fa-bell me-2"></i>Notifikasi Order Terbaru</h5>
            <ul class="mb-0 ps-3">
                @foreach($recentStatus as $notif)
                    <li>
                        Order untuk <strong>{{ $notif->product->name }}</strong> telah <strong>{{ $notif->status }}</strong>.
                    </li>
                @endforeach
            </ul>
        </div>
        <button type="button" class="btn-close ms-3" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
</div>
@endif

<!-- Hero Section -->
<section class="hero-section bg-primary text-white py-5">
    <div class="container">
        <div class="row align-items-center gx-5">
            <div class="col-lg-5 mb-4 mb-lg-0 text-center text-lg-start">
                <h1 class="display-4 fw-bold mb-3">Konveksi Berkualitas Tinggi</h1>
                <p class="lead mb-4">Kami Menyediakan Berbagai Produk Pakaian Dengan Kualitas Terbaik Dan Harga Kompetitif.</p>
                <a href="{{ route('products.index') }}" class="btn btn-light btn-lg">Lihat Produk</a>
            </div>
            <div class="col-lg-7 text-center">
                <div id="heroCarousel" class="carousel slide" data-bs-ride="carousel" data-bs-interval="3000">
                    <div class="carousel-inner">
                        @for ($i = 1; $i <= 4; $i++)
                            <div class="carousel-item {{ $i === 1 ? 'active' : '' }}">
                                <img src="{{ asset("images/beranda/contoh$i.png") }}"
                                     class="d-block ms-lg-auto img-fluid"
                                     style="max-height: 400px; max-width: 80%; object-fit: contain;"
                                     alt="Gambar contoh {{ $i }}">
                            </div>
                        @endfor
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>


<!-- Produk Unggulan Berjalan -->
@if(isset($featuredVariants) && $featuredVariants->count())
<section class="py-5 px-5 bg-light overflow-hidden">
    <div class="text-center mb-5">
        <h2 class="fw-bold">Sorotan Produk</h2>
        <p class="text-muted">Berbagai Pilihan Produk Berkualitas Kami</p>
    </div>

    <div class="scroll-container position-relative">
        <div class="scroll-track d-flex">
            @foreach (range(1, 2) as $loopSet)
                @foreach ($featuredVariants as $variant)
                    @php $product = $variant->product; @endphp
                    <div class="product-card card mx-3 shadow-sm" style="min-width: 250px; max-width: 250px;">
                        <img src="{{ $variant->image ? asset('storage/' . $variant->image) : asset('images/products/' . $product->image) }}" class="card-img-top" alt="{{ $product->name }}">
                        <div class="card-body text-center">
                            <h6 class="text-muted text-uppercase">{{ $product->category }}</h6>
                            <h5 class="card-title">{{ $product->name }}</h5>
                            <span class="text-primary fw-bold">Rp {{ number_format($product->price, 0, ',', '.') }}</span>
                        </div>
                    </div>
                @endforeach
            @endforeach
        </div>
    </div>

    <div class="text-center mt-4">
        <a href="{{ route('products.index') }}" class="btn btn-primary">Lihat Semua Produk</a>
    </div>
</section>
@endif

<!-- Why Choose Us -->
<section class="py-5 bg-light px-5">
    <div class="text-center mb-5">
        <h2 class="fw-bold">Mengapa Memilih Kami?</h2>
    </div>
    <div class="row gx-4 gy-4">
        <div class="col-md-4 text-center">
            <div class="bg-white p-4 rounded shadow-sm h-100">
                <div class="icon-box bg-primary text-white mb-3 mx-auto">
                    <i class="fas fa-award fa-2x"></i>
                </div>
                <h4>Kualitas Terbaik</h4>
                <p>Bahan premium dan jahitan berkualitas tinggi untuk produk yang tahan lama.</p>
            </div>
        </div>
        <div class="col-md-4 text-center">
            <div class="bg-white p-4 rounded shadow-sm h-100">
                <div class="icon-box bg-primary text-white mb-3 mx-auto">
                    <i class="fas fa-truck fa-2x"></i>
                </div>
                <h4>Pengiriman Cepat</h4>
                <p>Proses produksi efisien dan pengiriman tepat waktu ke seluruh Indonesia.</p>
            </div>
        </div>
        <div class="col-md-4 text-center">
            <div class="bg-white p-4 rounded shadow-sm h-100">
                <div class="icon-box bg-primary text-white mb-3 mx-auto">
                    <i class="fas fa-tshirt fa-2x"></i>
                </div>
                <h4>Custom Order</h4>
                <p>Menerima pesanan custom sesuai kebutuhan dan desain pelanggan.</p>
            </div>
        </div>
    </div>
</section>

{{-- Style Tambahan --}}
<style>
.scroll-container {
    width: 100%;
    overflow: hidden;
}
.scroll-track {
    animation: scroll-left 20s linear infinite;
    width: max-content;
}
@keyframes scroll-left {
    0% { transform: translateX(0); }
    100% { transform: translateX(-50%); }
}
.icon-box {
    padding-top: 20px;
    padding-bottom: 20px;
    border-radius: 8px;
    width: 60px;
    height: 60px;
    display: flex;
    align-items: center;
    justify-content: center;
}
</style>

@endsection