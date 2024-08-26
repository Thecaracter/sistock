<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // Mengambil semua data pengguna
        $users = User::where('role', 'user')->get();

        // Mengirim data pengguna ke view
        return view('pages.user', compact('users'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        try {
            // Membuat pengguna baru
            User::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => Hash::make($request->password),
                'role' => 'user',
            ]);

            return redirect()->route('user.index')->with('notification', [
                'title' => 'Berhasil!',
                'text' => 'Pengguna berhasil ditambahkan.',
                'type' => 'success'
            ]);
        } catch (\Exception $e) {
            return redirect()->route('user.index')->with('notification', [
                'title' => 'Error!',
                'text' => 'Terjadi kesalahan saat menyimpan data pengguna.',
                'type' => 'error'
            ]);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,' . $id,
            'password' => 'nullable|string|min:8',

        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        try {
            // Mengambil data pengguna berdasarkan ID
            $user = User::findOrFail($id);

            // Update data pengguna
            $user->name = $request->name;
            $user->email = $request->email;
            if ($request->filled('password')) {
                $user->password = Hash::make($request->password);
            }
            $user->role = 'user';
            $user->save();

            return redirect()->route('user.index')->with('notification', [
                'title' => 'Berhasil!',
                'text' => 'Pengguna berhasil diperbarui.',
                'type' => 'success'
            ]);
        } catch (\Exception $e) {
            dd($e);
            return redirect()->route('user.index')->with('notification', [
                'title' => 'Error!',
                'text' => 'Terjadi kesalahan saat memperbarui data pengguna.',
                'type' => 'error'
            ]);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        try {
            // Menghapus pengguna berdasarkan ID
            $user = User::findOrFail($id);
            $user->delete();

            return redirect()->route('user.index')->with('notification', [
                'title' => 'Berhasil!',
                'text' => 'Pengguna berhasil dihapus.',
                'type' => 'success'
            ]);
        } catch (\Exception $e) {
            return redirect()->route('user.index')->with('notification', [
                'title' => 'Error!',
                'text' => 'Terjadi kesalahan saat menghapus data pengguna.',
                'type' => 'error'
            ]);
        }
    }
}
