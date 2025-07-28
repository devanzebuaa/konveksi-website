@extends('layouts.app')

@section('title', 'Pesanan Custom')

@section('content')

<div class="container py-5">
    <div class="card shadow-sm p-4 border-0 rounded-4 mb-5">
        <h2 class="mb-4 text-center fw-bold">🧵 Form Pesanan Custom</h2>

        @if(session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        {{-- Tampilkan error validasi --}}
        @if($errors->any())
            <div class="alert alert-danger">
                <strong>Ups!</strong> Ada beberapa kesalahan:<br>
                <ul class="mb-0">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form id="customOrderForm" action="{{ route('custom-order.store') }}" method="POST" enctype="multipart/form-data">
            @csrf

            <div class="mb-3">
                <label for="name" class="form-label">Nama Lengkap</label>
                <input type="text" name="name" class="form-control" value="{{ old('name') }}" required>
            </div>

            <div class="mb-3">
                <label for="product_type" class="form-label">Jenis Produk</label>
                <input type="text" name="product_type" class="form-control" value="{{ old('product_type') }}" placeholder="Contoh: Kemeja Seragam, Kaos Komunitas" required>
            </div>

            <div class="mb-3">
                <label for="size_detail" class="form-label">Detail Ukuran </label>
                <textarea name="size_detail" class="form-control" rows="2" placeholder="Contoh: 2x M, 3x L, 1x XL">{{ old('size_detail') }}</textarea>
            </div>

            <div class="mb-3">
                <label for="description" class="form-label">Deskripsi Tambahan</label>
                <textarea name="description" class="form-control" rows="3" placeholder="Contoh: Bahan cotton combed 30s, sablon DTF full depan">{{ old('description') }}</textarea>
            </div>

            <div class="mb-3">
                <label for="design_file" class="form-label">Upload Desain </label>
                <input type="file" name="design_file" class="form-control" accept="image/*">
            </div>

            <div class="mb-3">
                <label for="quantity" class="form-label">Jumlah Pesanan</label>
                <!-- HAPUS min="24" supaya alert custom jalan -->
                <input type="number" name="quantity" class="form-control" value="{{ old('quantity') }}" required>
                <small class="text-danger d-block mt-1 fw-semibold">⚠️ Pesanan minimal 2 lusin (24 pcs)</small>
            </div>

            <div class="mb-3">
                <label for="contact" class="form-label">Kontak (WA / No HP)</label>
                <input type="text" name="contact" class="form-control" value="{{ old('contact') }}" placeholder="Contoh: 0812xxxxxxx" required>
            </div>

            <button type="submit" class="btn btn-primary w-100 mt-3">
                Kirim Pesanan Custom
            </button>
        </form>
    </div>

    {{-- Daftar Custom Order User --}}
    <div class="card shadow-sm p-4 border-0 rounded-4">
        <h4 class="mb-3">Daftar Pesanan Custom Anda</h4>
        @if(isset($orders) && $orders->count())
            <div class="table-responsive">
                <table class="table table-bordered align-middle">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Produk</th>
                            <th>Jumlah</th>
                            <th>Status</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($orders as $order)
                        <tr>
                            <td>{{ $order->id }}</td>
                            <td>{{ $order->product_type }}</td>
                            <td>{{ $order->quantity }}</td>
                            <td>Di Proses</td>
                            <td>
                                <a href="{{ route('custom-order.chat', $order->id) }}" class="btn btn-sm btn-outline-primary">Chat</a>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @else
            <p class="text-muted">Belum ada pesanan custom.</p>
        @endif
    </div>
</div>

{{-- JavaScript validasi jumlah --}}
<script>
    document.getElementById('customOrderForm').addEventListener('submit', function(e) {
        const qtyInput = document.querySelector('input[name="quantity"]');
        const qty = parseInt(qtyInput.value);

        if (isNaN(qty) || qty < 24) {
            e.preventDefault();
            alert("⚠️ Minimal order adalah 24 pcs (2 lusin). Silakan tambah jumlah pesanan.");
            qtyInput.focus();
        }
    });
</script>
@endsection
