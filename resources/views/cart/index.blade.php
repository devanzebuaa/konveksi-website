@extends('layouts.app')

@section('title', 'Keranjang Belanja')

@section('content')
<div class="container py-5">
    <h2 class="mb-4">Keranjang Belanja</h2>

    @if ($items->isEmpty())
        <div class="alert alert-info">Keranjangmu kosong.</div>
    @else
        @php $total = 0; @endphp
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
                    @foreach ($items as $item)
                        @php
                            $ukuran = strtoupper($item->ukuran);
                            $harga = $item->product->sizePrices->where('size', $ukuran)->first()?->price ?? 0;
                            $subtotal = $harga * $item->jumlah;
                            $total += $subtotal;
                            $defaultImage = asset('images/default.png');
                            if ($item->variant && $item->variant->image) {
                                $variantImage = asset('images/variants/' . $item->variant->image);
                            } elseif ($item->product && $item->product->image) {
                                $variantImage = asset('images/products/' . $item->product->image);
                            } else {
                                $variantImage = $defaultImage;
                            }
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
                                <div class="d-flex gap-2">
                                    <form action="{{ route('cart.destroy', $item->id) }}" method="POST" onsubmit="return confirm('Hapus item ini?')">
                                        @csrf
                                        @method('DELETE')
                                        <button class="btn btn-sm btn-danger">🗑</button>
                                    </form>
                                    <button type="button" class="btn btn-sm btn-primary" data-bs-toggle="modal" data-bs-target="#modalBayar{{ $item->id }}">Bayar</button>
                                </div>
                                <!-- Modal Pembayaran Satuan -->
                                <div class="modal fade" id="modalBayar{{ $item->id }}" tabindex="-1" aria-labelledby="modalBayarLabel{{ $item->id }}" aria-hidden="true">
                                  <div class="modal-dialog">
                                    <div class="modal-content">
                                      <div class="modal-header">
                                        <h5 class="modal-title" id="modalBayarLabel{{ $item->id }}">Pembayaran Item</h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                      </div>
                                      <form action="{{ route('cart.checkout.item', $item->id) }}" method="POST" enctype="multipart/form-data">
                                        @csrf
                                        <div class="modal-body">
                                          <div class="mb-3">
                                            <label class="form-label">Metode Pembayaran</label>
                                            <select name="payment_method" class="form-select payment-method-select" required>
                                              <option value="">-- Pilih --</option>
                                              <option value="bank">Transfer Bank</option>
                                              <option value="e-wallet">E-Wallet</option>
                                            </select>
                                          </div>
                                          <div class="mb-3 bank-field d-none">
                                            <label class="form-label">Nama Bank</label>
                                            <select name="bank_name" class="form-select">
                                              <option value="">-- Pilih Bank --</option>
                                              <option value="BCA">BCA</option>
                                              <option value="BNI">BNI</option>
                                              <option value="BRI">BRI</option>
                                              <option value="BSI">BSI</option>
                                            </select>
                                            <small class="text-muted">No Rekening: <strong>1234567890</strong> a.n. <strong>PT Dinara Konveksi</strong></small>
                                          </div>
                                          <div class="mb-3 wallet-field d-none">
                                            <label class="form-label">Jenis E-Wallet</label>
                                            <select name="wallet_type" class="form-select">
                                              <option value="">-- Pilih E-Wallet --</option>
                                              <option value="DANA">DANA</option>
                                              <option value="OVO">OVO</option>
                                              <option value="Gopay">Gopay</option>
                                              <option value="ShopeePay">ShopeePay</option>
                                            </select>
                                            <small class="text-muted">No E-Wallet: <strong>085228683441</strong> a.n. <strong>Dinara Konveksi</strong></small>
                                          </div>
                                          <div class="mb-3">
                                            <label class="form-label">Upload Bukti Pembayaran</label>
                                            <input type="file" name="payment_proof" class="form-control" accept="image/*" required>
                                          </div>
                                          <div class="mb-3">
                                            <label class="form-label fw-bold">Alamat Pengiriman</label>
                                            <textarea name="address" class="form-control" rows="3" required>{{ old('address') }}</textarea>
                                          </div>
                                        </div>
                                        <div class="modal-footer">
                                          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                                          <button type="submit" class="btn btn-primary">Bayar</button>
                                        </div>
                                      </form>
                                    </div>
                                  </div>
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

        <!-- Tidak ada form pembayaran massal di bawah -->
        <!-- Tombol Checkout Semua -->
        <div class="card p-4 shadow-sm mt-4">
            <h5 class="mb-3">Checkout Semua</h5>
            <button type="button" class="btn btn-success w-100 mt-2" data-bs-toggle="modal" data-bs-target="#modalCheckoutAll">Checkout Semua</button>
        </div>

        <!-- Modal Checkout Semua -->
        <div class="modal fade" id="modalCheckoutAll" tabindex="-1" aria-labelledby="modalCheckoutAllLabel" aria-hidden="true">
          <div class="modal-dialog">
            <div class="modal-content">
              <div class="modal-header">
                <h5 class="modal-title" id="modalCheckoutAllLabel">Checkout Semua</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
              </div>
              <form action="{{ route('cart.checkout') }}" method="POST" enctype="multipart/form-data">
                @csrf
                @foreach ($items as $item)
                    <input type="hidden" name="item_ids[]" value="{{ $item->id }}">
                @endforeach
                <div class="modal-body">
                  <div class="mb-3">
                    <label class="form-label">Metode Pembayaran</label>
                    <select name="payment_method" class="form-select payment-method-select-all" required>
                        <option value="">-- Pilih --</option>
                        <option value="bank">Transfer Bank</option>
                        <option value="e-wallet">E-Wallet</option>
                    </select>
                  </div>
                  <div class="mb-3 bank-field-all d-none">
                    <label class="form-label">Nama Bank</label>
                    <select name="bank_name" class="form-select">
                        <option value="">-- Pilih Bank --</option>
                        <option value="BCA">BCA</option>
                        <option value="BNI">BNI</option>
                        <option value="BRI">BRI</option>
                        <option value="BSI">BSI</option>
                    </select>
                    <small class="text-muted">No Rekening: <strong>1234567890</strong> a.n. <strong>PT Dinara Konveksi</strong></small>
                  </div>
                  <div class="mb-3 wallet-field-all d-none">
                    <label class="form-label">Jenis E-Wallet</label>
                    <select name="wallet_type" class="form-select">
                        <option value="">-- Pilih E-Wallet --</option>
                        <option value="DANA">DANA</option>
                        <option value="OVO">OVO</option>
                        <option value="Gopay">Gopay</option>
                        <option value="ShopeePay">ShopeePay</option>
                    </select>
                    <small class="text-muted">No E-Wallet: <strong>085228683441</strong> a.n. <strong>Dinara Konveksi</strong></small>
                  </div>
                  <div class="mb-3">
                    <label class="form-label">Upload Bukti Pembayaran</label>
                    <input type="file" name="payment_proof" class="form-control" accept="image/*" required>
                  </div>
                  <div class="mb-3">
                    <label class="form-label fw-bold">Alamat Pengiriman</label>
                    <textarea name="address" class="form-control" rows="3" required>{{ old('address') }}</textarea>
                  </div>
                </div>
                <div class="modal-footer">
                  <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                  <button type="submit" class="btn btn-success">Checkout Semua</button>
                </div>
              </form>
            </div>
          </div>
        </div>
    @endif
</div>
@endsection

@push('scripts')
<script>
document.addEventListener("DOMContentLoaded", function () {
    // Untuk modal satuan
    document.querySelectorAll('.payment-method-select').forEach(function(select) {
        select.addEventListener('change', function() {
            const modal = select.closest('.modal-body');
            if (select.value === 'bank') {
                modal.querySelector('.bank-field').classList.remove('d-none');
                modal.querySelector('.wallet-field').classList.add('d-none');
            } else if (select.value === 'e-wallet') {
                modal.querySelector('.wallet-field').classList.remove('d-none');
                modal.querySelector('.bank-field').classList.add('d-none');
            } else {
                modal.querySelector('.bank-field').classList.add('d-none');
                modal.querySelector('.wallet-field').classList.add('d-none');
            }
        });
    });
    // Untuk form checkout semua
    const selectAll = document.querySelector('.payment-method-select-all');
    if (selectAll) {
        selectAll.addEventListener('change', function() {
            const bankField = document.querySelector('.bank-field-all');
            const walletField = document.querySelector('.wallet-field-all');
            if (selectAll.value === 'bank') {
                bankField.classList.remove('d-none');
                walletField.classList.add('d-none');
            } else if (selectAll.value === 'e-wallet') {
                walletField.classList.remove('d-none');
                bankField.classList.add('d-none');
            } else {
                bankField.classList.add('d-none');
                walletField.classList.add('d-none');
            }
        });
    }
});
</script>
@endpush
