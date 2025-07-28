@extends('layouts.admin')

@section('title', 'Edit Varian - ' . $product->name)

@section('content')
<div class="container mt-4">
    <h3>Edit Varian untuk: <strong>{{ $product->name }}</strong></h3>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <form action="{{ route('admin.variants.update', [$product->id, $variant->id]) }}" method="POST" enctype="multipart/form-data" class="mt-3">
        @csrf
        @method('PUT')

        {{-- Warna --}}
        <div class="mb-3">
            <label for="color" class="form-label">Warna</label>
            <input type="text" name="color" id="color" class="form-control" value="{{ old('color', $variant->color) }}" required>
            @error('color')
                <div class="text-danger">{{ $message }}</div>
            @enderror
        </div>

        {{-- Harga --}}
        <div class="mb-3">
            <label for="price" class="form-label">Harga</label>
            <input type="number" name="price" id="price" class="form-control" min="0" value="{{ old('price', $variant->price) }}" required>
            @error('price')
                <div class="text-danger">{{ $message }}</div>
            @enderror
        </div>

        {{-- Gambar Varian --}}
        <div class="mb-3">
            <label for="image" class="form-label">Gambar (Opsional, isi jika ingin ganti)</label>
            <input type="file" name="image" id="image" class="form-control">
            @if($variant->image)
                <small class="d-block mt-2">Gambar saat ini:</small>
                <img src="{{ asset('storage/' . $variant->image) }}" alt="Varian Gambar" width="150" class="img-thumbnail">
            @endif
            @error('image')
                <div class="text-danger">{{ $message }}</div>
            @enderror
        </div>

        {{-- Stok --}}
        <div class="mb-3">
            <label for="stock" class="form-label">Stok</label>
            <input type="number" name="stock" id="stock" class="form-control" min="0" value="{{ old('stock', $variant->stock) }}" required>
            @error('stock')
                <div class="text-danger">{{ $message }}</div>
            @enderror
        </div>

        <button type="submit" class="btn btn-primary">Update Varian</button>
        <a href="{{ route('admin.variants.index', $product->id) }}" class="btn btn-secondary">Kembali</a>
    </form>
</div>
@endsection
