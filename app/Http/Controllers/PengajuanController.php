<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class PengajuanController extends Controller
{
    public function index()
    {
        return view('pengajuan.index');
    }

    public function create(Request $request)
    {
        $assetId = $request->query('asset_id');
        return view('pengajuan.create', compact('assetId'));
    }


}
