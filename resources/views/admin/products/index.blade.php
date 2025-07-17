@extends('layouts.admin')

@section('title', 'Kelola Produk')

@section('content')
<div class="container py-4">
    <h3 class="mb-4 fw-bold">Kelola Produk</h3>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <a href="{{ route('admin.products.create') }}" class="btn btn-success mb-3">+ Tambah Produk</a>

    <table class="table table-bordered table-hover align-middle">
        <thead class="table-light">
            <tr>
                <th>Gambar</th>
                <th>Nama</th>
                <th>Kategori</th>
                <th>Harga</th>
                <th>Unggulan</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
        @foreach ($products as $product)
            <tr>
                <td>
                    <img src="{{ asset('images/products/' . $product->image) }}"
                         alt="{{ $product->name }}"
                         width="80" height="80"
                         style="object-fit: cover; border-radius: 8px;">
                </td>
                <td>{{ $product->name }}</td>
                <td>{{ $product->category }}</td>
                <td>Rp {{ number_format($product->price, 0, ',', '.') }}</td>

                {{-- ✅ Kolom Unggulan --}}
                <td class="text-center">
                    @if ($product->is_featured)
                        <form action="{{ route('admin.products.toggle-featured', $product->id) }}" method="POST">
                            @csrf
                            @method('PATCH')
                            <button class="btn btn-sm btn-warning">Hapus</button>
                        </form>
                    @else
                        <form action="{{ route('admin.products.toggle-featured', $product->id) }}" method="POST">
                            @csrf
                            @method('PATCH')
                            <button class="btn btn-sm btn-outline-primary">Unggulkan</button>
                        </form>
                    @endif
                </td>

                {{-- Aksi Edit & Hapus --}}
                <td>
                    <a href="{{ route('admin.products.edit', $product->id) }}" class="btn btn-sm btn-primary">Edit</a>
                    <form action="{{ route('admin.products.destroy', $product->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Hapus produk ini?')">
                        @csrf
                        @method('DELETE')
                        <button class="btn btn-sm btn-danger">Hapus</button>
                    </form>
                </td>
            </tr>
        @endforeach
        </tbody>
    </table>

    {{ $products->links() }}

    <div class="mt-4">
        <a href="{{ route('admin.dashboard') }}" class="btn btn-secondary">← Kembali ke Dashboard</a>
    </div>
</div>
@endsection
