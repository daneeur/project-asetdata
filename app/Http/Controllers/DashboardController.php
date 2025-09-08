<?php

namespace App\Http\Controllers;

use App\Models\Monitoring;
use App\Models\Pengajuan;
use App\Models\Asset;
use App\Models\User;

class DashboardController extends Controller
{
    public function index()
    {
        return view('dashboard', [
            'monitoringCount' => Monitoring::count(),
            'pengajuanCount'  => Pengajuan::where('status', 'diterima')->count(),
            'assetCount'      => Asset::count(),
            'userCount'       => User::count(),
        ]);
    }
}
