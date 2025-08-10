@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="form-group mb-2">
                <a href="{{ url('master-items') }}" class="btn btn-secondary">Kembali ke Daftar Item</a>
            </div>
            <div class="card">
                <div class="card-header">Detail Master Item</div>

                <div class="card-body">
                    @if ($data)
                    <table id="table" class="table table-striped" style="width:100%">
                        <thead>
                            <tr>
                                <th>Kode</th>
                                <th>Nama</th>
                                <th>Jenis</th>
                                <th>Harga Beli</th>
                                <th>Harga Jual</th>
                                <th>Supplier</th>
                                <th>Foto</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>{{ $data->kode }}</td>
                                <td>{{ $data->nama }}</td>
                                <td>{{ $data->jenis }}</td>
                                <td>{{ number_format($data->harga_beli, 0, ',', '.') }}</td>
                                <td>{{ number_format($data->harga_beli + ($data->harga_beli * $data->laba / 100), 0, ',', '.') }}</td>
                                <td>{{ $data->supplier }}</td>
                                <td>
                                    @if($data->foto)
                                    <img src="{{ asset('storage/' . $data->foto) }}" alt="Foto {{ $data->nama }}" style="max-width: 100px; max-height: 100px;">
                                    @else
                                    -
                                    @endif
                                </td>
                                <td>
                                    <a href="{{ url('master-items/form/edit', $data->id) }}" class="btn btn-info btn-sm">Edit</a>
                                    <a href="{{ url('master-items/delete', $data->id) }}" class="btn btn-danger btn-sm" onclick="return confirm('Are you sure you want to delete this item?');">Delete</a>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                    @else
                    <div class="alert alert-warning">
                        Data item tidak ditemukan.
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('js')
@endsection
