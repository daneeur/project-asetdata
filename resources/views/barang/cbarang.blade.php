@extends('layouts.app')

@section('content')
<div class="container">
    <h3 class="mb-4">Tambah Barang</h3>

    <div class="card">
        <div class="card-header">
            <h5>Form Tambah Barang</h5>
        </div>
        <div class="card-body">
            <form action="{{ route('barang.store') }}" method="POST">
                @csrf
                <div class="mb-3">
                    <label for="nama" class="form-label">Nama Barang</label>
                    <input type="text" name="nama" id="nama" class="form-control" required>
                    @error('nama')
                        <div class="text-danger mt-2">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-3">
                    <label for="keterangan" class="form-label">Keterangan Barang</label>
                    <input type="text" name="keterangan" id="keterangan" class="form-control" required>
                    @error('keterangan')
                        <div class="text-danger mt-2">{{ $message }}</div>
                    @enderror
                </div>

                <a href="{{ route('barang.index') }}" class="btn btn-secondary">Kembali</a>
                <button type="submit" class="btn btn-primary">Simpan</button>
            </form>
        </div>
    </div>
</div>
@endsection
