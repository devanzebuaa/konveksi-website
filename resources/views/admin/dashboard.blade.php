@extends('layouts.app')

@section('title', 'Dashboard')

@section('content')
<div class="px-4 py-4">
    <h2 class="mb-4">Admin Dashboard</h2>

    <div class="row g-4">
        <!-- Total Produk -->
        <div class="col-md-3">
            <div class="card bg-primary text-white h-100">
                <div class="card-body">
                    <h5 class="card-title">Total Produk</h5>
                    <p class="display-4 fw-bold">{{ $products }}</p>
                    <a href="{{ route('admin.products.index') }}" class="btn btn-outline-light btn-sm mt-2">
                        Lihat Semua Produk
                    </a>
                </div>
            </div>
        </div>

        <!-- Total Galeri -->
        <div class="col-md-3">
            <div class="card bg-success text-white h-100">
                <div class="card-body">
                    <h5 class="card-title">Total Galeri</h5>
                    <p class="display-4 fw-bold">{{ $galleries }}</p>
                    <a href="{{ route('admin.galleries.index') }}" class="btn btn-outline-light btn-sm mt-2">
                        Lihat Semua Galeri
                    </a>
                </div>
            </div>
        </div>

        <!-- Total Testimoni -->
        <div class="col-md-3">
            <div class="card bg-warning text-dark h-100">
                <div class="card-body">
                    <h5 class="card-title">Testimoni</h5>
                    <p class="display-4 fw-bold">{{ $testimonials }}</p>
                    <a href="{{ route('admin.testimonials.index') }}" class="btn btn-outline-dark btn-sm mt-2">
                        Kelola Testimoni
                    </a>
                </div>
            </div>
        </div>

        <!-- Total Custom Order -->
        <div class="col-md-3">
            <div class="card bg-info text-white h-100">
                <div class="card-body">
                    <h5 class="card-title">Pesanan Custom</h5>
                    <p class="display-4 fw-bold">{{ $customOrders }}</p>
                    <a href="{{ route('admin.custom-orders.index') }}" class="btn btn-outline-light btn-sm mt-2">
                        Lihat Pesanan Custom
                    </a>
                </div>
            </div>
        </div>

        <!-- ✅ Total Order User -->
        <div class="col-md-3">
            <div class="card bg-secondary text-white h-100">
                <div class="card-body">
                    <h5 class="card-title">Order User</h5>
                    <p class="display-4 fw-bold">{{ $userOrders }}</p>
                    <a href="{{ route('admin.orders.index') }}" class="btn btn-outline-light btn-sm mt-2">
                        Lihat Semua Order
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
