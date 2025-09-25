@extends('layouts.app')
@section('title', 'Detail Asset')
@section('content')
<div class="container-fluid">
    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h5 class="mb-0">Detail Asset</h5>
            <a href="{{ route('asset.index') }}" class="btn btn-secondary btn-sm">Kembali</a>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-md-4">
                    @if($asset->foto)
                        <img src="{{ url('storage/'.$asset->foto) }}" class="img-fluid rounded" alt="foto" />
                    @endif
                    <div class="mt-3">
                        @if($asset->qr_path)
                            <img src="{{ url('storage/'.$asset->qr_path) }}" class="img-fluid" alt="qr" style="max-width:320px; height:auto; object-fit:contain;" />
                            <div class="small text-muted">Scan untuk membuka form pengajuan</div>
                        @endif
                    </div>
                </div>
                <div class="col-md-8">
                    <table class="table table-borderless">
                        <tr><th>Kode</th><td>{{ $asset->kode }}</td></tr>
                        <tr><th>Nama</th><td>{{ $asset->nama }}</td></tr>
                        <tr><th>Kategori</th><td>{{ optional($asset->kategori)->nama_kategori }}</td></tr>
                        <tr><th>Lokasi</th><td>{{ optional($asset->lokasi)->nama_lokasi }}</td></tr>
                        <tr><th>Spesifikasi</th><td>{{ $asset->spesifikasi }}</td></tr>
                        <tr><th>Kondisi</th><td>{{ $asset->kondisi }}</td></tr>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
