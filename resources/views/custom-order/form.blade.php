@extends('layouts.app')

@section('title', 'Pesanan Custom')

@section('content')
<div class="container py-5">
    <div class="card shadow-sm p-4 border-0 rounded-4">
        <h2 class="mb-4 text-center fw-bold">🧵 Form Pesanan Custom</h2>

        @if(session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        <form action="{{ route('custom-order.store') }}" method="POST" enctype="multipart/form-data">
            @csrf

            <div class="mb-3">
                <label for="name" class="form-label">Nama Lengkap</label>
                <input type="text" name="name" class="form-control" required>
            </div>

            <div class="mb-3">
                <label for="product_type" class="form-label">Jenis Produk</label>
                <input type="text" name="product_type" class="form-control" placeholder="Contoh: Kemeja Seragam, Kaos Komunitas" required>
            </div>

            <div class="mb-3">
                <label for="size_detail" class="form-label">Detail Ukuran (Opsional)</label>
                <textarea name="size_detail" class="form-control" rows="2" placeholder="Contoh: 2x M, 3x L, 1x XL"></textarea>
            </div>

            <div class="mb-3">
                <label for="description" class="form-label">Deskripsi Tambahan</label>
                <textarea name="description" class="form-control" rows="3" placeholder="Contoh: Bahan cotton combed 30s, sablon DTF full depan"></textarea>
            </div>

            <div class="mb-3">
                <label for="design_file" class="form-label">Upload Desain (Opsional)</label>
                <input type="file" name="design_file" class="form-control" accept="image/*">
            </div>

            <div class="mb-3">
                <label for="quantity" class="form-label">Jumlah Pesanan</label>
                <input type="number" name="quantity" class="form-control" min="1" required>
            </div>

            <div class="mb-3">
                <label for="payment_method" class="form-label">Metode Pembayaran</label>
                <select name="payment_method" class="form-select" required>
                    <option value="">Pilih Metode</option>
                    <option value="Bank Transfer">Bank Transfer</option>
                    <option value="E-Wallet">E-Wallet</option>
                </select>
            </div>

            <div class="mb-3">
                <label for="contact" class="form-label">Kontak (WA / No HP)</label>
                <input type="text" name="contact" class="form-control" placeholder="Contoh: 0812xxxxxxx" required>
            </div>

            <button type="submit" class="btn btn-primary w-100 mt-3">Kirim Pesanan Custom</button>
        </form>
    </div>
</div>
@endsection
