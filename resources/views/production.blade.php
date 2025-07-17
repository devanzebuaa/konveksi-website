@extends('layouts.app')

@section('title', 'Proses Produksi')

@section('content')
<!-- Production Process -->
<section class="py-5">
    <div class="container">
        <div class="text-center mb-5">
            <h1 class="fw-bold">Proses Produksi Kami</h1>
            <p class="lead">Dari Bahan Mentah Hingga Produk Jadi Dengan Kualitas Terbaik</p>
        </div>
        
        <div class="row">
            @foreach($steps as $step)
            <div class="col-md-4 mb-4">
                <div class="card h-100 shadow-sm">
                    <div class="card-body text-center">
                        <div class="step-number bg-primary text-white rounded-circle d-inline-flex align-items-center justify-content-center mb-3">
                            {{ $loop->iteration }}
                        </div>
                        <h4>{{ $step['title'] }}</h4>
                        <p>{{ $step['desc'] }}</p>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
        
        <div class="row mt-5">
            <div class="col-lg-6 mb-4 mb-lg-0">
                <div class="card shadow-sm">
                    <div class="card-body">
                        <h3 class="fw-bold mb-4">Fasilitas Produksi</h3>
                        <p>Kami Memiliki Fasilitas Produksi Modern Dengan Mesin-Mesin Terkini Untuk Memastikan Kualitas Dan Konsistensi Produk.</p>
                        <ul>
                            <li>Mesin Jahit High-Speed</li>
                            <li>Mesin Obras Otomatis</li>
                            <li>Mesin Cutting Digital</li>
                            <li>Quality Control Station</li>
                        </ul>
                    </div>
                </div>
            </div>
            
            <div class="col-lg-6">
                <div class="card shadow-sm">
                    <div class="card-body">
                        <h3 class="fw-bold mb-4">Standar Kualitas</h3>
                        <p>Setiap produk melewati pemeriksaan ketat sebelum dikirim ke pelanggan:</p>
                        <div class="progress mb-3">
                            <div class="progress-bar bg-success" role="progressbar" style="width: 95%">95% Lulus QC</div>
                        </div>
                        <div class="progress mb-3">
                            <div class="progress-bar bg-info" role="progressbar" style="width: 98%">98% Ketepatan Waktu</div>
                        </div>
                        <div class="progress">
                            <div class="progress-bar bg-primary" role="progressbar" style="width: 99%">99% Kepuasan Pelanggan</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection

@section('styles')
<style>
    .step-number {
        width: 50px;
        height: 50px;
        font-size: 1.5rem;
        font-weight: bold;
    }
</style>
@endsection

@section('scripts')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/lightbox2/2.11.3/css/lightbox.min.css">
<script src="https://cdnjs.cloudflare.com/ajax/libs/lightbox2/2.11.3/js/lightbox.min.js"></script>
@endsection