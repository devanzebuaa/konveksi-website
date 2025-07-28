@extends('layouts.admin')

@section('title', 'Tambah Produk')

@section('content')
<div class="container py-4">
    <h3 class="mb-4 fw-bold">Tambah Produk Baru</h3>

    <form action="{{ route('admin.products.store') }}" method="POST" enctype="multipart/form-data">
        @csrf

        {{-- Nama Produk --}}
        <div class="mb-3">
            <label>Nama Produk</label>
            <input type="text" name="name" class="form-control" required>
        </div>

        {{-- Kategori --}}
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

        {{-- Harga Umum (opsional kalau pakai harga per varian) --}}
        <div class="mb-3">
            <label>Harga (Opsional)</label>
            <input type="number" name="price" class="form-control">
        </div>

        {{-- Deskripsi --}}
        <div class="mb-3">
            <label>Deskripsi</label>
            <textarea name="description" class="form-control" rows="4"></textarea>
        </div>

        {{-- Gambar Utama Produk --}}
        <div class="mb-3">
            <label>Gambar Produk</label>
            <input type="file" name="image" class="form-control" accept="image/*" required>
        </div>

        {{-- Checkbox Produk Unggulan --}}
        <div class="form-check mb-4">
            <input class="form-check-input" type="checkbox" name="is_featured" id="is_featured">
            <label class="form-check-label" for="is_featured">
                Tampilkan di Beranda (Produk Unggulan)
            </label>
        </div>

        {{-- Variants --}}
        <hr>
        <h5>Varian Produk</h5>
        <div id="variants-container">
            <div class="variant-item border p-3 rounded mb-3">
                <h6>Varian #1</h6>
                <div class="mb-2">
                    <label>Warna</label>
                    <input type="text" name="variants[0][color]" class="form-control" required>
                </div>
                <div class="mb-2">
                    <label>Gambar Varian</label>
                    <input type="file" name="variants[0][image]" class="form-control" accept="image/*">
                </div>
                <div class="mb-2">
                    <label>Harga per Ukuran</label>
                    <input type="number" name="size_prices[0][S]" class="form-control mb-1" placeholder="Harga ukuran S">
                    <input type="number" name="size_prices[0][M]" class="form-control mb-1" placeholder="Harga ukuran M">
                    <input type="number" name="size_prices[0][L]" class="form-control mb-1" placeholder="Harga ukuran L">
                    <input type="number" name="size_prices[0][XL]" class="form-control" placeholder="Harga ukuran XL">
                    <input type="number" name="size_prices[0][XXL]" class="form-control" placeholder="Harga ukuran XXL">
                </div>
            </div>
        </div>

        <button type="button" class="btn btn-warning mb-4" id="add-variant">+ Tambah Varian</button>

        {{-- Tombol Aksi --}}
        <div>
            <button type="submit" class="btn btn-success">Simpan</button>
            <a href="{{ route('admin.products.index') }}" class="btn btn-secondary">Batal</a>
        </div>
    </form>
</div>

{{-- JavaScript Tambah Varian --}}
<script>
    let variantIndex = 1;

    document.getElementById('add-variant').addEventListener('click', function () {
        const container = document.getElementById('variants-container');
        const html = `
        <div class="variant-item border p-3 rounded mb-3">
            <h6>Varian #${variantIndex + 1}</h6>
            <div class="mb-2">
                <label>Warna</label>
                <input type="text" name="variants[${variantIndex}][color]" class="form-control" required>
            </div>
            <div class="mb-2">
                <label>Gambar Varian</label>
                <input type="file" name="variants[${variantIndex}][image]" class="form-control" accept="image/*">
            </div>
            <div class="mb-2">
                <label>Harga per Ukuran</label>
                <input type="number" name="size_prices[${variantIndex}][S]" class="form-control mb-1" placeholder="Harga ukuran S">
                <input type="number" name="size_prices[${variantIndex}][M]" class="form-control mb-1" placeholder="Harga ukuran M">
                <input type="number" name="size_prices[${variantIndex}][L]" class="form-control mb-1" placeholder="Harga ukuran L">
                <input type="number" name="size_prices[${variantIndex}][XL]" class="form-control" placeholder="Harga ukuran XL">
                <input type="number" name="size_prices[${variantIndex}][XXL]" class="form-control" placeholder="Harga ukuran XXL">
            </div>
        </div>`;
        container.insertAdjacentHTML('beforeend', html);
        variantIndex++;
    });
</script>
@endsection
