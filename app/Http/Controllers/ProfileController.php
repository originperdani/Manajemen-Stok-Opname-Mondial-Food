<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ProfileController extends Controller
{
    public function edit()
    {
        $user = auth()->user();
        return view('profile.simple-edit', compact('user'));
    }

    public function update(Request $request)
    {
        $user = auth()->user();
        
        $request->validate([
            'name' => 'required|string|max:255',
            'foto' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $data = $request->only('name');
        
        if ($request->hasFile('foto')) {
            if ($user->foto && Storage::exists('public/' . $user->foto)) {
                Storage::delete('public/' . $user->foto);
            }
            $path = $request->file('foto')->store('profile', 'public');
            $data['foto'] = $path;
        }

        $user->update($data);

        // Redirect ke dashboard sesuai role
        $redirectUrl = match($user->role) {
            'owner' => route('owner.dashboard'),
            'admin_gudang' => route('gudang.dashboard'),
            'admin_penjualan' => route('penjualan.dashboard'),
            'admin_produksi' => route('produksi.dashboard'),
            'customer' => route('home'),
            default => route('home'),
        };

        return redirect($redirectUrl)->with('success', 'Profil berhasil diperbarui!');
    }
}