@extends('layouts.app')

@section('title', 'Produk Kami')

@section('content')
<!-- Product Header -->
<section class="product-header bg-primary text-white py-5">
    <div class="container text-center">
        <h1 class="display-4 fw-bold mb-3">Produk Kami</h1>
        <p class="lead">Kualitas Terbaik Untuk Kebutuhan Anda</p>
    </div>
</section>

<!-- Products -->
<section class="py-5">
    <div class="container">
        <div class="row mb-4 align-items-center">
            <div class="col-md-6">
                <h2 class="fw-bold">
                    Katalog Produk
                    @if(request('category'))
                        - {{ request('category') }}
                    @endif
                </h2>
            </div>
            <div class="col-md-6 text-md-end">
                <div class="dropdown d-inline-block me-2 mb-2">
                    <button class="btn btn-outline-primary dropdown-toggle" type="button" id="filterDropdown" data-bs-toggle="dropdown">
                        Filter Kategori
                    </button>
                    <ul class="dropdown-menu">
                        <li><a class="dropdown-item {{ request('category') == null ? 'active' : '' }}" href="{{ route('products.index') }}">Semua Kategori</a></li>
                        <li><a class="dropdown-item {{ request('category') == 'Kaos' ? 'active' : '' }}" href="{{ route('products.index', ['category' => 'Kaos']) }}">Kaos</a></li>
                        <li><a class="dropdown-item {{ request('category') == 'Jaket' ? 'active' : '' }}" href="{{ route('products.index', ['category' => 'Jaket']) }}">Jaket</a></li>
                        <li><a class="dropdown-item {{ request('category') == 'Celana' ? 'active' : '' }}" href="{{ route('products.index', ['category' => 'Celana']) }}">Celana</a></li>
                        <li><a class="dropdown-item {{ request('category') == 'Kemeja' ? 'active' : '' }}" href="{{ route('products.index', ['category' => 'Kemeja']) }}">Kemeja</a></li>
                    </ul>
                </div>

                <form action="{{ route('products.index') }}" method="GET" class="d-inline-block">
                    @if(request('category'))
                        <input type="hidden" name="category" value="{{ request('category') }}">
                    @endif
                    <div class="input-group">
                        <input type="text" name="search" class="form-control" placeholder="Cari produk..." value="{{ request('search') }}">
                        <button class="btn btn-primary" type="submit">Cari</button>
                    </div>
                </form>
            </div>
        </div>
        
        <div class="row">
            @forelse($products as $product)
            <div class="col-md-4 mb-4">
                <div class="card h-100 shadow-sm product-card">
                    <div class="badge bg-primary position-absolute" style="top: 10px; right: 10px;">{{ $product->category }}</div>
                    <img src="{{ asset('images/products/' . $product->image) }}" class="card-img-top" alt="{{ $product->name }}">
                    <div class="card-body">
                        <h5 class="card-title">{{ $product->name }}</h5>
                        <p class="card-text">{{ Str::limit($product->description, 100) }}</p>
                        <div class="d-flex justify-content-between align-items-center">
                            <span class="text-primary fw-bold">Rp {{ number_format($product->price, 0, ',', '.') }}</span>
                            <a href="{{ route('products.show', $product->id) }}" class="btn btn-sm btn-primary">Detail</a>
                        </div>
                    </div>
                </div>
            </div>
            @empty
                <div class="col-12 text-center py-5">
                    <h5>Tidak Ada Produk Ditemukan.</h5>
                </div>
            @endforelse
        </div>
        
        <div class="d-flex justify-content-center mt-4">
            {{ $products->withQueryString()->links('pagination::bootstrap-4') }}
        </div>
    </div>
</section>
@endsection

@section('styles')
<style>
    .product-header {
        background: linear-gradient(rgba(0, 0, 0, 0.7), rgba(0, 0, 0, 0.7)), url('{{ asset('images/product-bg.jpg') }}');
        background-size: cover;
        background-position: center;
    }

    .product-card {
        transition: transform 0.3s;
    }

    .product-card:hover {
        transform: translateY(-5px);
    }
</style>
@endsection
