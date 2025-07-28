@extends('layouts.admin')

@section('title', 'Detail Order #' . $order->id)

@section('content')
<div class="container my-5">
    <h2 class="mb-4">📄 Detail Order #{{ $order->id }}</h2>

    <div class="card shadow-sm mb-4">
        <div class="card-body">
            {{-- Informasi Pemesan --}}
            <p><strong>Pemesan:</strong> {{ optional($order->user)->name ?? '-' }}</p>
            <p><strong>Alamat Pengiriman:</strong> {{ $order->address }}</p>
            <p><strong>Produk:</strong> {{ optional($order->product)->name ?? '-' }}</p>
            <p><strong>Varian/Warna:</strong> {{ ucfirst($order->warna) }}</p>
            <p><strong>Ukuran:</strong> {{ $order->ukuran }}</p>
            <p><strong>Jumlah:</strong> {{ $order->jumlah }}</p>

            {{-- Harga Berdasarkan Ukuran --}}
            @php
                $hargaUkuran = optional(
                    \App\Models\ProductSizePrice::where('product_id', $order->product_id)
                        ->where('size', $order->ukuran)
                        ->first()
                )->price;
            @endphp

            <p><strong>Harga per Ukuran:</strong> 
                Rp{{ number_format($hargaUkuran ?? 0, 0, ',', '.') }}
            </p>

            <p><strong>Total Dihitung (Ukuran × Jumlah):</strong> 
                Rp{{ number_format(($hargaUkuran ?? 0) * $order->jumlah, 0, ',', '.') }}
            </p>

            <p><strong>Total Tersimpan (Database):</strong> 
                Rp{{ number_format($order->total_harga, 0, ',', '.') }}
            </p>

            <p><strong>Metode Pembayaran:</strong> {{ ucfirst($order->payment_method) }}</p>

            @if($order->bank_name)
                <p><strong>Bank:</strong> {{ strtoupper($order->bank_name) }}</p>
            @endif

            @if($order->wallet_type)
                <p><strong>E-Wallet:</strong> {{ ucfirst($order->wallet_type) }}</p>
            @endif

            {{-- Status --}}
            <p><strong>Status Saat Ini:</strong> {!! $order->status_badge !!}</p>

            {{-- Bukti Pembayaran --}}
            @if($order->payment_proof)
                <p><strong>Bukti Transfer:</strong></p>
                <img src="{{ asset('storage/' . $order->payment_proof) }}" alt="Bukti Transfer" class="img-fluid rounded shadow" style="max-width: 300px;">
            @endif

            {{-- Tombol Lihat Invoice --}}
            @if($order->invoice_path)
                <p class="mt-3">
                    <a href="{{ asset('storage/' . $order->invoice_path) }}" target="_blank" class="btn btn-outline-primary">
                        📄 Lihat Invoice
                    </a>
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
                <button type="submit" class="btn btn-success">✅ Update Status</button>
            </form>

            {{-- Form Hapus Order --}}
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
