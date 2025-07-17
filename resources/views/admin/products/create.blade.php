@extends('layouts.admin')

@section('title', 'Tambah Produk')

@section('content')
<div class="container py-4">
    <h3 class="mb-4 fw-bold">Tambah Produk Baru</h3>

    <form action="{{ route('admin.products.store') }}" method="POST" enctype="multipart/form-data">
        @csrf

        <div class="mb-3">
            <label>Nama Produk</label>
            <input type="text" name="name" class="form-control" required>
        </div>

        <div class="mb-3">
            <label>Kategori</label>
            <select name="category" class="form-select" required>
                <option value="">Pilih Kategori</option>
                <option value="Kaos">Kaos</option>
                <option value="Kemeja">Kemeja</option>
                <option value="Jaket">Jaket</option>
                <option value="Celana">Celana</option>
            </select>
        </div>

        <div class="mb-3">
            <label>Harga</label>
            <input type="number" name="price" class="form-control" required>
        </div>

        <div class="mb-3">
            <label>Deskripsi</label>
            <textarea name="description" class="form-control" rows="4"></textarea>
        </div>

        <div class="mb-3">
            <label>Gambar Produk</label>
            <input type="file" name="image" class="form-control">
        </div>

        {{-- Checkbox Produk Unggulan --}}
        <div class="form-check mb-4">
            <input class="form-check-input" type="checkbox" name="is_featured" id="is_featured">
            <label class="form-check-label" for="is_featured">
                Tampilkan di Beranda (Produk Unggulan)
            </label>
        </div>

        <button type="submit" class="btn btn-success">Simpan</button>
        <a href="{{ route('admin.products.index') }}" class="btn btn-secondary">Batal</a>
    </form>
</div>
@endsection
