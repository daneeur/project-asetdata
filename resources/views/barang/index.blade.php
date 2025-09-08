@extends('layouts.app')
@section('title', 'Data Barang')
@section('content')
<div class="container-fluid">
    <div class="card">
        <div class="card-header bg-white d-flex justify-content-between align-items-center">
            <h5 class="mb-0">Data Barang</h5>
            <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#createModal">
                <i class="fa fa-plus me-2"></i>Tambah Barang
            </button>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered table-hover">
                    <thead class="table-light">
                        <tr>
                            <th>No</th>
                            <th>Nama Barang</th>
                            <th>Kategori</th>
                            <th>Spesifikasi</th>
                            <th>Kondisi</th>
                            <th width="200">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        {{-- Loop barang data here --}}
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
<!-- Modal Create/Edit/Delete can be added here for full CRUD -->
@endsection
