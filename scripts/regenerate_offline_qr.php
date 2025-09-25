<?php
require __DIR__ . '/../vendor/autoload.php';
$app = require_once __DIR__ . '/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\Asset;
use Illuminate\Support\Facades\Storage;
use SimpleSoftwareIO\QrCode\Facades\QrCode;

$assets = Asset::all();
if ($assets->isEmpty()) {
    echo "No assets found\n";
    exit;
}

foreach ($assets as $asset) {
    $need = false;
    if (!$asset->offline_qr_path) {
        $need = true;
    } else {
        if (!Storage::disk('public')->exists($asset->offline_qr_path)) {
            $need = true;
        }
    }
    if (!$need) {
        echo "Asset {$asset->id} already has offline QR and file exists.\n";
        continue;
    }

    $offlineHtml = '<!doctype html><html><head><meta charset="utf-8"><meta name="viewport" content="width=device-width,initial-scale=1"><title>Pengajuan Asset</title><style>body{font-family:sans-serif;padding:12px}label{display:block;margin-top:8px}input,textarea{width:100%;box-sizing:border-box;padding:6px}</style></head><body><h2>Pengajuan Asset</h2><form id="pengajuanForm"><input type="hidden" name="asset_id" value="' . e($asset->id) . '"><label><strong>Nama</strong><div>' . e($asset->nama) . '</div></label><label>Catatan:<textarea name="catatan" rows="4"></textarea></label><button type="submit">Kirim (offline test)</button></form><script>document.getElementById("pengajuanForm").addEventListener("submit",function(e){e.preventDefault();var a=this.asset_id.value;var c=this.catatan.value;alert("Pengajuan (offline)\nasset_id="+a+"\ncatatan="+c);});</script></body></html>';
    $dataUri = 'data:text/html;base64,' . base64_encode($offlineHtml);
    try {
        $offlineQrSvg = QrCode::format('svg')->size(300)->generate($dataUri);
        $offlinePath = 'qr/offline-asset-' . $asset->id . '.svg';
        Storage::disk('public')->put($offlinePath, $offlineQrSvg);
        $asset->offline_qr_path = $offlinePath;
        $asset->save();
        echo "Generated offline QR for asset {$asset->id} -> {$offlinePath}\n";
    } catch (\Throwable $e) {
        echo "Failed to generate offline QR for asset {$asset->id}: " . $e->getMessage() . "\n";
    }
}

// Additionally: generate a standalone offline HTML file per asset and a short-token QR
echo "\nGenerating standalone offline HTML pages and short-token QR images...\n";
$assets = Asset::all();
foreach ($assets as $asset) {
    try {
                // standalone HTML file (can be opened directly on phone)
                $assetIdEsc = e($asset->id);
                $assetKodeEsc = e($asset->kode);
                $assetNamaEsc = e($asset->nama);
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
        <label>Catatan:<textarea name="catatan" rows="4"></textarea></label>
        <button type="submit">Simpan (offline)</button>
    </form>
    <script>
        document.getElementById('pengajuanForm').addEventListener('submit', function(e){
            e.preventDefault();
            var key = 'offline_pengajuan_' + Date.now();
            var payload = { asset_id: this.asset_id.value, catatan: this.catatan.value, ts: Date.now() };
            try { localStorage.setItem(key, JSON.stringify(payload)); alert('Tersimpan offline (kunci: ' + key + ')\nAnda bisa sinkronisasi nanti.'); window.location.href = 'offline-submissions.html'; }
            catch(err){ alert('Gagal menyimpan: ' + err); }
        });
    </script>
</body>
</html>
HTML;
                $htmlPath = 'qr/offline-asset-' . $asset->id . '.html';
                Storage::disk('public')->put($htmlPath, $html);

        // short token QR (easy to copy/paste), content: ASSET:{id}
        $token = 'ASSET:' . $asset->id;
        $tokenSvg = QrCode::format('svg')->size(300)->generate($token);
        $tokenPath = 'qr/token-asset-' . $asset->id . '.svg';
        Storage::disk('public')->put($tokenPath, $tokenSvg);

        echo "Wrote HTML {$htmlPath} and token QR {$tokenPath} for asset {$asset->id}\n";
    } catch (\Throwable $e) {
        echo "Failed standalone files for asset {$asset->id}: " . $e->getMessage() . "\n";
    }
}

// Generate an index page that helps paste a scanned token (for scanners that only return text)
echo "\nGenerating offline index page...\n";
$indexItems = '';
foreach (Asset::all() as $asset) {
    $indexItems .= '<li><a href="offline-asset-' . $asset->id . '.html">Asset ' . $asset->id . ' &ndash; ' . e($asset->nama) . '</a> &nbsp; <a href="token-asset-' . $asset->id . '.svg">[QR]</a></li>';
}
$indexHtml = <<<HTML
<!doctype html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <title>Offline Pengajuan Index</title>
    <style>body{font-family:sans-serif;padding:12px}input,button{padding:8px;margin-top:6px}li{margin-bottom:6px}</style>
</head>
<body>
    <h2>Offline Pengajuan</h2>
    <p>Jika scanner Anda hanya menampilkan teks QR, scan lalu copy teks ke field di bawah dan tekan "Buka". Atau klik salah satu asset untuk membuka form langsung.</p>
    <input id="tokenInput" placeholder="Paste hasil scan (mis. ASSET:4)" style="width:80%">
    <button onclick="openFromToken()">Buka</button>
    <ul>
        {$indexItems}
    </ul>
    <script>
        function openFromToken(){
            var v=document.getElementById('tokenInput').value.trim();
            if(!v){ alert('Masukkan token QR (ASSET:ID)'); return; }
            var m=v.match(/^ASSET:(\d+)$/i);
            if(!m){ alert('Token tidak valid'); return; }
            var id=m[1];
            window.location.href='offline-asset-'+id+'.html';
        }
    </script>
</body>
</html>
HTML;
Storage::disk('public')->put('qr/offline-index.html', $indexHtml);
echo "Offline index created at storage/qr/offline-index.html\n";

// Create an offline submissions viewer page that reads localStorage keys and lists them
echo "Generating offline submissions viewer...\n";
$submissionsHtml = <<<'HTML'
<!doctype html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <title>Pengajuan Offline - Viewer</title>
    <style>body{font-family:sans-serif;padding:12px}button,input,textarea{padding:6px;margin:6px 0}pre{background:#f4f4f4;padding:8px;border-radius:4px}</style>
</head>
<body>
    <h2>Pengajuan Offline (tersimpan di browser)</h2>
    <p>Daftar pengajuan yang tersimpan secara offline. Anda bisa menghapus atau menyalin isinya untuk sinkronisasi ke server nanti.</p>
    <div id="list"></div>
    <script>
        function loadList(){
            var out=document.getElementById('list');
            out.innerHTML='';
            var keys=Object.keys(localStorage).filter(k=>k.indexOf('offline_pengajuan_')===0).sort();
            if(!keys.length){ out.innerHTML='<p>Tidak ada pengajuan offline.</p>'; return; }
            keys.forEach(function(k){
                try{
                    var obj=JSON.parse(localStorage.getItem(k));
                }catch(e){ var obj=null; }
                var div=document.createElement('div');
                div.style.border='1px solid #ddd'; div.style.padding='8px'; div.style.marginBottom='8px';
                var h=document.createElement('div'); h.innerHTML='<strong>'+k+'</strong> (asset_id: '+(obj?obj.asset_id:'-')+')'; div.appendChild(h);
                var pre=document.createElement('pre'); pre.textContent = JSON.stringify(obj, null, 2); div.appendChild(pre);
                var btnDel=document.createElement('button'); btnDel.textContent='Hapus'; btnDel.onclick=function(){ if(confirm('Hapus '+k+' ?')){ localStorage.removeItem(k); loadList(); } };
                var btnCopy=document.createElement('button'); btnCopy.textContent='Salin JSON'; btnCopy.onclick=function(){ navigator.clipboard.writeText(JSON.stringify(obj)).then(function(){ alert('Disalin ke clipboard'); }, function(){ alert('Gagal menyalin'); }); };
                div.appendChild(btnCopy); div.appendChild(btnDel);
                out.appendChild(div);
            });
        }
        loadList();
    </script>
</body>
</html>
HTML;
Storage::disk('public')->put('qr/offline-submissions.html', $submissionsHtml);
echo "Offline submissions viewer created at storage/qr/offline-submissions.html\n";
