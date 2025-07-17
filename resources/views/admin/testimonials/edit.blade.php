@extends('layouts.admin')

@section('content')
<div class="container py-4">
    <h2 class="mb-4 fw-bold">Edit Testimoni</h2>

    {{-- Notifikasi error --}}
    @if ($errors->any())
        <div class="alert alert-danger">
            <ul class="mb-0">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('admin.testimonials.update', $testimonial->id) }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')

        <div class="mb-3">
            <label for="name" class="form-label">Nama</label>
            <input type="text" name="name" id="name" class="form-control" value="{{ $testimonial->name }}" required>
        </div>

        <div class="mb-3">
            <label for="rating" class="form-label">Rating</label>
            <select name="rating" id="rating" class="form-select" required>
                @for ($i = 1; $i <= 5; $i++)
                    <option value="{{ $i }}" {{ $testimonial->rating == $i ? 'selected' : '' }}>{{ $i }} ⭐</option>
                @endfor
            </select>
        </div>

        <div class="mb-3">
            <label for="comment" class="form-label">Komentar</label>
            <textarea name="comment" id="comment" class="form-control" rows="3" required>{{ $testimonial->comment }}</textarea>
        </div>

        <div class="mb-3">
            <label for="photo" class="form-label">Ganti Foto (opsional)</label>
            <input type="file" name="photo" id="photo" class="form-control">
            @if ($testimonial->photo)
                <div class="mt-2">
                    <img src="{{ asset('storage/' . $testimonial->photo) }}" alt="Foto Sekarang" width="100" class="rounded">
                </div>
            @endif
        </div>

        <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
        <a href="{{ route('admin.testimonials.index') }}" class="btn btn-secondary">Batal</a>
    </form>
</div>
@endsection
