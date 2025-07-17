@extends('layouts.app')

@section('title', 'Testimoni')

@section('content')
<div class="container py-5">
    <h2 class="mb-4">Testimoni Pelanggan</h2>

    {{-- ✅ Form hanya tampil jika user bukan admin --}}
    @auth
        @if (!Auth::user()->is_admin)
            <div class="card mb-4">
                <div class="card-header">Tulis Testimoni Anda</div>
                <div class="card-body">
                    <form action="{{ route('testimonials.store') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div class="mb-3">
                            <label for="name" class="form-label">Nama Anda</label>
                            <input type="text" name="name" id="name" class="form-control" required>
                        </div>

                        <div class="mb-3">
                            <label for="rating" class="form-label">Rating (1-5)</label>
                            <select name="rating" id="rating" class="form-select" required>
                                @for ($i = 1; $i <= 5; $i++)
                                    <option value="{{ $i }}">{{ $i }} ⭐</option>
                                @endfor
                            </select>
                        </div>

                        <div class="mb-3">
                            <label for="comment" class="form-label">Komentar</label>
                            <textarea name="comment" id="comment" class="form-control" rows="3" required></textarea>
                        </div>

                        <div class="mb-3">
                            <label for="photo" class="form-label">Foto (opsional)</label>
                            <input type="file" name="photo" id="photo" class="form-control">
                        </div>

                        <button type="submit" class="btn btn-primary">Kirim Testimoni</button>
                    </form>
                </div>
            </div>
        @endif
    @endauth

    {{-- ✅ List Testimoni --}}
    <div class="row">
        @forelse ($testimonials as $testimonial)
            <div class="col-md-4 mb-4">
                <div class="card h-100">
                    @if ($testimonial->photo)
                        <img src="{{ asset('storage/' . $testimonial->photo) }}" class="card-img-top" style="height: 200px; object-fit: cover;">
                    @endif
                    <div class="card-body">
                        <h5>{{ $testimonial->name }}</h5>
                        <p>
                            @for ($i = 1; $i <= 5; $i++)
                                @if ($i <= $testimonial->rating)
                                    ⭐
                                @else
                                    ☆
                                @endif
                            @endfor
                        </p>
                        <p>{{ $testimonial->comment }}</p>
                    </div>
                </div>
            </div>
        @empty
            <p>Belum ada testimoni.</p>
        @endforelse
    </div>
</div>
@endsection
