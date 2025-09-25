<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Asset;
use App\Models\Kategori;
use App\Models\Lokasi;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;
use SimpleSoftwareIO\QrCode\Facades\QrCode;

class AssetController extends Controller
{
    public function index()
    {
        $assets = Asset::with(['kategori','lokasi'])->latest()->paginate(15);
        $kategoris = Kategori::all();
        $lokasis = Lokasi::all();
        return view('asset.index', compact('assets','kategoris','lokasis'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'nama' => 'required|string|max:255',
            'kategori_id' => 'nullable|exists:kategori,id',
            'lokasi_id' => 'nullable|exists:lokasi,id',
            'spesifikasi' => 'nullable|string',
            'kondisi' => 'required|in:Baik,Rusak Ringan,Rusak Berat',
            'foto' => 'nullable|image|max:2048',
        ]);

        // generate kode unik
        $kode = 'AST-' . now()->format('Ymd') . '-' . Str::upper(Str::random(6));
        $data['kode'] = $kode;

        if ($request->hasFile('foto')) {
            $path = $request->file('foto')->store('assets', 'public');
            $data['foto'] = $path;
        }

        $asset = Asset::create($data);

        // generate QR code that links to pengajuan create form with asset_id
        try {
            $url = route('pengajuan.create') . '?asset_id=' . $asset->id;
            $qrImage = QrCode::format('svg')->size(300)->generate($url);
            $qrPath = 'qr/asset-' . $asset->id . '.svg';
            Storage::disk('public')->put($qrPath, $qrImage);
            $asset->qr_path = $qrPath;
            $asset->save();
        } catch (\Throwable $e) {
            // silently ignore QR failure
        }

        // generate an offline-capable QR (data:text/html;base64 payload)
        try {
            $offlineHtml = '<!doctype html><html><head><meta charset="utf-8"><meta name="viewport" content="width=device-width,initial-scale=1"><title>Pengajuan Asset</title><style>body{font-family:sans-serif;padding:12px}label{display:block;margin-top:8px}input,textarea{width:100%;box-sizing:border-box;padding:6px}</style></head><body><h2>Pengajuan Asset</h2><form id="pengajuanForm"><input type="hidden" name="asset_id" value="' . e($asset->id) . '"><label><strong>Nama</strong><div>' . e($asset->nama) . '</div></label><label>Catatan:<textarea name="catatan" rows="4"></textarea></label><button type="submit">Kirim (offline test)</button></form><script>document.getElementById("pengajuanForm").addEventListener("submit",function(e){e.preventDefault();var a=this.asset_id.value;var c=this.catatan.value;alert("Pengajuan (offline)\nasset_id="+a+"\ncatatan="+c);});</script></body></html>';
            $dataUri = 'data:text/html;base64,' . base64_encode($offlineHtml);
            $offlineQrSvg = QrCode::format('svg')->size(300)->generate($dataUri);
            $offlinePath = 'qr/offline-asset-' . $asset->id . '.svg';
            Storage::disk('public')->put($offlinePath, $offlineQrSvg);
            $asset->offline_qr_path = $offlinePath;
            $asset->save();
        } catch (\Throwable $e) {
            // ignore offline QR failures
        }

        // generate standalone offline HTML and a short token QR for full offline usage
        try {
            $assetIdEsc = e($asset->id);
            $assetNamaEsc = e($asset->nama);
            $assetKodeEsc = e($asset->kode ?? '');
            $html = <<<HTML
<!doctype html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <title>Pengajuan Asset {$assetIdEsc}</title>
    <style>body{font-family:sans-serif;padding:12px}label{display:block;margin-top:8px}input,textarea{width:100%;box-sizing:border-box;padding:6px}button{margin-top:9px;padding:8px 12px}.top-links{margin-bottom:10px}</style>
</head>
<body>
    <div class="top-links"><a href="offline-submissions.html">Lihat Pengajuan Offline</a> | <a href="offline-index.html">Index</a></div>
    <h2>Pengajuan Asset</h2>
    <div><strong>Kode:</strong> {$assetKodeEsc}</div>
    <div><strong>Nama:</strong> {$assetNamaEsc}</div>
    <form id="pengajuanForm">
        <input type="hidden" name="asset_id" value="{$assetIdEsc}">
        <label>Nama Pengaju:<input name="nama_pengaju"></label>
        <label>Catatan:<textarea name="catatan" rows="4"></textarea></label>
        <button type="submit">Simpan (offline)</button>
    </form>
    <script>
        document.getElementById('pengajuanForm').addEventListener('submit', function(e){
            e.preventDefault();
            var key = 'offline_pengajuan_' + Date.now();
            var payload = { asset_id: this.asset_id.value, nama_pengaju: this.nama_pengaju.value, catatan: this.catatan.value, ts: Date.now() };
            try { localStorage.setItem(key, JSON.stringify(payload)); alert('Tersimpan offline (kunci: ' + key + ')\nAnda bisa sinkronisasi nanti.'); window.location.href = 'offline-submissions.html'; }
            catch(err) { alert('Gagal menyimpan: ' + err); }
        });
    </script>
</body>
</html>
HTML;
            $htmlPath = 'qr/offline-asset-' . $asset->id . '.html';
            Storage::disk('public')->put($htmlPath, $html);

            $token = 'ASSET:' . $asset->id;
            $tokenSvg = QrCode::format('svg')->size(300)->generate($token);
            $tokenPath = 'qr/token-asset-' . $asset->id . '.svg';
            Storage::disk('public')->put($tokenPath, $tokenSvg);
        } catch (\Throwable $e) {
            // ignore
        }

        return redirect()->route('asset.index')->with('success', 'Asset berhasil ditambahkan');
    }

    public function show(Asset $asset)
    {
        return view('asset.show', compact('asset'));
    }

    public function edit(Asset $asset)
    {
        $kategoris = Kategori::all();
        $lokasis = Lokasi::all();
        return view('asset.edit', compact('asset','kategoris','lokasis'));
    }

    public function update(Request $request, Asset $asset)
    {
        $data = $request->validate([
            'nama' => 'required|string|max:255',
            'kategori_id' => 'nullable|exists:kategori,id',
            'lokasi_id' => 'nullable|exists:lokasi,id',
            'spesifikasi' => 'nullable|string',
            'kondisi' => 'required|in:Baik,Rusak Ringan,Rusak Berat',
            'foto' => 'nullable|image|max:2048',
        ]);

        if ($request->hasFile('foto')) {
            // delete old foto
            if ($asset->foto) {
                Storage::disk('public')->delete($asset->foto);
            }
            $path = $request->file('foto')->store('assets', 'public');
            $data['foto'] = $path;
        }

        $asset->update($data);

        // regenerate QR code that links to pengajuan create form with asset_id
        try {
            $url = route('pengajuan.create') . '?asset_id=' . $asset->id;
            $qrImage = QrCode::format('svg')->size(300)->generate($url);
            $qrPath = 'qr/asset-' . $asset->id . '.svg';
            Storage::disk('public')->put($qrPath, $qrImage);
            $asset->qr_path = $qrPath;
            $asset->save();
        } catch (\Throwable $e) {
            // ignore
        }

        // regenerate offline-capable QR
        try {
            $offlineHtml = '<!doctype html><html><head><meta charset="utf-8"><meta name="viewport" content="width=device-width,initial-scale=1"><title>Pengajuan Asset</title><style>body{font-family:sans-serif;padding:12px}label{display:block;margin-top:8px}input,textarea{width:100%;box-sizing:border-box;padding:6px}</style></head><body><h2>Pengajuan Asset</h2><form id="pengajuanForm"><input type="hidden" name="asset_id" value="' . e($asset->id) . '"><label><strong>Nama</strong><div>' . e($asset->nama) . '</div></label><label>Catatan:<textarea name="catatan" rows="4"></textarea></label><button type="submit">Kirim (offline test)</button></form><script>document.getElementById("pengajuanForm").addEventListener("submit",function(e){e.preventDefault();var a=this.asset_id.value;var c=this.catatan.value;alert("Pengajuan (offline)\nasset_id="+a+"\ncatatan="+c);});</script></body></html>';
            $dataUri = 'data:text/html;base64,' . base64_encode($offlineHtml);
            $offlineQrSvg = QrCode::format('svg')->size(300)->generate($dataUri);
            $offlinePath = 'qr/offline-asset-' . $asset->id . '.svg';
            Storage::disk('public')->put($offlinePath, $offlineQrSvg);
            $asset->offline_qr_path = $offlinePath;
            $asset->save();
        } catch (\Throwable $e) {
            // ignore
        }

        return redirect()->route('asset.index')->with('success', 'Asset berhasil diperbarui');
    }

    public function destroy(Asset $asset)
    {
        if ($asset->foto) {
            Storage::disk('public')->delete($asset->foto);
        }
        if ($asset->qr_path) {
            Storage::disk('public')->delete($asset->qr_path);
        }
        if ($asset->offline_qr_path) {
            Storage::disk('public')->delete($asset->offline_qr_path);
        }
        $asset->delete();
        return redirect()->route('asset.index')->with('success', 'Asset dihapus');
    }
}
