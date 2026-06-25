<?php

namespace App\Http\Controllers;

use App\Models\Branch;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class UserController extends Controller
{
    // Hanya admin (sudah dibatasi di route via middleware role:admin)

    public function index()
    {
        $users = User::with('branch')->orderBy('name')->get();
        $branches = Branch::all();

        return view('users.index', compact('users', 'branches'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:users,email',
            'password' => 'required|string|min:6',
            'role' => 'required|in:admin,karyawan',
            'branch_id' => 'nullable|exists:branches,id|required_if:role,karyawan',
        ], [
            'branch_id.required_if' => 'Karyawan wajib dipilih cabangnya.',
        ]);

        $validated['password'] = Hash::make($validated['password']);
        if ($validated['role'] === 'admin') {
            $validated['branch_id'] = null;
        }

        User::create($validated);

        return redirect()->route('users.index')->with('success', 'Pengguna berhasil ditambahkan.');
    }

    public function update(Request $request, User $user)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => ['required', 'email', 'max:255', Rule::unique('users', 'email')->ignore($user->id)],
            'password' => 'nullable|string|min:6',
            'role' => 'required|in:admin,karyawan',
            'branch_id' => 'nullable|exists:branches,id|required_if:role,karyawan',
        ], [
            'branch_id.required_if' => 'Karyawan wajib dipilih cabangnya.',
        ]);

        if (! empty($validated['password'])) {
            $validated['password'] = Hash::make($validated['password']);
        } else {
            unset($validated['password']);
        }

        if ($validated['role'] === 'admin') {
            $validated['branch_id'] = null;
        }

        $user->update($validated);

        return redirect()->route('users.index')->with('success', 'Data pengguna berhasil diperbarui.');
    }

    public function destroy(User $user)
    {
        if ($user->id === Auth::id()) {
            return back()->with('error', 'Tidak bisa menghapus akun yang sedang digunakan.');
        }

        $user->delete();

        return back()->with('success', 'Pengguna berhasil dihapus.');
    }
}
