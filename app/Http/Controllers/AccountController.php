<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class AccountController extends Controller
{
    public function index()
    {
        $users = User::all();
        return view('akun.index', compact('users'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name'     => 'required|string|max:100|unique:users,name',
            'email'    => 'required|email|unique:users,email',
            'role'     => 'required|in:admin,superAdmin',
            'password' => 'required|string|min:6|confirmed',
        ]);

        User::create([
            'name'     => $request->name,
            'email'    => $request->email,
            'role'     => $request->role,
            'password' => Hash::make($request->password),
            'avatar'   => "https://ui-avatars.com/api/?name={$request->name}&background=3B82F6&color=fff",
        ]);

        return back()->with('success', 'Akun berhasil ditambahkan!');
    }

    public function update(Request $request, User $user)
    {
        $request->validate([
            'name'  => "required|string|max:100|unique:users,name,{$user->id}",
            'email' => "required|email|unique:users,email,{$user->id}",
            'role'  => 'required|in:admin,superAdmin',
        ]);

        $data = ['name' => $request->name, 'email' => $request->email, 'role' => $request->role];
        if ($request->filled('password')) {
            $request->validate(['password' => 'min:6|confirmed']);
            $data['password'] = Hash::make($request->password);
        }

        $user->update($data);
        return back()->with('success', 'Akun berhasil diperbarui!');
    }

    public function destroy(User $user)
    {
        if ($user->role === 'superAdmin') {
            return back()->with('error', 'Tidak bisa menghapus akun Super Admin!');
        }
        $user->delete();
        return back()->with('success', 'Akun berhasil dihapus!');
    }
}
