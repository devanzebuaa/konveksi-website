@extends('layouts.app')

@section('title', 'Checkout Pembayaran')

@section('content')
<div class="container py-5">
    <h2 class="mb-4">Checkout Pembayaran</h2>

    <form action="{{ route('cart.checkout') }}" method="POST" enctype="multipart/form-data">
        @csrf

        @foreach ($item_ids as $id)
            <input type="hidden" name="item_ids[]" value="{{ $id }}">
        @endforeach

        <div class="mb-3">
            <label for="payment_method" class="form-label">Metode Pembayaran</label>
            <select name="payment_method" id="payment_method" class="form-select" required onchange="togglePaymentFields()">
                <option value="">-- Pilih Metode --</option>
                <option value="bank">Transfer Bank</option>
                <option value="e-wallet">E-Wallet</option>
            </select>
        </div>

        <div class="mb-3" id="bank_field" style="display: none;">
            <label for="bank_name" class="form-label">Nama Bank</label>
            <select name="bank_name" class="form-select">
                <option value="">-- Pilih Bank --</option>
                <option value="BCA">BCA</option>
                <option value="BNI">BNI</option>
                <option value="BRI">BRI</option>
                <option value="Mandiri">Mandiri</option>
                <option value="BSI">BSI</option>
            </select>
        </div>

        <div class="mb-3" id="wallet_field" style="display: none;">
            <label for="wallet_type" class="form-label">Jenis E-Wallet</label>
            <select name="wallet_type" class="form-select">
                <option value="">-- Pilih E-Wallet --</option>
                <option value="OVO">OVO</option>
                <option value="DANA">DANA</option>
                <option value="GoPay">GoPay</option>
                <option value="ShopeePay">ShopeePay</option>
                <option value="SeaBank">SeaBank</option>
            </select>
        </div>

        <div class="mb-3">
            <label for="address" class="form-label">Alamat Pengiriman</label>
            <textarea name="address" id="address" class="form-control" rows="3" required>{{ old('address') }}</textarea>
        </div>

        <div class="mb-3">
            <label for="payment_proof" class="form-label">Upload Bukti Pembayaran</label>
            <input type="file" name="payment_proof" class="form-control" accept="image/*" required>
        </div>

        <button type="submit" class="btn btn-primary">Kirim & Selesaikan Checkout</button>
    </form>
</div>
@endsection

@section('scripts')
<script>
    function togglePaymentFields() {
        const method = document.getElementById('payment_method').value;
        document.getElementById('bank_field').style.display = method === 'bank' ? 'block' : 'none';
        document.getElementById('wallet_field').style.display = method === 'e-wallet' ? 'block' : 'none';
    }
</script>
@endsection
