@extends('layouts.app')
@section('title', 'Data Pengajuan')
@section('content')
<div class="container-fluid">
    <div class="card">
        <div class="card-header bg-white d-flex justify-content-between align-items-center">
            <h5 class="mb-0">Data Pengajuan</h5>
        </div>
        <div class="card-body">
            <div class="alert alert-info">Halaman Data Pengajuan. Pengajuan yang muncul adalah yang tersimpan di database.</div>

            <h6>Data Pengajuan (dari database)</h6>
            @php $pengajuans = \App\Models\Pengajuan::latest()->limit(200)->get(); @endphp
            <table class="table table-striped">
                <thead><tr><th>ID</th><th>Asset</th><th>Nama Pengaju</th><th>Catatan</th><th>Status</th><th>Waktu</th></tr></thead>
                <tbody>
                @foreach($pengajuans as $p)
                    <tr>
                        <td>{{ $p->id }}</td>
                        <td>{{ $p->asset_id }}</td>
                        <td>{{ $p->nama_pengaju }}</td>
                        <td>{{ $p->catatan }}</td>
                        <td>{{ $p->status }}</td>
                        <td>{{ $p->created_at }}</td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
