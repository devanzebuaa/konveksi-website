@extends('layouts.admin')

@section('content')
<div class="container py-4">
    <h2 class="mb-4 fw-bold">Manajemen Testimoni</h2>

    {{-- Tombol kembali ke dashboard --}}
    <a href="{{ route('admin.dashboard') }}" class="btn btn-sm btn-secondary mb-3">
        <i class="fas fa-arrow-left"></i> Kembali ke Dashboard
    </a>

    {{-- Notifikasi sukses --}}
    @if (session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    {{-- Tabel testimoni --}}
    @if ($testimonials->isEmpty())
        <div class="alert alert-info text-center">
            Belum ada testimoni dari pelanggan.
        </div>
    @else
        <div class="table-responsive">
            <table class="table table-bordered table-striped align-middle">
                <thead class="table-light text-center">
                    <tr>
                        <th>Nama</th>
                        <th>Rating</th>
                        <th>Komentar</th>
                        <th>Foto</th>
                        <th width="150">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($testimonials as $testimonial)
                        <tr>
                            <td>{{ $testimonial->name }}</td>
                            <td class="text-center">{{ $testimonial->rating }} ⭐</td>
                            <td>{{ $testimonial->comment }}</td>
                            <td class="text-center">
                                @if ($testimonial->photo)
                                    <img src="{{ asset('storage/' . $testimonial->photo) }}" alt="Foto" width="50" class="rounded">
                                @else
                                    <span class="text-muted">-</span>
                                @endif
                            </td>
                            <td class="text-center">
                                <a href="{{ route('admin.testimonials.edit', $testimonial->id) }}" class="btn btn-warning btn-sm mb-1">Edit</a>
                                <form action="{{ route('admin.testimonials.destroy', $testimonial->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Yakin ingin menghapus testimoni ini?')">
                                    @csrf
                                    @method('DELETE')
                                    <button class="btn btn-danger btn-sm">Hapus</button>
                                </form>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    @endif
</div>
@endsection
