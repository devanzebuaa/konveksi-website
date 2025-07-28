@extends('layouts.app')

@section('title', 'Galeri Karya Sablon')

@section('content')
<section class="py-5 px-4">
    <div class="text-center mb-5">
        <h2 class="fw-bold">Galeri Tempat Produksi</h2>
        <p class="text-muted">Beberapa Galeri Tempat Produksi Konveksi Kami</p>
    </div>

    <div class="row flex-nowrap justify-content-center" style="overflow-x:auto; gap: 4px;">
        @foreach ($galleries as $gallery)
        <div class="col-md-4 d-flex justify-content-center flex-shrink-0" style="margin-bottom: 0; min-width: 480px; max-width: 520px; padding-left: 0; padding-right: 0;">
            <div style="background: #fff; border-radius: 14px; box-shadow: 0 4px 16px rgba(60,60,60,0.08); padding: 12px 12px 8px 12px; display: flex; flex-direction: column; align-items: center; max-width: 520px; margin: 0;">
                <div style="width: 440px; height: 440px; max-width: 100%; max-height: 100%; overflow: hidden; border-radius: 12px; border: 6px solid #fff; box-shadow: 0 2px 8px rgba(60,60,60,0.08); margin-bottom: 10px; display: flex; align-items: center; justify-content: center;">
                    <img src="{{ asset('images/galleries/' . $gallery->image) }}"
                         alt="{{ $gallery->title }}"
                         style="width: 100%; height: 100%; object-fit: cover; object-position: center;">
                </div>
                <h5 class="fw-bold mb-1" style="font-size: 1.18rem;">{{ $gallery->title }}</h5>
                <p class="text-muted mb-0" style="font-size: 1.04rem; text-align: center;">{{ $gallery->description }}</p>
            </div>
        </div>
        @endforeach
    </div>

    <div class="mt-4 d-flex justify-content-center">
        {{ $galleries->links() }}
    </div>
</section>
@endsection