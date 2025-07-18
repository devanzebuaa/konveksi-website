@extends('layouts.app')

@section('title', 'Tentang Kami')

@section('content')

<!-- About Section -->
<section class="py-5">
    <div class="container">
        <div class="row">
            <div class="col">
                <div class="card shadow-sm">
                    <div class="card-body">
                        <h2 class="fw-bold mb-4">Tentang Dinara Konveksi</h2>
                        <p>Dinara Konveksi didirikan pada tahun 2010 dengan visi menjadi penyedia produk konveksi terbaik di Indonesia. Dengan pengalaman lebih dari 10 tahun di industri tekstil, kami telah melayani berbagai klien dari perusahaan kecil hingga besar.</p>
                        <p>Kami mengutamakan kualitas, ketepatan waktu, dan pelayanan yang ramah kepada setiap pelanggan. Tim kami terdiri dari ahli-ahli di bidangnya yang siap memberikan solusi terbaik untuk kebutuhan konveksi Anda.</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Vision & Mission -->
<section class="py-5 bg-light">
    <div class="container">
        <div class="row">
            <!-- Visi -->
            <div class="col-md-6 mb-4 mb-md-0">
                <div class="card h-100 shadow-sm">
                    <div class="card-body">
                        <div class="text-center mb-4">
                            <div class="icon-box bg-primary text-white mx-auto rounded-circle d-flex align-items-center justify-content-center" style="width: 60px; height: 60px;">
                                <i class="fas fa-eye fa-2x"></i>
                            </div>
                        </div>
                        <h3 class="text-center mb-4">Visi</h3>
                        <p class="text-center">"Menjadi Perusahaan Konveksi Terdepan di Indonesia dengan inovasi dan kualitas terbaik yang berkelanjutan."</p>
                    </div>
                </div>
            </div>

            <!-- Misi -->
            <div class="col-md-6">
                <div class="card h-100 shadow-sm">
                    <div class="card-body">
                        <div class="text-center mb-4">
                            <div class="icon-box bg-primary text-white mx-auto rounded-circle d-flex align-items-center justify-content-center" style="width: 60px; height: 60px;">
                                <i class="fas fa-bullseye fa-2x"></i>
                            </div>
                        </div>
                        <h3 class="text-center mb-4">Misi</h3>
                        <ul>
                            <li>Menyediakan produk berkualitas tinggi dengan bahan terbaik</li>
                            <li>Memberikan pelayanan prima kepada pelanggan</li>
                            <li>Mengembangkan SDM yang profesional dan kreatif</li>
                            <li>Berinovasi dalam desain dan teknologi produksi</li>
                            <li>Berkontribusi positif bagi industri tekstil Indonesia</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

@endsection
