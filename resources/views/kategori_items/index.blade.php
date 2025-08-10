@extends('layouts.app')

@section('content')
<div class="container">
    <h2>Daftar Kategori Items</h2>

    {{-- Filter Form --}}
    <form id="filter-form" class="row g-3 mb-4">
        <div class="col-md-4">
            <label>Nama</label>
            <input type="text" name="nama" class="form-control" placeholder="Cari Nama">
        </div>
        <div class="col-md-4">
            <label>Kode</label>
            <input type="text" name="kode" class="form-control" placeholder="Cari Kode">
        </div>
        <div class="col-md-4 align-self-end">
            <button type="submit" class="btn btn-primary">Filter</button>
            <a href="{{ route('kategori-items.form', ['method' => 'new']) }}" class="btn btn-success">Tambah</a>
        </div>
    </form>

    {{-- Table --}}
    <table class="table table-bordered">
        <thead>
            <tr>
                <th>Kode</th>
                <th>Nama</th>
                <th>Total Items</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody id="table-body">
            {{-- Data akan di-load via AJAX --}}
        </tbody>
    </table>
</div>
@endsection

@section('js')
<script>
    function loadData() {
        $.get("{{ route('kategori-items.search') }}", $('#filter-form').serialize(), function(res) {
            let rows = '';
            res.data.forEach(item => {
                rows += `
                    <tr>
                        <td>${item.kode}</td>
                        <td>${item.nama}</td>
                        <td>${item.master_items_count}</td>
                        <td>
                            <a href="{{ url('kategori-items/view') }}/${item.id}" class="btn btn-info btn-sm">View</a>
                            <a href="{{ url('kategori-items/form/edit') }/${item.id}" class="btn btn-warning btn-sm">Edit</a>
                            <form action="{{ url('kategori-items/delete') }}/${item.id}" method="POST" style="display:inline-block;">
                                @csrf
                                @method('DELETE')
                                <button class="btn btn-danger btn-sm" onclick="return confirm('Yakin?')">Hapus</button>
                            </form>
                        </td>
                    </tr>
                `;
            });
            $('#table-body').html(rows);
        });
    }

    $('#filter-form').on('submit', function(e) {
        e.preventDefault();
        loadData();
    });

    $(document).ready(loadData);
</script>
@endsection
