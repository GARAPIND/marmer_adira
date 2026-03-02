<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class PenggunaController extends Controller
{
    public function index()
    {
        $user = User::whereIn('role', ['pengrajin', 'pembeli'])->get();
        return view('admin.data-pengguna', compact('user'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name'     => 'required|string|max:255',
            'email'    => 'required|email|unique:users,email',
            'no_telp'  => 'required|string|max:20',
            'role'     => 'required|in:pembeli,pengrajin',
            'password' => 'required|min:8',
        ]);

        User::create([
            'name'     => $request->name,
            'email'    => $request->email,
            'no_telp'  => $request->no_telp,
            'role'     => $request->role,
            'password' => Hash::make($request->password),
        ]);

        return redirect()
            ->route('admin.pengguna.index')
            ->with('success', 'Data pengguna berhasil ditambahkan.');
    }

    public function update(Request $request, $id)
    {
        $user = User::findOrFail($id);

        $request->validate([
            'name'    => 'required|string|max:255',
            'email'   => 'required|email|unique:users,email,' . $id,
            'no_telp' => 'required|string|max:20',
            'role'    => 'required|in:pembeli,pengrajin',
            'password' => 'nullable|min:8',
        ]);

        $data = [
            'name'    => $request->name,
            'email'   => $request->email,
            'no_telp' => $request->no_telp,
            'role'    => $request->role,
        ];

        if ($request->filled('password')) {
            $data['password'] = Hash::make($request->password);
        }

        $user->update($data);

        return redirect()
            ->route('admin.pengguna.index')
            ->with('success', 'Data pengguna berhasil diperbarui.');
    }

    public function destroy($id)
    {
        $user = User::findOrFail($id);
        $user->delete();

        return redirect()
            ->route('admin.pengguna.index')
            ->with('success', 'Data pengguna berhasil dihapus.');
    }
}
