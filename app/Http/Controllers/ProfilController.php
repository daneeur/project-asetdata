<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ProfilController extends Controller
{
    public function update(Request $request)
    {
        $user = Auth::user();
        if ($request->hasFile('foto')) {
            $path = $request->file('foto')->store('foto-profil', 'public');
            $user->foto = $path;
            $user->save();
        }
        return back()->with('success', 'Foto profil berhasil diupdate!');
    }

    public function show()
    {
        $user = auth()->user();
        return view('profil', compact('user'));
    }
}
