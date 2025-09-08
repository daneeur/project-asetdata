<?php
use App\Http\Controllers\KategoriController;
use App\Http\Controllers\LokasiController;
use App\Http\Controllers\ProfilController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});
Route::get('/dashboard', function () {
    return view('welcome');
})->name('dashboard');
Route::get('/profil', [ProfilController::class, 'show'])->name('profil.show');
Route::resource('kategori', KategoriController::class);
Route::post('/logout', function() {
    Auth::logout();
    return redirect('/');
})->name('logout');
Route::put('/profil', [ProfilController::class, 'update'])->name('profil.update');
Route::resource('lokasi', LokasiController::class);

Route::resource('barang', App\Http\Controllers\BarangController::class);

Route::resource('asset', App\Http\Controllers\AssetController::class);

Route::resource('pengajuan', App\Http\Controllers\PengajuanController::class);

Route::resource('monitoring', App\Http\Controllers\MonitoringController::class);

