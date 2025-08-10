@extends('layouts.app')

@section('content')
<div class="container">
    <h2>Detail Kategori</h2>

    <div class="mb-3">
        <strong>Kode:</strong> {{ $kategori->kode }}
    </div>
    <div class="mb-3">
        <strong>Nama:</strong> {{ $kategori->nama }}
    </div>

    <h4>Daftar Items</h4>
    @if($kategori->masterItems->count())
        <ul class="list-group">
            @foreach($kategori->masterItems as $item)
                <li class="list-group-item">{{ $item->nama }}</li>
            @endforeach
        </ul>
    @else
        <p><em>Belum ada item di kategori ini.</em></p>
    @endif

    <a href="{{ route('kategori-items.index') }}" class="btn btn-secondary mt-3">Kembali</a>
</div>
@endsection
