@extends('layouts.app')

@section('title', 'Belanjaanku')

@section('content')
<div class="container py-5">
    <h2 class="mb-4 fw-bold">🛍️ Belanjaanku</h2>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    @if($orders->isEmpty())
        <div class="alert alert-info">Kamu belum memiliki pesanan.</div>
    @else
    <div class="table-responsive">
        <table class="table table-bordered table-striped align-middle text-center">
            <thead class="table-dark">
                <tr>
                    <th>Produk</th>
                    <th>Warna</th>
                    <th>Ukuran</th>
                    <th>Status</th>
                    <th>Total Harga</th>
                    <th>Gambar Varian</th>
                </tr>
            </thead>
            <tbody>
                @foreach($orders as $order)
                    @php
                        $image = $order->variant->image ?? 'images/products/default.jpg';
                    @endphp
                    <tr>
                        <td>{{ $order->product->name }}</td>
                        <td>{{ ucfirst($order->warna) }}</td>
                        <td>{{ $order->ukuran }}</td>
                        <td>
                            <span class="badge
                                @if($order->status === 'Menunggu Pembayaran') bg-warning text-dark
                                @elseif($order->status === 'Sudah Dibayar') bg-success
                                @elseif($order->status === 'Diproses') bg-info text-dark
                                @elseif($order->status === 'Dikirim') bg-primary
                                @elseif($order->status === 'Selesai') bg-secondary
                                @elseif($order->status === 'Dibatalkan') bg-danger
                                @else bg-dark @endif">
                                {{ $order->status }}
                            </span>
                        </td>
                        <td>Rp{{ number_format($order->total_harga, 0, ',', '.') }}</td>
                        <td>
                            <img src="{{ asset('storage/' . $image) }}"
                                 alt="Varian Gambar"
                                 class="img-thumbnail rounded"
                                 style="height: 60px; object-fit: cover;">
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    @endif
</div>
@endsection
