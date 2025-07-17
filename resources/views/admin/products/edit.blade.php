@extends('layouts.admin')

@section('title', 'Edit Produk')

@section('content')
<div class="container py-4">
    <h3 class="mb-4 fw-bold">Edit Produk</h3>

    {{-- Form Edit Produk --}}
    <form action="{{ route('admin.products.update', $product->id) }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')

        <div class="mb-3">
            <label>Nama Produk</label>
            <input type="text" name="name" class="form-control" value="{{ $product->name }}" required>
        </div>

        <div class="mb-3">
            <label>Kategori</label>
            <input type="text" name="category" class="form-control" value="{{ $product->category }}" required>
        </div>

        <div class="mb-3">
            <label>Harga</label>
            <input type="number" name="price" class="form-control" value="{{ $product->price }}" required>
        </div>

        <div class="mb-3">
            <label>Deskripsi</label>
            <textarea name="description" class="form-control" rows="4">{{ $product->description }}</textarea>
        </div>

        <div class="mb-3">
            <label>Ganti Gambar (opsional)</label>
            <input type="file" name="image" class="form-control">
        </div>

        <div class="form-check mb-4">
            <input class="form-check-input" type="checkbox" name="is_featured" id="is_featured"
                {{ old('is_featured', $product->is_featured) ? 'checked' : '' }}>
            <label class="form-check-label" for="is_featured">
                Tampilkan di Beranda (Produk Unggulan)
            </label>
        </div>

        <button type="submit" class="btn btn-primary">Update</button>
        <a href="{{ route('admin.products.index') }}" class="btn btn-secondary">Batal</a>
    </form>

    {{-- Manajemen Varian Warna --}}
    <hr>
    <h4 class="mt-4">Varian Warna</h4>

    @if ($product->variants->count())
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>Warna</th>
                    <th>Gambar</th>
                    <th>Stok</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($product->variants as $variant)
                <tr>
                    <td>{{ $variant->color }}</td>
                    <td>
                        @if ($variant->image)
                            <img src="{{ asset('storage/' . ltrim($variant->image, '/')) }}" width="50">
                        @endif
                    </td>
                    <td>{{ $variant->stock }}</td>
                    <td>
                        <form action="{{ route('admin.variants.destroy', [$product->id, $variant->id]) }}" method="POST" onsubmit="return confirm('Hapus varian ini?')">
                            @csrf
                            @method('DELETE')
                            <button class="btn btn-sm btn-danger">Hapus</button>
                        </form>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    @else
        <p class="text-muted">Belum ada varian warna.</p>
    @endif

    {{-- Form Tambah Varian --}}
    <form action="{{ route('admin.variants.store', $product->id) }}" method="POST" enctype="multipart/form-data" class="mt-3">
        @csrf

        <div class="mb-3">
            <label>Warna</label>
            <input type="text" name="color" class="form-control" required>
            @error('color')
                <div class="text-danger">{{ $message }}</div>
            @enderror
        </div>

        <div class="mb-3">
            <label>Stok</label>
            <input type="number" name="stock" class="form-control" min="0" value="{{ old('stock', 0) }}" required>
            @error('stock')
                <div class="text-danger">{{ $message }}</div>
            @enderror
        </div>

        <div class="mb-3">
            <label>Gambar Warna (opsional)</label>
            <input type="file" name="image" class="form-control">
            @error('image')
                <div class="text-danger">{{ $message }}</div>
            @enderror
        </div>

        <button class="btn btn-success">Tambah Varian</button>
    </form>

    {{-- Tombol Kembali --}}
    <div class="mt-5">
        <a href="{{ route('admin.products.index') }}" class="btn btn-outline-secondary">
            <i class="fas fa-arrow-left"></i> Kembali ke Kelola Produk
        </a>
    </div>
</div>
@endsection
