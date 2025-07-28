@extends('layouts.app')

@section('title', 'Checkout Pembayaran')

@section('content')
<div class="container py-5">
    <h2 class="mb-4">Checkout Pembayaran</h2>

    @if ($errors->any())
        <div class="alert alert-danger">
            <ul class="mb-0 small">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('cart.checkout') }}" method="POST" enctype="multipart/form-data">
        @csrf

        {{-- Input semua ID item dari keranjang --}}
        @foreach ($item_ids as $id)
            <input type="hidden" name="item_ids[]" value="{{ $id }}">
        @endforeach

        {{-- Metode Pembayaran --}}
        <div class="mb-3">
            <label for="payment_method" class="form-label">Metode Pembayaran</label>
            <select name="payment_method" id="payment_method" class="form-select @error('payment_method') is-invalid @enderror" onchange="togglePaymentFields()" required>
                <option value="">-- Pilih Metode --</option>
                <option value="bank" {{ old('payment_method') == 'bank' ? 'selected' : '' }}>Transfer Bank</option>
                <option value="e-wallet" {{ old('payment_method') == 'e-wallet' ? 'selected' : '' }}>E-Wallet</option>
            </select>
            @error('payment_method')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        {{-- Jika metode Bank --}}
        <div class="mb-3" id="bank_field" style="display: none;">
            <label for="bank_name" class="form-label">Nama Bank</label>
            <select name="bank_name" class="form-select @error('bank_name') is-invalid @enderror">
                <option value="">-- Pilih Bank --</option>
                @foreach (['BCA', 'BNI', 'BRI', 'Mandiri', 'BSI'] as $bank)
                    <option value="{{ $bank }}" {{ old('bank_name') == $bank ? 'selected' : '' }}>{{ $bank }}</option>
                @endforeach
            </select>
            @error('bank_name')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        {{-- Jika metode E-Wallet --}}
        <div class="mb-3" id="wallet_field" style="display: none;">
            <label for="wallet_type" class="form-label">Jenis E-Wallet</label>
            <select name="wallet_type" class="form-select @error('wallet_type') is-invalid @enderror">
                <option value="">-- Pilih E-Wallet --</option>
                @foreach (['OVO', 'DANA', 'GoPay', 'ShopeePay', 'SeaBank'] as $wallet)
                    <option value="{{ $wallet }}" {{ old('wallet_type') == $wallet ? 'selected' : '' }}>{{ $wallet }}</option>
                @endforeach
            </select>
            @error('wallet_type')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        {{-- Alamat Pengiriman --}}
        <div class="mb-3">
            <label for="address" class="form-label">Alamat Pengiriman</label>
            <textarea name="address" id="address" class="form-control @error('address') is-invalid @enderror" rows="3" required>{{ old('address') }}</textarea>
            @error('address')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        {{-- Upload Bukti Pembayaran --}}
        <div class="mb-3">
            <label for="payment_proof" class="form-label">Upload Bukti Pembayaran</label>
            <input type="file" name="payment_proof" id="payment_proof" class="form-control @error('payment_proof') is-invalid @enderror" accept="image/*" required>
            @error('payment_proof')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <button type="submit" class="btn btn-primary">Kirim & Selesaikan Checkout</button>
    </form>
</div>
@endsection

@section('scripts')
<script>
    function togglePaymentFields() {
        const method = document.getElementById('payment_method').value;
        const bankField = document.getElementById('bank_field');
        const walletField = document.getElementById('wallet_field');

        bankField.style.display = (method === 'bank') ? 'block' : 'none';
        walletField.style.display = (method === 'e-wallet') ? 'block' : 'none';
    }

    document.addEventListener('DOMContentLoaded', function () {
        togglePaymentFields(); // Aktifkan saat reload agar sesuai old()
    });
</script>
@endsection
