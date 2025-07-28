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