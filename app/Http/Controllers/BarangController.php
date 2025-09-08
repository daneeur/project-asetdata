<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Barang; //import model Barang

class BarangController extends Controller
{
    public function index()
    {
        $barang = Barang::paginate(10); // Pagination 10 per halaman
        return view('barang.index', compact('barang'));
    }

public function create()
{
    return view('cbarang');
}

public function store(Request $request)
{
    $request->validate([
        'nama' => 'required|string|max:255',
        'keterangan' => 'required|string|max:255',
    ]);

    Barang::create($request->all());

    return redirect()->route('cbarang')->with('success', 'Barang berhasil ditambahkan.');
}

public function edit($id)
{
    $barang = Barang::findOrFail($id);
    return view('ebarang', compact('barang'));
}

public function update(Request $request, $id)
{
    // Validasi input
    $request->validate([
        'nama' => 'required|string|max:255',
        'keterangan' => 'required|string|max:255',
    ]);

    // Cari data barang berdasarkan ID, kalau tidak ketemu error 404
    $barang = Barang::findOrFail($id);

    // Update data barang dengan input dari form
    $barang->update([
        'nama' => $request->nama,
        'keterangan' => $request->keterangan,
    ]);

    // Redirect ke halaman index dengan pesan sukses
    return redirect()->route('barang.index')->with('success', 'Barang berhasil diupdate.');
}

}