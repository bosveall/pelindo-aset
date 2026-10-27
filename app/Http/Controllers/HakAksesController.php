<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class HakAksesController extends Controller
{
    public function index()
    {
        $admins = User::where('role', 'admin')->orderBy('name')->get();
        $petugas = User::where('role', 'petugas')->orderBy('name')->get();

        return view('hakakses.index', compact('admins', 'petugas'));
    }

    public function update(Request $request, User $user)
    {
        $request->validate([
            'role' => ['required', 'in:admin,petugas'],
        ]);

        if ($user->id === auth()->id() && $request->role !== 'admin') {
            return back()->with('error', 'Tidak dapat mengubah role akun sendiri dari admin.');
        }

        $user->update([
            'role' => $request->role,
        ]);

        return redirect()->route('hakakses.index')->with('success', 'Hak akses berhasil diperbarui.');
    }
}
