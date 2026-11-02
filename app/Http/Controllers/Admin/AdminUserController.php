<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;
use App\Models\User;
use App\Models\AuditLog;

class AdminUserController extends Controller
{
    public function index()
    {
        $users = User::orderBy('name')->paginate(15);
        return view('admin.users.index', compact('users'));
    }

    public function create()
    {
        return view('admin.users.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:150',
            'email' => 'required|email|max:150|unique:users,email',
            'role' => 'required|in:super_admin,ketua_divisi,staff,mahasiswa',
            'phone' => 'nullable|string|max:25',
            'password' => ['required', Password::min(8)],
            'is_active' => 'nullable|boolean',
        ]);

        $user = User::create([
            'name' => strip_tags($validated['name']),
            'email' => strtolower(trim($validated['email'])),
            'role' => $validated['role'],
            'phone' => $validated['phone'] ? strip_tags($validated['phone']) : null,
            'password' => Hash::make($validated['password']),
            'is_active' => $request->boolean('is_active'),
        ]);

        AuditLog::log('superadmin_create_user', "Menambahkan pengurus baru: {$user->name} ({$user->role})", 'users', $user->id);

        return redirect()->route('admin.users.index')->with('success', "Pengguna {$user->name} berhasil ditambahkan.");
    }

    public function update(Request $request, int $id)
    {
        $user = User::findOrFail($id);

        $validated = $request->validate([
            'name' => 'required|string|max:150',
            'email' => 'required|email|max:150|unique:users,email,' . $user->id,
            'role' => 'required|in:super_admin,ketua_divisi,staff,mahasiswa',
            'phone' => 'nullable|string|max:25',
            'password' => ['nullable', Password::min(8)],
            'is_active' => 'nullable|boolean',
        ]);

        if ($user->id === auth()->id()) {
            if ($validated['role'] !== $user->role) {
                return back()->with('error', 'Anda tidak dapat mengubah role akun Anda sendiri untuk mencegah lockout sistem.');
            }
            if (!$request->boolean('is_active')) {
                return back()->with('error', 'Anda tidak dapat menonaktifkan akun Anda sendiri.');
            }
        }

        $data = [
            'name' => strip_tags($validated['name']),
            'email' => strtolower(trim($validated['email'])),
            'role' => $validated['role'],
            'phone' => $validated['phone'] ? strip_tags($validated['phone']) : null,
            'is_active' => $request->boolean('is_active'),
        ];

        if (!empty($validated['password'])) {
            $data['password'] = Hash::make($validated['password']);
        }

        $user->update($data);

        AuditLog::log('superadmin_update_user', "Memperbarui akun {$user->name} ({$user->role})", 'users', $user->id);

        return back()->with('success', "Data pengguna berhasil diperbarui.");
    }

    public function toggleActive(int $id)
    {
        $user = User::findOrFail($id);
        if ($user->id === auth()->id()) {
            return back()->with('error', 'Anda tidak dapat menonaktifkan akun Anda sendiri.');
        }

        $user->is_active = !$user->is_active;
        $user->save();

        AuditLog::log('superadmin_toggle_user', "Status akun {$user->name} diubah menjadi: " . ($user->is_active ? 'Aktif' : 'Non-Aktif'), 'users', $user->id);

        return back()->with('success', "Status akun {$user->name} berhasil diubah.");
    }
}
