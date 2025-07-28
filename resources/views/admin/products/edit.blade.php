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

        {{-- Harga per ukuran --}}
        <div class="mb-4">
            <h5 class="mb-2">Harga Berdasarkan Ukuran</h5>
            <div class="row">
                @php
                    $sizes = ['S', 'M', 'L', 'XL', 'XXL'];
                @endphp

                @foreach ($sizes as $size)
                    <div class="col-md-2 mb-2">
                        <label class="form-label">Ukuran {{ $size }}</label>
                        <input type="number" step="0.01" name="size_prices[{{ $size }}]"
                            class="form-control"
                            value="{{ old('size_prices.' . $size, $product->sizePrices->firstWhere('size', $size)->price ?? '') }}">
                    </div>
                @endforeach
            </div>
        </div>

        <div class="mb-3">
            <label>Ganti Gambar (opsional)</label>
            <input type="file" name="image" class="form-control">
        </div>

        <div class="form-check mb-4">
            <input class="form-check-input" type="checkbox" name="is_featured" id="is_featured"
                {{ old('is_featured', $product->is_featured) ? 'checked' : '' }}>
            <label class="form-check-label" for="is_featured">
                Tampilkan di Beranda (Produk Unggul)
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
                    <th>Harga</th>
                    <th>TampilkanProduk</th>
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
                    <td>Rp {{ number_format($variant->price, 0, ',', '.') }}</td>
                    <td>
                        @if ($variant->is_featured)
                            <span class="badge bg-success">Ya</span>
                        @else
                            <span class="badge bg-secondary">Tidak</span>
                        @endif
                    </td>
                    <td class="d-flex gap-2 flex-wrap">
                        <a href="{{ route('admin.variants.edit', [$product->id, $variant->id]) }}" class="btn btn-sm btn-warning">Edit</a>

                        <form action="{{ route('admin.variants.destroy', [$product->id, $variant->id]) }}" method="POST" onsubmit="return confirm('Hapus varian ini?')">
                            @csrf
                            @method('DELETE')
                            <button class="btn btn-sm btn-danger">Hapus</button>
                        </form>

                        {{-- Tombol toggle unggulan --}}
                        <form action="{{ route('admin.variants.toggleFeatured', [$product->id, $variant->id]) }}" method="POST">
                            @csrf
                            @method('PATCH')
                            <button class="btn btn-sm {{ $variant->is_featured ? 'btn-outline-secondary' : 'btn-outline-success' }}">
                                {{ $variant->is_featured ? 'Batalkan' : 'Tampilkan' }}
                            </button>
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
            <label>Harga Varian</label>
            <input type="number" name="price" class="form-control" min="0" step="1000" placeholder="Contoh: 150000" required>
            @error('price')
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

        <div class="form-check mb-3">
            <input class="form-check-input" type="checkbox" name="is_featured" id="variant_is_featured"
                {{ old('is_featured') ? 'checked' : '' }}>
            <label class="form-check-label" for="variant_is_featured">
                Tampilkan varian ini di Beranda
            </label>
        </div>

        <button class="btn btn-success">Tambah Varian</button>
    </form>

    <div class="mt-5">
        <a href="{{ route('admin.products.index') }}" class="btn btn-outline-secondary">
            <i class="fas fa-arrow-left"></i> Kembali ke Kelola Produk
        </a>
    </div>
</div>

<style>
    .d-flex {
        display: flex;
        align-items: center;
    }
    .gap-2 {
        gap: 0.5rem;
    }
</style>
@endsection
