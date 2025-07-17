@extends('layouts.admin')

@section('title', 'Edit Gambar Galeri')

@section('content')
<div class="container py-4">
    <h3 class="mb-4 fw-bold">Edit Gambar Galeri</h3>

    <form action="{{ route('admin.galleries.update', $gallery->id) }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')

        {{-- Judul --}}
        <div class="mb-3">
            <label for="title" class="form-label">Judul</label>
            <input type="text"
                   class="form-control @error('title') is-invalid @enderror"
                   id="title" name="title"
                   value="{{ old('title', $gallery->title) }}"
                   required>
            @error('title')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        {{-- Deskripsi --}}
        <div class="mb-3">
            <label for="description" class="form-label">Deskripsi</label>
            <textarea class="form-control @error('description') is-invalid @enderror"
                      id="description" name="description"
                      rows="4"
                      required>{{ old('description', $gallery->description) }}</textarea>
            @error('description')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        {{-- Gambar Saat Ini --}}
        <div class="mb-3">
            <label class="form-label">Gambar Saat Ini:</label><br>
            <img src="{{ asset('images/galleries/' . $gallery->image) }}"
                 class="img-thumbnail mb-2"
                 width="200" alt="Current Image">
        </div>

        {{-- Input Gambar Baru --}}
        <div class="mb-3">
            <label for="image" class="form-label">Ganti Gambar (Opsional)</label>
            <input type="file"
                   class="form-control @error('image') is-invalid @enderror"
                   id="image" name="image"
                   accept=".jpg,.jpeg,.png,.webp">
            @error('image')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        {{-- Tombol --}}
        <button type="submit" class="btn btn-primary">Update</button>
        <a href="{{ route('admin.galleries.index') }}" class="btn btn-secondary">Kembali</a>
    </form>
</div>
@endsection
