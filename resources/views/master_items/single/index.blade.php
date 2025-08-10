@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="form-group mb-2">
                <a href="{{ url('master-items') }}" class="btn btn-secondary">Kembali ke Daftar Item</a>
            </div>
            <div class="card">
                <div class="card-header">Master Item</div>

                <div class="card-body">
                    <table class="table table-bordered">
                        <tr>
                            <th>Nama</th>
                            <td>{{ $data->nama }}</td>
                        </tr>
                        <tr>
                            <th>Harga Beli</th>
                            <td>{{ number_format($data->harga_beli, 0, ',', '.') }}</td>
                        </tr>
                        <tr>
                            <th>Laba (%)</th>
                            <td>{{ $data->laba }}</td>
                        </tr>
                        <tr>
                            <th>Harga Jual</th>
                            <td>{{ number_format($data->harga_beli + ($data->harga_beli * $data->laba / 100), 0, ',', '.') }}</td>
                        </tr>
                        <tr>
                            <th>Supplier</th>
                            <td>{{ $data->supplier }}</td>
                        </tr>
                        <tr>
                            <th>Jenis</th>
                            <td>{{ $data->jenis }}</td>
                        </tr>
                        @if($data->foto)
                        <tr>
                            <th>Foto</th>
                            <td>
                                <img src="{{ asset('storage/' . $data->foto) }}" alt="Foto {{ $data->nama }}" style="max-width: 150px; max-height: 150px;">
                            </td>
                        </tr>
                        @endif
                    </table>

                    <a class="btn btn-info" href="{{ url('master-items/form/edit', $data->id) }}">Edit</a>
                    <a class="btn btn-danger" href="{{ url('master-items/delete', $data->id) }}" onclick="return confirm('Are you sure you want to delete this item?');">Delete</a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('js')
@endsection
