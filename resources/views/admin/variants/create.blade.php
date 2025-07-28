@extends('layouts.admin')

@section('title', 'Tambah Varian - ' . $product->name)

@section('content')
<div class="container mt-4">
    <h3>Tambah Varian untuk: <strong>{{ $product->name }}</strong></h3>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <form action="{{ route('admin.variants.store', $product->id) }}" method="POST" enctype="multipart/form-data" class="mt-3">
        @csrf

        {{-- Warna --}}
        <div class="mb-3">
            <label for="color" class="form-label">Warna</label>
            <input type="text" name="color" id="color" class="form-control" value="{{ old('color') }}" required>
            @error('color')
                <div class="text-danger">{{ $message }}</div>
            @enderror
        </div>

        {{-- Harga --}}
        <div class="mb-3">
            <label for="price" class="form-label">Harga</label>
            <input type="number" name="price" id="price" class="form-control" value="{{ old('price') }}" min="0" required>
            @error('price')
                <div class="text-danger">{{ $message }}</div>
            @enderror
        </div>

        {{-- Gambar Varian --}}
        <div class="mb-3">
            <label for="image" class="form-label">Gambar Varian</label>
            <input type="file" name="image" id="image" class="form-control" required>
            @error('image')
                <div class="text-danger">{{ $message }}</div>
            @enderror
        </div>

        {{-- Stok --}}
        <div class="mb-3">
            <label for="stock" class="form-label">Stok</label>
            <input type="number" name="stock" id="stock" class="form-control" value="{{ old('stock', 0) }}" min="0" required>
            @error('stock')
                <div class="text-danger">{{ $message }}</div>
            @enderror
        </div>

        {{-- Tombol --}}
        <button type="submit" class="btn btn-success">Simpan Varian</button>
        <a href="{{ route('admin.variants.index', $product->id) }}" class="btn btn-secondary">Kembali</a>
    </form>
</div>
@endsection
