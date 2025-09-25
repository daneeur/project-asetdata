@extends('layouts.app')
@section('title', 'Buat Pengajuan')
@section('content')
<div class="container-fluid">
    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h5 class="mb-0">Form Pengajuan</h5>
            <a href="{{ route('pengajuan.index') }}" class="btn btn-secondary btn-sm">Kembali</a>
        </div>
        <div class="card-body">
            <form action="#" method="POST">
                @csrf
                <div class="mb-3">
                    <label class="form-label">Asset ID</label>
                    <input type="text" name="asset_id" class="form-control" value="{{ old('asset_id', $assetId) }}">
                </div>
                <div class="mb-3">
                    <label class="form-label">Nama Pengaju</label>
                    <input type="text" name="nama" class="form-control" value="{{ old('nama') }}">
                </div>
                <div class="mb-3">
                    <label class="form-label">Catatan</label>
                    <textarea name="catatan" class="form-control">{{ old('catatan') }}</textarea>
                </div>
                <div>
                    <button class="btn btn-primary">Kirim Pengajuan</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
