<!DOCTYPE html>
<html lang="id">
<head>
    <link rel="icon" type="image/png" href="{{ asset('images/favicon.png') }}">
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    {{-- SEO --}}
    <meta name="description" content="Konveksi Dinara - Pusat produksi pakaian berkualitas di Bandung.">
    <meta name="keywords" content="konveksi, pakaian, bandung, kaos, jaket, seragam, dinara">
    <meta name="author" content="Konveksi Dinara">

    {{-- CSRF --}}
    <meta name="csrf-token" content="{{ csrf_token() }}">

    {{-- Title --}}
    <title>@yield('title', 'Dinara Konveksi') - Dinara Konveksi</title>

    {{-- CSS --}}
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">

    @yield('styles')
</head>
<body class="d-flex flex-column min-vh-100">

    {{-- ✅ Navbar --}}
    <nav class="navbar navbar-expand-lg navbar-dark bg-primary sticky-top">
        <div class="container">
            <a class="navbar-brand" href="/">
                <img src="{{ asset('images/Logo.png') }}" alt="Logo" height="100">
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>

            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item"><a class="nav-link" href="/">Beranda</a></li>

                    {{-- Dropdown Produk --}}
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" id="produkDropdown" role="button" data-bs-toggle="dropdown">
                            Produk
                        </a>
                        <ul class="dropdown-menu">
                            <li><a class="dropdown-item" href="{{ route('products.index') }}">Produk</a></li>
                            <li><a class="dropdown-item" href="{{ route('custom-order.form') }}">Custom Produk</a></li>
                            <li><a class="dropdown-item" href="{{ route('gallery') }}">Galeri</a></li>
                        </ul>
                    </li>

                    <li class="nav-item"><a class="nav-link" href="{{ route('production') }}">Proses Produksi</a></li>
                    <li class="nav-item"><a class="nav-link" href="{{ route('about') }}">Tentang Kami</a></li>

                    @auth
                        @if(!auth()->user()->is_admin)
                            {{-- Dropdown Belanja --}}
                            <li class="nav-item dropdown">
                                <a class="nav-link dropdown-toggle" href="#" id="belanjaDropdown" role="button" data-bs-toggle="dropdown">
                                    Belanja
                                </a>
                                <ul class="dropdown-menu">
                                    <li><a class="dropdown-item" href="{{ route('cart.index') }}">Keranjangku</a></li>
                                    <li><a class="dropdown-item" href="{{ route('orders.index') }}">Belanjaanku</a></li>
                                    <li><a class="dropdown-item" href="{{ route('testimonials.index') }}">Testimoni</a></li>
                                </ul>
                            </li>
                        @endif

                        @if(auth()->user()->is_admin)
                            <li class="nav-item"><a class="nav-link" href="{{ route('dashboard') }}">Admin</a></li>
                        @endif

                        {{-- 🔔 Notifikasi --}}
                        @if(!auth()->user()->is_admin)
                            @php
                                $orders = \App\Models\Order::with('product')
                                    ->where('user_id', auth()->id())
                                    ->where('status', '!=', 'Menunggu Konfirmasi')
                                    ->latest()->take(5)->get();
                                $notifCount = $orders->where('user_notified', false)->count();
                            @endphp
                            <li class="nav-item dropdown">
                                <a class="nav-link position-relative dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false" id="notifDropdown">
                                    <i class="fas fa-bell"></i>
                                    @if($notifCount > 0)
                                        <span id="notif-badge" class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger">
                                            {{ $notifCount }}
                                        </span>
                                    @endif
                                </a>
                                <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="notifDropdown">
                                    @forelse($orders as $order)
                                        <li class="px-3 py-2 small">
                                            <strong>Produk:</strong> {{ $order->product->name ?? 'Produk tidak ditemukan' }}<br>
                                            <strong>Status:</strong> {{ $order->status }}<br>
                                            <small class="text-muted">{{ $order->updated_at->diffForHumans() }}</small>
                                        </li>
                                        @if(!$loop->last)
                                            <li><hr class="dropdown-divider"></li>
                                        @endif
                                    @empty
                                        <li class="dropdown-item text-muted">Tidak ada notifikasi.</li>
                                    @endforelse
                                </ul>
                            </li>
                        @endif

                        {{-- Logout --}}
                        <li class="nav-item">
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button type="submit" class="nav-link btn btn-link">Logout</button>
                            </form>
                        </li>
                    @else
                        <li class="nav-item"><a class="nav-link" href="{{ route('login') }}">Login</a></li>
                        @if (Route::has('register'))
                            <li class="nav-item"><a class="nav-link" href="{{ route('register') }}">Register</a></li>
                        @endif
                    @endauth
                </ul>
            </div>
        </div>
    </nav>

    {{-- ✅ Flash Message --}}
    @include('partials.alert')

    {{-- ✅ Main Content --}}
    <main class="flex-fill w-100">
        @yield('content')
    </main>

    {{-- ✅ Scroll to Top --}}
    <button id="scrollTopBtn" class="btn btn-primary position-fixed bottom-0 end-0 m-4 d-none" style="z-index: 999;">
        <i class="fas fa-arrow-up"></i>
    </button>

    {{-- ✅ Footer --}}
    <footer class="bg-dark text-white py-4 mt-auto">
        <div class="container">
            <div class="row">
                <div class="col-md-4">
                    <h5>Tentang Kami</h5>
                    <p>Kami adalah konveksi terpercaya yang mengutamakan kualitas, ketepatan waktu, dan kepuasan pelanggan dalam setiap jahitan.</p>
                </div>
                <div class="col-md-4">
                    <h5>Kontak</h5>
                    <p><i class="fas fa-map-marker-alt"></i> Jalan Diponegoro No. 01, RT 04/RW 03, Kecamatan Sumpiuh, Kabupaten Banyumas, Jawa Tengah, 53195</p>
                    <p><i class="fas fa-phone"></i> 085228683441</p>
                    <p><i class="fas fa-envelope"></i> dinarakonveksi@gmail.com</p>
                </div>
                <div class="col-md-4">
                    <h5>Sosial Media</h5>
                    <a href="#" class="text-white me-2"><i class="fab fa-facebook-f fa-lg"></i></a>
                    <a href="#" class="text-white me-2"><i class="fab fa-instagram fa-lg"></i></a>
                    <a href="https://wa.me/6285228683441" class="text-white me-2" target="_blank" rel="noopener">
                        <i class="fab fa-whatsapp fa-lg"></i></a>
                </div>
            </div>
            <hr>
            <div class="text-center">
                <p class="mb-0">&copy; 2025 Dinara Konveksi.</p>
            </div>
        </div>
    </footer>

    {{-- ✅ Scripts --}}
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

    @auth
    <script>
        document.addEventListener("DOMContentLoaded", () => {
            const btn = document.getElementById('scrollTopBtn');
            window.onscroll = () => {
                btn.classList.toggle('d-none', window.scrollY < 200);
            };
            btn.onclick = () => {
                window.scrollTo({ top: 0, behavior: 'smooth' });
            };

            const notifBadge = document.getElementById("notif-badge");
            const notifDropdown = document.getElementById("notifDropdown");

            notifDropdown?.addEventListener("click", () => {
                notifBadge?.remove();

                fetch("/notif/mark-as-read", {
                    method: "POST",
                    headers: {
                        "X-CSRF-TOKEN": document.querySelector('meta[name=csrf-token]').content,
                        "Accept": "application/json"
                    }
                })
                .then(res => res.json())
                .then(data => console.log("Notifikasi dibaca:", data))
                .catch(err => console.error("Gagal tandai notifikasi:", err));

                document.getElementById("notif-alert")?.remove();
            });
        });
    </script>
    @endauth

    @stack('scripts')
</body>
</html>
