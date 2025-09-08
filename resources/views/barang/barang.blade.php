@extends('layouts.app')

@section('content')
<div class="container">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h3>Barang</h3>
        <a href="{{ route('barang.create') }}" class="btn btn-primary">
            <i class="fa fa-plus"></i> Tambah Data Barang
        </a>
    </div>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <div class="card">
        <div class="card-header">
            <h5>Informasi Barang</h5>
        </div>
        <div class="card-body">
            <table class="table table-bordered table-striped" id="barangTable">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Nama Barang</th>
                        <th>Keterangan Barang</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($barang as $index => $item)
                        <tr>
                            <td>{{ ($barang->currentPage() - 1) * $barang->perPage() + $index + 1 }}</td>
                            <td>{{ $item->nama }}</td>
                            <td>{{ $item->keterangan }}</td>
                            <td>
                                <form action="{{ route('barang.destroy', $item->id) }}" method="POST" class="d-inline">
                                    @csrf
                                    @method('DELETE')
                                    <button onclick="return confirm('Yakin hapus data?')" class="btn btn-danger btn-sm">
                                        <i class="fa fa-trash"></i>
                                    </button>
                                </form>
                                <a href="{{ route('barang.edit', $item->id) }}" class="btn btn-warning btn-sm">
                                    <i class="fa fa-edit"></i>
                                </a>
                            </td>
                        </tr>
                    @endforeach

                    @if($barang->isEmpty())
                        <tr>
                            <td colspan="4" class="text-center text-muted">Belum ada data barang.</td>
                        </tr>
                    @endif
                </tbody>
            </table>

            <!-- Pagination Laravel -->
            <div class="mt-3">
                {{ $barang->links('pagination::bootstrap-4') }}
            </div>
        </div>
    </div>
</div>
@endsection
