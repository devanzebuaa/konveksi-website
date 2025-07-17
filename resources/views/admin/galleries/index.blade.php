@extends('layouts.admin')

@section('title', 'Galeri')

@section('content')
<div class="container py-4">
    <h3 class="mb-4 fw-bold">Galeri</h3>

    {{-- Tombol Tambah --}}
    <a href="{{ route('admin.galleries.create') }}" class="btn btn-primary mb-3">
        <i class="fas fa-plus"></i> Tambah Gambar
    </a>

    {{-- Notifikasi --}}
    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    {{-- Daftar Galeri --}}
    @if($galleries->count())
        <div class="row">
            @foreach($galleries as $gallery)
                <div class="col-md-4 mb-4">
                    <div class="card shadow-sm h-100 border-0">
                        {{-- Gambar --}}
                        <img src="{{ asset('images/galleries/' . $gallery->image) }}"
                             alt="{{ $gallery->title }}"
                             class="card-img-top"
                             style="height: 250px; object-fit: cover;">

                        {{-- Konten --}}
                        <div class="card-body">
                            <h5 class="card-title fw-semibold">{{ $gallery->title }}</h5>
                            <p class="card-text text-muted">{{ $gallery->description }}</p>

                            {{-- Tombol Aksi --}}
                            <div class="d-flex gap-2">
                                <a href="{{ route('admin.galleries.edit', $gallery->id) }}" class="btn btn-sm btn-warning">Edit</a>
                                <form action="{{ route('admin.galleries.destroy', $gallery->id) }}" method="POST" onsubmit="return confirm('Yakin ingin menghapus gambar ini?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-danger">Hapus</button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        {{-- Pagination --}}
        <div class="mt-4 d-flex justify-content-center">
            {{ $galleries->links() }}
        </div>
    @else
        <div class="alert alert-info">Belum ada gambar di galeri.</div>
    @endif

    {{-- Tombol Kembali --}}
    <div class="mt-4">
        <a href="{{ route('admin.dashboard') }}" class="btn btn-secondary">
            <i class="fas fa-arrow-left"></i> Kembali ke Dashboard
        </a>
    </div>
</div>
@endsection
