@extends('layouts.app')

@section('title', 'Galeri Karya Sablon')

@section('content')
<section class="py-5 px-4">
    <div class="text-center mb-5">
        <h2 class="fw-bold">Galeri Dinara Konveksi</h2>
        <p class="text-muted">Hasil Produksi</p>
    </div>

    <div class="row g-4">
        @foreach ($galleries as $gallery)
        <div class="col-md-4">
            <div class="card shadow-sm border-0 h-100 overflow-hidden">
                <a href="#" data-bs-toggle="modal" data-bs-target="#modal{{ $gallery->id }}">
                    <div class="zoom-container">
                        <img src="{{ asset('images/galleries/' . $gallery->image) }}"
                             class="card-img-top zoom-img"
                             alt="{{ $gallery->title }}">
                    </div>
                </a>
                <div class="card-body">
                    <h5 class="card-title">{{ $gallery->title }}</h5>
                    <p class="card-text">{{ $gallery->description }}</p>
                </div>
            </div>
        </div>

        <!-- Modal -->
        <div class="modal fade" id="modal{{ $gallery->id }}" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered modal-lg">
                <div class="modal-content bg-transparent border-0">
                    <img src="{{ asset('images/galleries/' . $gallery->image) }}" class="img-fluid rounded shadow" alt="{{ $gallery->title }}">
                </div>
            </div>
        </div>
        @endforeach
    </div>

    <div class="mt-4 d-flex justify-content-center">
        {{ $galleries->links() }}
    </div>
</section>
@endsection
