@extends('layouts.admin')

@section('title', 'Detail Pesanan Custom')

@section('content')
<div class="container py-4">
    <h2 class="fw-bold mb-4">📄 Detail Pesanan</h2>

    <div class="card p-4">
        <p><strong>Nama:</strong> {{ $order->name }}</p>
        <p><strong>Jenis Produk:</strong> {{ $order->product_type }}</p>
        <p><strong>Ukuran / Detail:</strong> {{ $order->size_detail }}</p>
        <p><strong>Deskripsi:</strong> {{ $order->description }}</p>
        <p><strong>Jumlah:</strong> {{ $order->quantity }}</p>
        <p><strong>Metode Pembayaran:</strong> {{ $order->payment_method }}</p>
        <p><strong>Kontak:</strong> {{ $order->contact }}</p>

        @if($order->design_file)
            <p><strong>Desain:</strong><br>
                <a href="{{ asset('storage/' . $order->design_file) }}" target="_blank">
                    <img src="{{ asset('storage/' . $order->design_file) }}" style="max-width: 200px;" class="img-thumbnail">
                </a>
            </p>
        @endif

        <a href="{{ route('admin.custom-orders.index') }}" class="btn btn-secondary mt-3">⬅️ Kembali</a>
    </div>
</div>
@endsection
