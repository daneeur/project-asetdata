@extends('layouts.app')
@section('title', 'Data Asset')
@section('content')
<div class="container-fluid">
    <div class="card">
        <div class="card-header bg-white d-flex justify-content-between align-items-center">
            <h5 class="mb-0">Data Asset</h5>
            <div>
                <button class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#modalTambah"> <i class="fa fa-plus"></i> Tambah Asset</button>
            </div>
        </div>
        <div class="card-body">
            @if(session('success'))
                <div class="alert alert-success">{{ session('success') }}</div>
            @endif

            {{-- modal moved down to end of page to avoid transform stacking issues --}}

            <!-- Tabel Asset -->
            <div class="table-responsive">
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Kode</th>
                            <th>Nama</th>
                            <th>Kategori</th>
                            <th>Lokasi</th>
                            <th>Foto</th>
                            <th>Scan</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($assets as $a)
                            <tr>
                                <td>{{ $loop->iteration + ($assets->currentPage()-1)*$assets->perPage() }}</td>
                                <td>{{ $a->kode }}</td>
                                <td>{{ $a->nama }}</td>
                                <td>{{ optional($a->kategori)->nama_kategori }}</td>
                                <td>{{ optional($a->lokasi)->nama_lokasi }}</td>
                                <td>
                                    @if($a->foto)
                                        <img src="{{ url('storage/'.$a->foto) }}" alt="foto" style="width:60px; height:60px; object-fit:cover; border-radius:8px;" />
                                    @endif
                                </td>
                                <td>
                                    @if($a->qr_path)
                                        <img src="{{ url('storage/'.$a->qr_path) }}" alt="qr" style="width:80px; height:80px; object-fit:contain;" />
                                        <div class="small text-muted">Scan untuk pengajuan</div>
                                    @endif
                                </td>
                                <td class="action-buttons">
                                    <a href="{{ route('asset.show', $a->id) }}" class="btn btn-sm btn-primary" title="Lihat"><i class="fa fa-eye"></i></a>
                                    <a href="{{ route('asset.edit', $a->id) }}" class="btn btn-sm btn-warning" title="Edit"><i class="fa fa-edit"></i></a>
                                    <button class="btn btn-sm btn-danger btn-delete-asset" data-id="{{ $a->id }}" data-name="{{ e($a->nama) }}" data-foto="{{ $a->foto ? url('storage/'.$a->foto) : '' }}" data-bs-toggle="modal" data-bs-target="#modalDeleteAsset" title="Hapus"><i class="fa fa-trash"></i></button>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="8" class="text-center">Belum ada data asset.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="mt-3">
                {{ $assets->links() }}
            </div>

@push('scripts')
<script>
    (function(){
        // helper to safely query element
        const $ = id => document.getElementById(id);
        const inputFoto = $('inputFoto');
        const previewFoto = $('previewFoto');
        const previewPlaceholder = $('previewPlaceholder');
        const fotoError = $('fotoError');
        const form = $('formTambahAsset');
        const btnClear = $('btnClearForm');
        const btnSubmit = $('btnSubmitForm');
        const modalEl = $('modalTambah');

        function safeDisplay(el, show){ if(!el) return; el.style.display = show ? '' : 'none'; }

        function resetPreview() {
            try{
                if(previewFoto){ previewFoto.src = ''; previewFoto.style.display = 'none'; }
                if(previewPlaceholder) previewPlaceholder.style.display = 'block';
                if(fotoError) fotoError.style.display = 'none';
                if(inputFoto) inputFoto.value = '';
            }catch(e){ console.warn('resetPreview error', e); }
        }

        if(inputFoto){
            inputFoto.addEventListener('change', function(e){
                try{
                    if(fotoError) fotoError.style.display = 'none';
                    const file = e.target.files[0];
                    if(!file) { resetPreview(); return; }
                    // validate type
                    if(!file.type || !file.type.startsWith('image/')){
                        if(fotoError){ fotoError.textContent = 'File bukan gambar.'; fotoError.style.display = 'block'; }
                        resetPreview();
                        return;
                    }
                    // validate size (2MB)
                    if(file.size > 2 * 1024 * 1024){
                        if(fotoError){ fotoError.textContent = 'Ukuran file terlalu besar (maks 2MB).'; fotoError.style.display = 'block'; }
                        resetPreview();
                        return;
                    }
                    const reader = new FileReader();
                    reader.onload = function(ev){
                        if(previewFoto){ previewFoto.src = ev.target.result; previewFoto.style.display = 'block'; }
                        if(previewPlaceholder) previewPlaceholder.style.display = 'none';
                    }
                    reader.readAsDataURL(file);
                }catch(err){ console.error('file change handler error', err); }
            });
        }

        if(btnClear && form){
            btnClear.addEventListener('click', function(){
                resetPreview();
                try{ form.reset(); }catch(e){/* ignore */}
            });
        }

        if(form){
            form.addEventListener('submit', function(){
                try{
                    if(btnSubmit){ btnSubmit.disabled = true; btnSubmit.textContent = 'Menyimpan...'; }
                }catch(e){ /* ignore */ }
            });
        }

        if(modalEl){
            try{
                modalEl.addEventListener('hidden.bs.modal', function(){
                    resetPreview();
                    try{ if(form) form.reset(); }catch(e){}
                    try{ if(btnSubmit){ btnSubmit.disabled = false; btnSubmit.textContent = 'Simpan'; } }catch(e){}
                });

                modalEl.addEventListener('show.bs.modal', function(){
                    if(fotoError) fotoError.style.display = 'none';
                });
            }catch(e){ console.warn('modal event binding failed', e); }
        }
    })();
</script>
@endpush
        </div>
    </div>
</div>
<!-- Improved Modal Tambah (moved out of card to avoid flicker) -->
<style>
    /* Make the modal wider on large screens while staying responsive */
    .modal-dialog.modal-xl.modal-wide {
        max-width: 1100px; /* reduced width to make modal shorter */
    }
    @media (max-width: 1199px) {
        .modal-dialog.modal-xl.modal-wide {
            max-width: calc(100% - 1rem);
            margin: 0.5rem;
        }
    }
</style>
<div class="modal fade" id="modalTambah" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-xl modal-wide">
        <div class="modal-content">
            <form id="formTambahAsset" action="{{ route('asset.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title">Tambah Asset Baru</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-9">
                            <div class="mb-3">
                                <label class="form-label">Nama Asset <span class="text-danger">*</span></label>
                                <input type="text" name="nama" class="form-control" placeholder="Contoh: Printer Laser HP" required>
                            </div>
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Kategori</label>
                                    <select name="kategori_id" class="form-control">
                                        <option value="">- Pilih Kategori -</option>
                                        @foreach($kategoris as $k)
                                            <option value="{{ $k->id }}">{{ $k->nama_kategori }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Lokasi</label>
                                    <select name="lokasi_id" class="form-control">
                                        <option value="">- Pilih Lokasi -</option>
                                        @foreach($lokasis as $l)
                                            <option value="{{ $l->id }}">{{ $l->nama_lokasi }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Spesifikasi</label>
                                <textarea name="spesifikasi" class="form-control" rows="3" placeholder="Contoh: warna putih, ukuran A4..."></textarea>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Kondisi</label>
                                <select name="kondisi" class="form-control">
                                    <option value="Baik">Baik</option>
                                    <option value="Rusak Ringan">Rusak Ringan</option>
                                    <option value="Rusak Berat">Rusak Berat</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <label class="form-label">Preview Foto</label>
                            <div class="border rounded p-2 text-center" style="min-height:260px; display:flex; align-items:center; justify-content:center;">
                                <img id="previewFoto" src="" alt="preview" style="max-width:100%; max-height:200px; display:none; border-radius:8px;" />
                                <span id="previewPlaceholder" class="text-muted">Belum ada foto yang dipilih</span>
                            </div>
                            <div class="mt-3">
                                <label class="form-label">Pilih Foto (max 2MB)</label>
                                <input id="inputFoto" type="file" name="foto" accept="image/*" class="form-control">
                                <small class="text-muted">JPEG/PNG disarankan.</small>
                                <div class="text-danger mt-1" id="fotoError" style="display:none;"></div>
                            </div>
                            <div class="mt-3 d-flex gap-2">
                                <button id="btnClearForm" type="button" class="btn btn-outline-secondary btn-sm">Reset</button>
                                <button id="btnSubmitForm" type="submit" class="btn btn-primary btn-sm ms-auto">Simpan</button>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <small class="text-muted">Semua field dengan tanda * wajib diisi.</small>
                </div>
            </form>
        </div>
    </div>
</div>

@endsection

<!-- Improved delete confirmation modal -->
<div class="modal fade" id="modalDeleteAsset" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content border-0 overflow-hidden">
            <form id="formDeleteAsset" method="POST" action="#">
                @csrf
                @method('DELETE')
                <div class="modal-header bg-danger text-white align-items-center">
                    <div class="d-flex align-items-center gap-2">
                        <span class="bg-white text-danger rounded-circle d-inline-flex align-items-center justify-content-center" style="width:36px; height:36px;"><i class="fa fa-trash"></i></span>
                        <h5 class="modal-title mb-0">Hapus Asset</h5>
                    </div>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="d-flex gap-3">
                        <div style="width:90px; height:90px; flex:0 0 90px;">
                            <img id="deleteAssetThumb" src="" alt="thumb" style="width:90px; height:90px; object-fit:cover; border-radius:8px; background:#f3f3f3;" />
                        </div>
                        <div class="flex-fill">
                            <p class="mb-1 text-muted small">Apakah anda yakin menghapus data ini?</p>
                            <h6 id="deleteAssetName" class="mb-0"></h6>
                            <p class="small text-muted mt-2">Tindakan ini bersifat permanen dan tidak bisa dikembalikan.</p>
                        </div>
                    </div>
                </div>
                <div class="modal-footer border-0">
                    <button type="button" class="btn btn-light btn-sm" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-danger btn-sm">Hapus</button>
                </div>
            </form>
        </div>
    </div>
    <style>
        /* small modal tweaks to make it look nicer */
        #modalDeleteAsset .modal-content { border-radius: 12px; }
        #modalDeleteAsset .modal-header { padding: 1rem 1rem; }
        #modalDeleteAsset .modal-body { padding: 1rem; }
        #modalDeleteAsset .modal-footer { padding: 0.75rem 1rem; }
    </style>
</div>

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function(){
        const modalEl = document.getElementById('modalDeleteAsset');
        if(!modalEl) return;
        const bsModal = bootstrap.Modal.getOrCreateInstance(modalEl);
        const form = document.getElementById('formDeleteAsset');
        const nameEl = document.getElementById('deleteAssetName');
        const thumb = document.getElementById('deleteAssetThumb');

        document.querySelectorAll('.btn-delete-asset').forEach(function(btn){
            btn.addEventListener('click', function(e){
                const id = this.getAttribute('data-id');
                const name = this.getAttribute('data-name') || '';
                const foto = this.getAttribute('data-foto') || '';
                if(form) form.action = (window.location.origin || '') + '/asset/' + id;
                if(nameEl) nameEl.textContent = name;
                if(thumb) {
                    if(foto) { thumb.src = foto; thumb.style.display = 'block'; }
                    else { thumb.src = ''; thumb.style.display = 'none'; }
                }
                bsModal.show();
            });
        });
    });
</script>
@endpush
