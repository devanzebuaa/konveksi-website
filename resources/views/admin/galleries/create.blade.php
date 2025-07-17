@extends('layouts.admin')

@section('title', 'Tambah Gambar Galeri')

@section('content')
<div class="container py-4">
    <h3 class="mb-4 fw-bold">Tambah Gambar Galeri</h3>

    <form action="{{ route('admin.galleries.store') }}" method="POST" enctype="multipart/form-data">
        @csrf

        {{-- Input Judul --}}
        <div class="mb-3">
            <label for="title" class="form-label">Judul</label>
            <input type="text"
                   class="form-control @error('title') is-invalid @enderror"
                   id="title" name="title"
                   value="{{ old('title') }}"
                   required>
            @error('title')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        {{-- Input Gambar --}}
        <div class="mb-3">
            <label for="image" class="form-label">Pilih Gambar</label>
            <input type="file"
                   class="form-control @error('image') is-invalid @enderror"
                   id="image" name="image"
                   accept=".jpg,.jpeg,.png,.webp"
                   required>
            @error('image')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        {{-- Input Deskripsi --}}
        <div class="mb-3">
            <label for="description" class="form-label">Deskripsi</label>
            <textarea class="form-control @error('description') is-invalid @enderror"
                      id="description" name="description" rows="4"
                      required>{{ old('description') }}</textarea>
            @error('description')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        {{-- Tombol --}}
        <button type="submit" class="btn btn-primary">Simpan</button>
        <a href="{{ route('admin.galleries.index') }}" class="btn btn-secondary">Batal</a>
    </form>
</div>
@endsection
