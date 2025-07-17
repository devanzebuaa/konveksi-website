@extends('layouts.admin')

@section('title', 'Kelola Varian - ' . $product->name)

@section('content')
<div class="container mt-4">
    <h4>Kelola Varian Produk: <strong>{{ $product->name }}</strong></h4>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <div class="d-flex justify-content-between align-items-center mb-3">
        <a href="{{ route('admin.products.index') }}" class="btn btn-secondary">← Kembali ke Daftar Produk</a>
        <a href="{{ route('admin.variants.create', $product->id) }}" class="btn btn-primary">+ Tambah Varian</a>
    </div>

    @if($variants->isEmpty())
        <div class="alert alert-info">Belum ada varian untuk produk ini.</div>
    @else
        <div class="table-responsive">
            <table class="table table-striped align-middle">
                <thead class="table-light">
                    <tr>
                        <th>Gambar</th>
                        <th>Warna</th>
                        <th>Stok</th>
                        <th>Terakhir Diupdate</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($variants as $variant)
                    <tr>
                        <td>
                            @if($variant->image)
                                <img src="{{ asset('storage/' . $variant->image) }}" width="70" class="img-thumbnail" alt="Varian">
                            @else
                                <span class="text-muted">Tidak ada gambar</span>
                            @endif
                        </td>
                        <td>{{ $variant->color }}</td>
                        <td>{{ $variant->stock }}</td>
                        <td>{{ $variant->updated_at->format('d M Y H:i') }}</td>
                        <td>
                            <a href="{{ route('admin.variants.edit', [$product->id, $variant->id]) }}" class="btn btn-sm btn-warning">Edit</a>
                            <form action="{{ route('admin.variants.destroy', [$product->id, $variant->id]) }}" method="POST" class="d-inline" onsubmit="return confirm('Yakin hapus varian ini?')">
                                @csrf
                                @method('DELETE')
                                <button class="btn btn-sm btn-danger">Hapus</button>
                            </form>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    @endif
</div>
@endsection
