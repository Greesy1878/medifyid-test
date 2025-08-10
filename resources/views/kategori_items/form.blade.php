@extends('layouts.app')

@section('content')
<div class="container">
    <h2>{{ $method === 'new' ? 'Tambah' : 'Edit' }} Kategori</h2>

    <form action="{{ route('kategori-items.submit', ['method' => $method, 'id' => $kategori->id ?? 0]) }}" method="POST">
        @csrf

        <div class="mb-3">
            <label>Kode</label>
            <input type="text" name="kode" class="form-control" value="{{ old('kode', $kategori->kode) }}" required>
        </div>

        <div class="mb-3">
            <label>Nama</label>
            <input type="text" name="nama" class="form-control" value="{{ old('nama', $kategori->nama) }}" required>
        </div>

        <div class="mb-3">
            <label>Pilih Items</label>
            <select name="items[]" class="form-control">
                @foreach($items as $item)
                    <option value="{{ $item->id }}"
                        {{ in_array($item->id, old('items', $kategori->masterItems->pluck('id')->toArray())) ? 'selected' : '' }}>
                        {{ $item->nama }}
                    </option>
                @endforeach
            </select>
        </div>

        <button type="submit" class="btn btn-primary">Simpan</button>
        <a href="{{ route('kategori-items.index') }}" class="btn btn-secondary">Kembali</a>
    </form>
</div>
@endsection
