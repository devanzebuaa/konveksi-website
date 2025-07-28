@extends('layouts.admin')

@section('title', 'Kelola Custom Order')

@section('content')
<div class="container py-4">
    <h3 class="fw-bold mb-4"><i class="fas fa-box text-purple"></i> Daftar Custom Order</h3>

    {{-- Tombol Kembali ke Dashboard --}}
    <a href="{{ route('admin.dashboard') }}" class="btn btn-sm btn-secondary mb-3">
        <i class="fas fa-arrow-left"></i> Kembali ke Dashboard
    </a>

    @if (session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    @if ($orders->count())
        <div class="table-responsive">
            <table class="table table-bordered table-hover align-middle">
                <thead class="table-light">
                    <tr>
                        <th>#</th>
                        <th>Nama</th>
                        <th>Produk</th>
                        <th>Jumlah Pesanan</th>
                        <th>Kontak</th>
                        <th>Deskripsi</th>
                        <th>File Desain</th>
                        <th>Chat</th>
                        <th>Hapus</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($orders as $i => $order)
                        <tr>
                            <td>{{ $orders->firstItem() + $i }}</td>
                            <td>{{ $order->name }}</td>
                            <td>{{ $order->product_type }}</td>
                            <td>{{ $order->quantity }}</td>
                            <td>{{ $order->contact }}</td>
                            <td>
                                {{ $order->description ?? '-' }}
                            </td>
                            <td>
                                @if ($order->design_file)
                                    <a href="{{ asset('storage/' . $order->design_file) }}" target="_blank" class="btn btn-sm btn-outline-secondary">
                                        Lihat
                                    </a>
                                @else
                                    <span class="text-muted">Tidak ada</span>
                                @endif
                            </td>
                            <td>
                                <a href="{{ route('admin.custom-orders.chat', $order->id) }}" class="btn btn-sm btn-outline-primary w-100" title="Lihat Chat">
                                    <i class="fas fa-comments"></i> Chat
                                </a>
                            </td>
                            <td>
                                <form action="{{ route('admin.custom-orders.destroy', $order->id) }}" method="POST" onsubmit="return confirm('Yakin ingin menghapus pesanan ini?')">
                                    @csrf
                                    @method('DELETE')
                                    <button class="btn btn-sm btn-danger w-100">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        {{-- Pagination --}}
        <div class="mt-3">
            {{ $orders->links() }}
        </div>
    @else
        <div class="alert alert-info">
            Belum ada pesanan custom.
        </div>
    @endif
</div>
@endsection