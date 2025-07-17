@extends('layouts.app')

@section('title', $product->name)

@section('content')
<section class="py-5 animate-slide-up">
    <div class="container">
        <div class="row">
            {{-- Gambar Utama --}}
            <div class="col-lg-6 mb-4 mb-lg-0 animate-slide-left">
                <div class="card shadow-sm">
                    <img id="product-image"
                         src="{{ asset('images/products/' . ($product->image ?? 'default.jpg')) }}"
                         alt="{{ $product->name }}"
                         class="card-img-top"
                         style="max-height: 450px; width: 100%; object-fit: contain;">
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
                        <h4 class="text-primary mb-3">Rp {{ number_format($product->price, 0, ',', '.') }}</h4>

                        <div class="mb-4">
                            <h5>Deskripsi Produk</h5>
                            <p>{!! nl2br(e($product->description)) !!}</p>
                        </div>

                        @auth
                            {{-- Error --}}
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
                            <form action="{{ route('cart.store') }}" method="POST" class="mb-3">
                                @csrf
                                <input type="hidden" name="product_id" value="{{ $product->id }}">

                                {{-- Warna --}}
                                <div class="mb-3">
                                    <label for="warna" class="form-label">Warna</label>
                                    <select name="warna" id="warna" class="form-select" required>
                                        <option value="">Pilih Warna</option>
                                        @foreach($product->variants as $variant)
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
                                        @if(in_array($product->category, ['Kaos', 'Kemeja', 'Jaket']))
                                            @foreach(['S', 'M', 'L', 'XL', 'XXL'] as $size)
                                                <option value="{{ $size }}">{{ $size }}</option>
                                            @endforeach
                                        @elseif($product->category == 'Celana')
                                            @for($i = 28; $i <= 38; $i++)
                                                <option value="{{ $i }}">{{ $i }}</option>
                                            @endfor
                                        @else
                                            <option value="-">-</option>
                                        @endif
                                    </select>
                                </div>

                                {{-- Jumlah --}}
                                <div class="mb-3">
                                    <label for="jumlah" class="form-label">Jumlah</label>
                                    <input type="number" name="jumlah" id="jumlah" class="form-control" value="1" min="1" required>
                                    <small id="stok-info" class="text-muted d-block mt-1"></small>
                                </div>

                                <button type="submit" class="btn btn-success btn-lg">+ Tambah ke Keranjang</button>
                            </form>
                        @endauth

                        {{-- WhatsApp --}}
                        <div class="d-flex">
                            <a href="https://wa.me/6287872459410?text=Halo%2C%20saya%20tertarik%20dengan%20produk%20{{ urlencode($product->name) }}"
                               target="_blank" class="btn btn-outline-secondary me-2">
                                <i class="fab fa-whatsapp"></i> Tanya via WhatsApp
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

{{-- JS --}}
<script>
document.addEventListener('DOMContentLoaded', () => {
    const warnaSelect = document.getElementById('warna');
    const jumlahInput = document.getElementById('jumlah');
    const stokInfo = document.getElementById('stok-info');
    const imgPreview = document.getElementById('product-image');

    warnaSelect.addEventListener('change', function () {
        const selected = this.options[this.selectedIndex];
        const newImage = selected.getAttribute('data-image');
        const stok = selected.getAttribute('data-stock');

        if (newImage) imgPreview.src = newImage;

        if (stok) {
            stokInfo.innerText = `Sisa stok: ${stok}`;
            jumlahInput.max = stok;
        } else {
            stokInfo.innerText = '';
            jumlahInput.removeAttribute('max');
        }
    });
});
</script>
@endsection
