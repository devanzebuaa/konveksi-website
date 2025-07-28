@extends('layouts.app')

@section('content')
<div class="container">
    <h3>Chat Order #{{ $order->id }}</h3>
    <div class="card mb-3">
        <div class="card-body" style="max-height:300px; overflow-y:auto;">
            @forelse($messages as $msg)
                <div class="d-flex mb-2 {{ $msg->sender_type === 'admin' ? 'justify-content-end' : 'justify-content-start' }}">
                    <div style="max-width:70%;" class="{{ $msg->sender_type === 'admin' ? 'text-end' : '' }}">
                        <div class="p-2 rounded-3 mb-1 {{ $msg->sender_type === 'admin' ? 'bg-primary text-white' : 'bg-light border' }}" style="display:inline-block;">
                            <strong style="font-size:13px;">{{ $msg->sender->name ?? ($msg->sender_type === 'admin' ? 'Admin' : 'User') }}</strong><br>
                            <span style="font-size:15px;">{{ $msg->content }}</span>
                        </div>
                        <small class="text-muted d-block mt-1" style="font-size:11px;">
                            {{ $msg->created_at->format('d M Y H:i') }}
                        </small>
                    </div>
                </div>
            @empty
                <p>Belum ada pesan.</p>
            @endforelse
        </div>
    </div>
    <form action="{{ isset($isAdmin) && $isAdmin ? route('admin.custom-orders.chat.send', $order->id) : route('custom-order.chat.send', $order->id) }}" method="POST">
        @csrf
        <div class="input-group">
            <input type="text" name="content" class="form-control" placeholder="Tulis pesan..." required>
            <button class="btn btn-primary" type="submit">Kirim</button>
        </div>
    </form>
    <a href="{{ isset($isAdmin) && $isAdmin ? route('admin.custom-orders.index') : route('custom-order.form') }}" class="btn btn-primary mt-3">
        Kembali ke {{ isset($isAdmin) && $isAdmin ? 'Daftar Custom Order (Admin)' : 'Daftar Custom Order' }}
    </a>
</div>
@endsection
