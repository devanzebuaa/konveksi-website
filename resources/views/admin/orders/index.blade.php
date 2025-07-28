@extends('layouts.admin')

@section('title', 'Manajemen Order')

@section('content')
<div class="container my-5">
    <h2 class="mb-4">📦 Semua Order Masuk</h2>

    <a href="{{ route('admin.dashboard') }}" class="btn btn-outline-secondary mb-4">
        ⬅ Kembali ke Dashboard
    </a>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    @if($orders->isEmpty())
        <div class="alert alert-info text-center">Belum ada order yang masuk.</div>
    @else
    <div class="table-responsive shadow-sm">
        <table class="table table-hover align-middle text-center table-bordered bg-white">
            <thead class="table-dark text-nowrap">
                <tr>
                    <th>ID</th>
                    <th>Produk</th>
                    <th>Pemesan</th>
                    <th>Warna</th>
                    <th>Ukuran</th>
                    <th>Jumlah</th>
                    <th>Total</th>
                    <th>Alamat</th>
                    <th>Status</th>
                    <th>Bukti</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                @php $totalPendapatan = 0; @endphp

                @foreach($orders as $order)
                    @php
                        $hargaUkuran = optional(
                            \App\Models\ProductSizePrice::where('product_id', $order->product_id)
                                ->where('size', $order->ukuran)
                                ->first()
                        )->price;

                        $totalHarga = $hargaUkuran ? $hargaUkuran * $order->jumlah : $order->total_harga;
                        $totalPendapatan += $totalHarga;
                    @endphp

                    <tr>
                        <td><strong>#{{ $order->id }}</strong></td>
                        <td>{{ $order->product->name ?? '-' }}</td>
                        <td>{{ $order->user->name ?? '-' }}</td>
                        <td>{{ ucfirst($order->warna) }}</td>
                        <td>{{ $order->ukuran }}</td>
                        <td>{{ $order->jumlah }}</td>
                        <td>Rp{{ number_format($totalHarga, 0, ',', '.') }}</td>
                        <td>{{ $order->address }}</td>
                        <td>{!! $order->status_badge !!}</td>
                        <td>
                            @if($order->payment_proof)
                                <a href="{{ asset('storage/' . $order->payment_proof) }}" target="_blank" class="btn btn-sm btn-outline-success">
                                    Lihat
                                </a>
                            @else
                                <span class="text-muted">-</span>
                            @endif
                        </td>
                        <td>
                            <div class="d-flex flex-column gap-2 justify-content-center align-items-center">
                                <a href="{{ route('admin.orders.show', $order->id) }}" class="btn btn-sm btn-outline-primary w-100">
                                    <i class="fas fa-eye"></i> Detail
                                </a>
                                <form action="{{ route('admin.orders.destroy', $order->id) }}" method="POST" onsubmit="return confirm('Yakin ingin menghapus order ini?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-outline-danger w-100">
                                        <i class="fas fa-trash-alt"></i> Hapus
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    {{-- TOTAL PENDAPATAN --}}
    <div class="mt-4 alert alert-success text-center fs-5">
        💰 <strong>Total Pendapatan:</strong> Rp{{ number_format($totalPendapatan, 0, ',', '.') }}
    </div>
    @endif
</div>
@endsection
