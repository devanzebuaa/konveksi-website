@extends('layouts.admin')

@section('title', 'Detail Order #'.$order->id)

@section('content')
<div class="container my-5">
    <h2 class="mb-4">📄 Detail Order #{{ $order->id }}</h2>

    <div class="card mb-4">
        <div class="card-body">
            <p><strong>Pemesan:</strong> {{ $order->user->name }}</p>
            <p><strong>Alamat Pengiriman:</strong> {{ $order->address }}</p>
            <p><strong>Produk:</strong> {{ $order->product->name }}</p>
            <p><strong>Warna:</strong> {{ ucfirst($order->warna) }}</p>
            <p><strong>Ukuran:</strong> {{ $order->ukuran }}</p>
            <p><strong>Jumlah:</strong> {{ $order->jumlah }}</p>
            <p><strong>Total:</strong> Rp {{ number_format($order->total_harga, 0, ',', '.') }}</p>
            <p><strong>Metode Pembayaran:</strong> {{ ucfirst($order->payment_method) }}</p>

            @if($order->bank_name)
                <p><strong>Bank:</strong> {{ strtoupper($order->bank_name) }}</p>
            @endif

            @if($order->wallet_type)
                <p><strong>E-Wallet:</strong> {{ $order->wallet_type }}</p>
            @endif

            <p><strong>Status Saat Ini:</strong>
                <span class="badge
                    @if($order->status === 'Menunggu Pembayaran') bg-warning
                    @elseif($order->status === 'Sudah Dibayar') bg-success
                    @elseif($order->status === 'Diproses') bg-primary
                    @elseif($order->status === 'Dikirim') bg-info
                    @elseif($order->status === 'Selesai') bg-secondary
                    @elseif($order->status === 'Dibatalkan') bg-danger
                    @endif">
                    {{ $order->status }}
                </span>
            </p>

            @if($order->payment_proof)
                <p><strong>Bukti Transfer:</strong><br>
                    <img src="{{ asset('storage/' . $order->payment_proof) }}" alt="Bukti Transfer" style="max-width: 300px;" class="img-fluid rounded shadow">
                </p>
            @endif

            {{-- Form Update Status --}}
            <form action="{{ route('admin.orders.updateStatus', $order->id) }}" method="POST" class="mt-4">
                @csrf
                @method('PATCH')
                <div class="mb-3">
                    <label for="status" class="form-label">Update Status</label>
                    <select name="status" id="status" class="form-select" required>
                        <option value="">-- Pilih Status --</option>
                        @foreach(['Menunggu Pembayaran', 'Sudah Dibayar', 'Diproses', 'Dikirim', 'Selesai', 'Dibatalkan'] as $status)
                            <option value="{{ $status }}" {{ $order->status === $status ? 'selected' : '' }}>
                                {{ $status }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <button type="submit" class="btn btn-success">Update Status</button>
            </form>

            {{-- Tombol Hapus Order --}}
            <form action="{{ route('admin.orders.destroy', $order->id) }}" method="POST" class="mt-3" onsubmit="return confirm('Yakin ingin menghapus order ini?')">
                @csrf
                @method('DELETE')
                <button type="submit" class="btn btn-danger">🗑 Hapus Order</button>
            </form>
        </div>
    </div>

    <a href="{{ route('admin.orders.index') }}" class="btn btn-secondary">
        ⬅ Kembali ke Daftar Order
    </a>
</div>
@endsection
