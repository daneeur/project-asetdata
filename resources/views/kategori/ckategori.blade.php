    @extends('layouts.app')

@section('content')
<div class="container">
    <h3 class="mb-4">Tambah Kategori</h3>

    <div class="card">
        <div class="card-header">
            <h5>Form Tambah Kategori</h5>
        </div>
        <div class="card-body">
            <form action="{{ route('kategori.store') }}" method="POST">
                @csrf
                <div class="mb-3">
                    <label for="nama_kategori" class="form-label">Nama Kategori</label>
                    <input type="text" name="nama_kategori" id="nama_kategori" class="form-control" required>

                    @error('nama_kategori')
                        <div class="text-danger mt-2">{{ $message }}</div>
                    @enderror
                </div>

                <a href="{{ route('kategori.index') }}" class="btn btn-secondary">Kembali</a>
                <button type="submit" class="btn btn-primary">Simpan</button>
            </form>
        </div>
    </div>
</div>
@endsection
