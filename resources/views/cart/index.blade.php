@extends('layouts.app')

@section('title', 'Keranjang Belanja')

@section('content')
<div class="container py-5">
    <h2 class="mb-4">Keranjang Belanja</h2>

    @if ($items->isEmpty())
        <div class="alert alert-info">Keranjangmu kosong.</div>
    @else
        <div class="table-responsive mb-4">
            <table class="table align-middle">
                <thead class="table-light">
                    <tr>
                        <th>Produk</th>
                        <th>Warna</th>
                        <th>Ukuran</th>
                        <th>Jumlah</th>
                        <th>Harga</th>
                        <th>Subtotal</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @php $total = 0; @endphp
                    @foreach ($items as $item)
                        @php
                            $harga = $item->product->price ?? 0;
                            $subtotal = $harga * $item->jumlah;
                            $total += $subtotal;
                            $variantImage = $item->variant && $item->variant->image
                                ? asset('storage/' . $item->variant->image)
                                : asset('images/products/default.jpg');
                        @endphp
                        <tr>
                            <td>
                                <div class="d-flex align-items-center">
                                    <img src="{{ $variantImage }}" alt="" width="70" class="me-2 rounded">
                                    <div>
                                        <strong>{{ $item->product->name ?? 'Produk tidak ditemukan' }}</strong><br>
                                        <small>Varian: {{ $item->variant->color ?? '-' }}</small>
                                    </div>
                                </div>
                            </td>
                            <td>{{ $item->variant->color ?? '-' }}</td>
                            <td>{{ $item->ukuran }}</td>
                            <td>{{ $item->jumlah }}</td>
                            <td>Rp{{ number_format($harga, 0, ',', '.') }}</td>
                            <td>Rp{{ number_format($subtotal, 0, ',', '.') }}</td>
                            <td>
                                <div class="d-flex gap-1">
                                    <form action="{{ route('cart.destroy', $item->id) }}" method="POST" onsubmit="return confirm('Hapus item ini?')">
                                        @csrf
                                        @method('DELETE')
                                        <button class="btn btn-sm btn-danger">🗑</button>
                                    </form>

                                    {{-- Form Bayar Satuan --}}
                                    <form action="{{ route('cart.checkout.item', $item->id) }}" method="POST" enctype="multipart/form-data" class="form-bayar-satuan">
                                        @csrf
                                        <input type="hidden" name="payment_method" class="payment_method">
                                        <input type="hidden" name="bank_name" class="bank_name">
                                        <input type="hidden" name="wallet_type" class="wallet_type">
                                        <input type="file" name="payment_proof" class="payment_proof d-none" accept="image/*">
                                        <button type="submit" class="btn btn-sm btn-primary">Bayar</button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
                <tfoot class="table-light">
                    <tr>
                        <td colspan="5" class="text-end"><strong>Total:</strong></td>
                        <td colspan="2"><strong>Rp{{ number_format($total, 0, ',', '.') }}</strong></td>
                    </tr>
                </tfoot>
            </table>
        </div>

        {{-- Form Inputan Pembayaran Utama untuk Dipakai Bayar Satuan --}}
        <div class="card p-4 shadow-sm">
            <h5 class="mb-3">Informasi Pembayaran</h5>

            @if ($errors->any())
                <div class="alert alert-danger">
                    <ul class="mb-0 small">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <div class="mb-3">
                <label for="payment_method" class="form-label">Metode Pembayaran</label>
                <select id="payment_method" class="form-select" required>
                    <option value="">-- Pilih --</option>
                    <option value="bank">Transfer Bank</option>
                    <option value="e-wallet">E-Wallet</option>
                </select>
            </div>

            <div class="mb-3 d-none" id="bank_field">
                <label for="bank_name" class="form-label">Nama Bank</label>
                <select id="bank_name" class="form-select">
                    <option value="">-- Pilih Bank --</option>
                    <option value="BCA">BCA</option>
                    <option value="BNI">BNI</option>
                    <option value="BRI">BRI</option>
                    <option value="BSI">BSI</option>
                </select>
                <small class="text-muted">No Rek: <strong>1234567890</strong> a.n. <strong>PT Dinara Konveksi</strong></small>
            </div>

            <div class="mb-3 d-none" id="wallet_field">
                <label for="wallet_type" class="form-label">Jenis E-Wallet</label>
                <select id="wallet_type" class="form-select">
                    <option value="">-- Pilih E-Wallet --</option>
                    <option value="DANA">DANA</option>
                    <option value="OVO">OVO</option>
                    <option value="Gopay">Gopay</option>
                    <option value="ShopeePay">ShopeePay</option>
                </select>
                <small class="text-muted">No HP: <strong>0878-7245-9410</strong> a.n. <strong>Dinara Konveksi</strong></small>
            </div>

            <div class="mb-3">
                <label for="payment_proof_main" class="form-label">Upload Bukti Pembayaran</label>
                <input type="file" id="payment_proof_main" class="form-control" accept="image/*" required>
            </div>
        </div>
    @endif
</div>
@endsection

@push('scripts')
<script>
document.addEventListener("DOMContentLoaded", function () {
    const methodSelect = document.getElementById('payment_method');
    const bankField = document.getElementById('bank_field');
    const walletField = document.getElementById('wallet_field');
    const bankInput = document.getElementById('bank_name');
    const walletInput = document.getElementById('wallet_type');
    const proofMain = document.getElementById('payment_proof_main');

    methodSelect.addEventListener('change', function () {
        bankField.classList.toggle('d-none', this.value !== 'bank');
        walletField.classList.toggle('d-none', this.value !== 'e-wallet');
    });

    document.querySelectorAll('.form-bayar-satuan').forEach(form => {
        form.addEventListener('submit', function (e) {
            e.preventDefault();

            const method = methodSelect.value;
            const bank = bankInput.value;
            const wallet = walletInput.value;
            const proofFile = proofMain.files[0];

            if (!method || !proofFile || (method === 'bank' && !bank) || (method === 'e-wallet' && !wallet)) {
                alert('Lengkapi metode pembayaran dan bukti terlebih dahulu.');
                return;
            }

            form.querySelector('.payment_method').value = method;
            form.querySelector('.bank_name').value = bank;
            form.querySelector('.wallet_type').value = wallet;

            const proofInput = form.querySelector('.payment_proof');
            const dt = new DataTransfer();
            dt.items.add(proofFile);
            proofInput.files = dt.files;

            form.submit();
        });
    });
});
</script>
@endpush
