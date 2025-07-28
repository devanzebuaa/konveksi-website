@extends('layouts.app')

@section('title', $product->name)

@section('content')
<section class="py-5 animate-slide-up">
    <div class="container">
        <div class="row">
            {{-- Gambar Utama --}}
            <div class="col-lg-6 mb-4 mb-lg-0 animate-slide-left">
                <div class="border rounded p-3 bg-white d-inline-block">
                    <img id="product-image"
                         src="{{ asset('images/products/' . ($product->image ?? 'default.jpg')) }}"
                         alt="{{ $product->name }}"
                         class="img-fluid"
                         style="height: auto; width: 100%; max-width: 500px; object-fit: contain;">
                </div>

                {{-- Thumbnail --}}
                <div class="d-flex mt-3 gap-2 flex-wrap">
                    <img src="{{ asset('images/products/' . ($product->image ?? 'default.jpg')) }}"
                         data-image="{{ asset('images/products/' . ($product->image ?? 'default.jpg')) }}"
                         alt="Default"
                         class="img-thumbnail"
                         style="width: 80px; height: 80px; object-fit: cover; cursor: pointer;"
                         onclick="document.getElementById('product-image').src = this.dataset.image;">

                    @foreach ($product->variants as $variant)
                        @if ($variant->image)
                            <img src="{{ asset('storage/' . $variant->image) }}"
                                 data-image="{{ asset('storage/' . $variant->image) }}"
                                 alt="{{ $variant->color }}"
                                 class="img-thumbnail"
                                 style="width: 80px; height: 80px; object-fit: cover; cursor: pointer;"
                                 onclick="document.getElementById('product-image').src = this.dataset.image;">
                        @endif
                    @endforeach
                </div>
            </div>

            {{-- Detail Produk --}}
            <div class="col-lg-6 animate-slide-right">
                <div class="card shadow-sm">
                    <div class="card-body">
                        <span class="badge bg-primary mb-2">{{ $product->category }}</span>
                        <h2 class="fw-bold">{{ $product->name }}</h2>

                        @php
                            $defaultPrice = $product->sizePrices->first()->price ?? $product->price;
                        @endphp
                        <h4 class="text-primary mb-3" id="harga-produk">Rp {{ number_format($defaultPrice, 0, ',', '.') }}</h4>

                        <div class="mb-4">
                            <h5>Deskripsi Produk</h5>
                            <p>{!! nl2br(e($product->description)) !!}</p>
                        </div>

                        @auth
                            @if ($errors->any())
                                <div class="alert alert-danger">
                                    <ul class="mb-0">
                                        @foreach ($errors->all() as $error)
                                            <li>{{ $error }}</li>
                                        @endforeach
                                    </ul>
                                </div>
                            @endif

                            {{-- Form Tambah ke Keranjang --}}
                            <form action="{{ route('cart.store') }}" method="POST" class="mb-3" onsubmit="return cekValidasiFormIni();">
                                @csrf
                                <input type="hidden" name="product_id" value="{{ $product->id }}">

                                {{-- Warna --}}
                                <div class="mb-3">
                                    <label for="warna" class="form-label">Warna</label>
                                    <select name="warna" id="warna" class="form-select" required>
                                        <option value="">Pilih Warna</option>
                                        @foreach ($product->variants as $variant)
                                            <option value="{{ $variant->color }}"
                                                    data-image="{{ asset('storage/' . ($variant->image ?? 'default.jpg')) }}"
                                                    data-stock="{{ $variant->stock }}">
                                                {{ ucfirst($variant->color) }} (Stok: {{ $variant->stock }})
                                            </option>
                                        @endforeach
                                    </select>
                                </div>

                                {{-- Ukuran --}}
                                <div class="mb-3">
                                    <label for="ukuran" class="form-label">Ukuran</label>
                                    <select name="ukuran" id="ukuran" class="form-select" required>
                                        <option value="">Pilih Ukuran</option>
                                        @foreach ($product->sizePrices as $sizePrice)
                                            <option value="{{ $sizePrice->size }}">{{ $sizePrice->size }}</option>
                                        @endforeach
                                    </select>
                                </div>

                                {{-- Jumlah --}}
                                <div class="mb-3">
                                    <label for="jumlah" class="form-label">Jumlah</label>
                                    <input type="number" name="jumlah" id="jumlah" class="form-control"
                                           value="1" min="1" required>
                                    <small id="stok-info" class="text-muted d-block mt-1"></small>
                                </div>

                                {{-- Tombol --}}
                                <div class="d-flex flex-column gap-2 mt-4 align-items-start">
                                    <button type="submit"
                                            class="btn btn-success px-4 py-2 fw-medium shadow"
                                            style="border-radius: 10px; font-size: 0.9rem; max-width: 300px;">
                                        <i class="fas fa-cart-plus me-1"></i> Tambah ke Keranjang
                                    </button>

                                    <a href="{{ route('products.index') }}"
                                       class="btn btn-dark px-4 py-2 fw-medium shadow"
                                       style="border-radius: 10px; font-size: 0.9rem; max-width: 300px;">
                                        <i class="fas fa-arrow-left me-1"></i> Kembali ke Daftar Produk
                                    </a>
                                </div>
                            </form>
                        @endauth
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

{{-- Script untuk update harga dan stok --}}
<script>
    document.addEventListener('DOMContentLoaded', () => {
        const warnaSelect = document.getElementById('warna');
        const jumlahInput = document.getElementById('jumlah');
        const stokInfo = document.getElementById('stok-info');
        const imgPreview = document.getElementById('product-image');
        const ukuranSelect = document.getElementById('ukuran');
        const hargaElement = document.getElementById('harga-produk');

        const hargaPerUkuran = @json($product->sizePrices->pluck('price', 'size'));

        warnaSelect.addEventListener('change', function () {
            const selected = this.options[this.selectedIndex];
            const newImage = selected.dataset.image;
            const stok = selected.dataset.stock;

            if (newImage) imgPreview.src = newImage;
            if (stok) {
                stokInfo.innerText = `Sisa stok: ${stok}`;
                jumlahInput.max = stok;
            } else {
                stokInfo.innerText = '';
                jumlahInput.removeAttribute('max');
            }
        });

        ukuranSelect.addEventListener('change', function () {
            const selectedUkuran = this.value;
            if (hargaPerUkuran[selectedUkuran]) {
                const hargaBaru = parseInt(hargaPerUkuran[selectedUkuran]);
                hargaElement.textContent = "Rp " + hargaBaru.toLocaleString('id-ID');
            } else {
                hargaElement.textContent = "Rp {{ number_format($defaultPrice, 0, ',', '.') }}";
            }
        });
    });

    function cekValidasiFormIni() {
        const warna = document.getElementById('warna').value;
        const ukuran = document.getElementById('ukuran').value;
        if (!warna || !ukuran) {
            alert('Silakan pilih warna dan ukuran terlebih dahulu.');
            return false;
        }
        return true;
    }
</script>
@endsection
