@extends('layouts.app')

@section('title', 'Checkout Pembayaran')

@section('content')
<div class="container py-5">
    <h2 class="mb-4">Checkout Pembayaran</h2>

    @if (session('error'))
        <div class="alert alert-danger">{{ session('error') }}</div>
    @endif

    <form action="{{ route('cart.checkout') }}" method="POST" enctype="multipart/form-data" class="card p-4 shadow-sm">
        @csrf

        <div class="mb-3">
            <label for="payment_method" class="form-label">Metode Pembayaran</label>
            <select name="payment_method" id="payment_method" class="form-select" required onchange="togglePaymentFields()">
                <option value="">-- Pilih Metode --</option>
                <option value="bank">Transfer Bank</option>
                <option value="e-wallet">E-Wallet</option>
            </select>
        </div>

        <div id="bank_field" class="mb-3 d-none">
            <label for="bank_name" class="form-label">Pilih Bank</label>
            <select name="bank_name" id="bank_name" class="form-select">
                <option value="">-- Pilih Bank --</option>
                <option value="BCA">BCA</option>
                <option value="BRI">BRI</option>
                <option value="Mandiri">Mandiri</option>
                <option value="BSI">BSI</option>
            </select>
        </div>

        <div id="wallet_field" class="mb-3 d-none">
            <label for="wallet_type" class="form-label">Pilih E-Wallet</label>
            <select name="wallet_type" id="wallet_type" class="form-select">
                <option value="">-- Pilih E-Wallet --</option>
                <option value="DANA">DANA</option>
                <option value="OVO">OVO</option>
                <option value="GoPay">GoPay</option>
                <option value="ShopeePay">ShopeePay</option>
                <option value="SeaBank">SeaBank</option>
            </select>
        </div>

        <div class="mb-3">
            <label for="payment_proof" class="form-label">Upload Bukti Pembayaran</label>
            <input type="file" name="payment_proof" id="payment_proof" class="form-control" required>
            @error('payment_proof')
                <div class="text-danger small mt-1">{{ $message }}</div>
            @enderror
        </div>

        <button type="submit" class="btn btn-success">Kirim dan Proses Pesanan</button>
    </form>
</div>
@endsection

@section('scripts')
<script>
    function togglePaymentFields() {
        const method = document.getElementById('payment_method').value;
        document.getElementById('bank_field').classList.toggle('d-none', method !== 'bank');
        document.getElementById('wallet_field').classList.toggle('d-none', method !== 'e-wallet');
    }
</script>
@endsection
