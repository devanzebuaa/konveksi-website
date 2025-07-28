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
                    <th>Jumlah</th>
                    <th>Status</th>
                    <th>Total Harga</th>
                    <th>Gambar Varian</th>
                    <th>Invoice</th>
                </tr>
            </thead>
            <tbody>
                @foreach($orders as $order)
                    @php
                        $variant = $order->variant;
                        $image = $variant?->image ?? 'images/products/default.jpg';

                        // Ambil harga ukuran langsung dari relasi sizePrice (tidak pakai variant_id)
                        $hargaUkuran = $order->sizePrice?->price ?? 0;

                        // Hitung total harga
                        $totalHarga = $hargaUkuran * $order->jumlah;
                    @endphp
                    <tr>
                        <td>{{ $order->product->name }}</td>
                        <td>{{ ucfirst($order->warna) }}</td>
                        <td>{{ $order->ukuran }}</td>
                        <td>{{ $order->jumlah }}</td>
                        <td>
                            {!! $order->status_badge !!}
                        </td>
                        <td>
                            @if($totalHarga == 0)
                                <span class="text-danger fw-bold">Rp0 ⚠️</span>
                            @else
                                Rp{{ number_format($totalHarga, 0, ',', '.') }}
                            @endif
                        </td>
                        <td>
                            <img src="{{ asset('storage/' . $image) }}"
                                 alt="Varian Gambar"
                                 class="img-thumbnail rounded"
                                 style="height: 60px; object-fit: cover;">
                        </td>
                        <td>
                            @if($order->invoice_path)
                                <a href="{{ asset('storage/' . $order->invoice_path) }}"
                                   class="btn btn-sm btn-outline-primary"
                                   target="_blank">
                                    📄 Lihat Invoice
                                </a>
                            @else
                                <span class="text-muted">-</span>
                            @endif
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    @php
        $totalItem = $orders->sum('jumlah');
    @endphp

    <div class="mt-4 alert alert-primary text-center">
        🧾 <strong>Total Semua Item Dibeli:</strong> {{ $totalItem }} pcs
    </div>
    @endif
</div>
@endsection
