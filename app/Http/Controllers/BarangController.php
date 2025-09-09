<?php

namespace App\Http\Controllers;

use App\Models\Barang;
use App\Models\Kategori;
use Illuminate\Http\Request;

class BarangController extends Controller
{
    public function index()
    {
        $barang = Barang::with('kategori')->get();
        $kategori = Kategori::all();
        return view('barang.index', compact('barang', 'kategori'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama_barang' => 'required|string|max:255',
            'kategori_id' => 'required|exists:kategori,id',
            'spesifikasi' => 'required|string',
            'kondisi' => 'required|string',
        ]);

        Barang::create($request->all());

        return redirect()->route('barang.index')->with('success', 'Barang ' . $request->nama_barang . ' berhasil ditambahkan');
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'nama_barang' => 'required|string|max:255',
            'kategori_id' => 'required|exists:kategori,id',
            'spesifikasi' => 'required|string',
            'kondisi' => 'required|string',
        ]);

        $barang = Barang::findOrFail($id);
        $barang->update($request->all());

        return redirect()->route('barang.index')->with('success', 'Barang ' . $request->nama_barang . ' berhasil diperbarui');
    }

    public function destroy($id)
    {
        $barang = Barang::findOrFail($id);
        $barang->delete();

        $namaBarang = $barang->nama_barang;
        $barang->delete();
        return redirect()->route('barang.index')->with('success', 'Barang ' . $namaBarang . ' berhasil dihapus');
    }
}