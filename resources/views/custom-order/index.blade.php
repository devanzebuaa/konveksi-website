@extends('layouts.admin')

@section('title', 'Pesanan Custom')

@section('content')
<div class="container py-4">
    <h2 class="mb-4 fw-bold">📦 Daftar Pesanan Custom</h2>

    @if($orders->count())
        <div class="table-responsive">
            <table class="table table-bordered align-middle">
                <thead>
                    <tr>
                        <th>Nama</th>
                        <th>Produk</th>
                        <th>Jumlah</th>
                        <th>Kontak</th>
                        <th>Waktu</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($orders as $order)
                    <tr>
                        <td>{{ $order->name }}</td>
                        <td>{{ $order->product_type }}</td>
                        <td>{{ $order->quantity }}</td>
                        <td>{{ $order->contact }}</td>
                        <td>{{ $order->created_at->format('d/m/Y') }}</td>
                        <td>
                            <a href="{{ route('admin.custom-orders.show', $order->id) }}" class="btn btn-sm btn-outline-primary">Detail</a>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        {{ $orders->links() }}
    @else
        <div class="alert alert-info">Belum ada pesanan custom.</div>
    @endif
</div>
@endsection
