@extends('layouts.app')

@section('page-title', 'Kelola Pengguna')
@section('page-subtitle', 'Tambah, ubah, atau hapus akun admin dan karyawan')

@section('content')
<div class="grid grid-cols-1 lg:grid-cols-3 gap-5">

    {{-- Add User Form --}}
    <div class="lg:col-span-1">
        <div class="bg-white rounded-2xl shadow-sm border border-slate-100 overflow-hidden sticky top-20">
            <div class="bg-gradient-to-r from-blue-600 to-blue-700 px-5 py-4">
                <h2 class="font-display text-white font-bold">Tambah Pengguna</h2>
                <p class="text-blue-200 text-xs mt-0.5">Buat akun admin atau karyawan baru</p>
            </div>
            <form method="POST" action="{{ route('users.store') }}" class="p-5 space-y-4">
                @csrf
                <div>
                    <label class="block text-xs font-semibold text-slate-600 mb-1.5">Nama Lengkap <span class="text-red-500">*</span></label>
                    <input type="text" name="name" class="field-input" required placeholder="contoh: Budi Santoso">
                </div>
                <div>
                    <label class="block text-xs font-semibold text-slate-600 mb-1.5">Email <span class="text-red-500">*</span></label>
                    <input type="email" name="email" class="field-input" required placeholder="budi@perusahaan.com">
                </div>
                <div>
                    <label class="block text-xs font-semibold text-slate-600 mb-1.5">Password <span class="text-red-500">*</span></label>
                    <input type="password" name="password" class="field-input" required minlength="6" placeholder="Minimal 6 karakter">
                </div>
                <div>
                    <label class="block text-xs font-semibold text-slate-600 mb-1.5">Role <span class="text-red-500">*</span></label>
                    <select name="role" id="role-select" class="field-input" required onchange="toggleBranchField(this, 'branch-field')">
                        <option value="karyawan">Karyawan</option>
                        <option value="admin">Admin</option>
                    </select>
                </div>
                <div id="branch-field">
                    <label class="block text-xs font-semibold text-slate-600 mb-1.5">Cabang <span class="text-red-500">*</span></label>
                    <select name="branch_id" class="field-input">
                        <option value="">— Pilih Cabang —</option>
                        @foreach($branches as $b)
                            <option value="{{ $b->id }}">{{ $b->name }}</option>
                        @endforeach
                    </select>
                </div>
                <button class="btn-primary w-full flex items-center justify-center gap-2">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4"/>
                    </svg>
                    Tambah Pengguna
                </button>
            </form>
        </div>
    </div>

    {{-- Users List --}}
    <div class="lg:col-span-2">
        {{-- Summary --}}
        <div class="grid grid-cols-2 gap-3 mb-4">
            <div class="bg-blue-50 border border-blue-100 rounded-xl p-4">
                <p class="text-xs text-blue-600 font-medium">Total Admin</p>
                <p class="font-display text-2xl font-bold text-blue-700">{{ $users->where('role', 'admin')->count() }}</p>
            </div>
            <div class="bg-green-50 border border-green-100 rounded-xl p-4">
                <p class="text-xs text-green-600 font-medium">Total Karyawan</p>
                <p class="font-display text-2xl font-bold text-green-700">{{ $users->where('role', 'karyawan')->count() }}</p>
            </div>
        </div>

        {{-- Desktop --}}
        <div class="hidden sm:block bg-white rounded-2xl shadow-sm border border-slate-100 overflow-hidden">
            <div class="px-5 py-4 border-b border-slate-100">
                <h2 class="font-semibold text-slate-800">Daftar Pengguna</h2>
            </div>
            <table class="w-full data-table">
                <thead>
                    <tr>
                        <th>Nama</th>
                        <th>Email</th>
                        <th>Role</th>
                        <th>Cabang</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($users as $u)
                    <tr>
                        <td>
                            <div class="flex items-center gap-2.5">
                                <div class="w-8 h-8 rounded-full bg-blue-600 flex items-center justify-center text-xs font-bold text-white flex-shrink-0">
                                    {{ strtoupper(substr($u->name, 0, 2)) }}
                                </div>
                                <p class="font-medium text-slate-800 text-sm">{{ $u->name }}</p>
                            </div>
                        </td>
                        <td class="text-slate-500 text-xs">{{ $u->email }}</td>
                        <td>
                            <span class="px-2.5 py-1 rounded-full text-xs font-semibold {{ $u->role === 'admin' ? 'bg-purple-50 text-purple-600' : 'badge-masuk' }}">
                                {{ ucfirst($u->role) }}
                            </span>
                        </td>
                        <td class="text-slate-500 text-xs">{{ $u->branch->name ?? '—' }}</td>
                        <td>
                            <div class="flex items-center justify-end gap-1">
                                <button onclick="openEditModal({{ $u->id }}, '{{ $u->name }}', '{{ $u->email }}', '{{ $u->role }}', '{{ $u->branch_id }}')"
                                        class="text-xs text-blue-500 hover:text-blue-700 hover:bg-blue-50 px-2 py-1 rounded transition-colors">Edit</button>
                                <form method="POST" action="{{ route('users.destroy', $u) }}"
                                      onsubmit="return confirm('Hapus pengguna {{ $u->name }}?')">
                                    @csrf @method('DELETE')
                                    <button class="text-xs text-red-400 hover:text-red-600 hover:bg-red-50 px-2 py-1 rounded transition-colors">Hapus</button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr><td colspan="5" class="text-center py-10 text-slate-400">Belum ada pengguna</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- Mobile --}}
        <div class="sm:hidden space-y-2">
            @forelse($users as $u)
            <div class="bg-white rounded-xl border border-slate-100 shadow-sm p-4">
                <div class="flex items-center gap-3 mb-2">
                    <div class="w-9 h-9 rounded-full bg-blue-600 flex items-center justify-center text-xs font-bold text-white flex-shrink-0">
                        {{ strtoupper(substr($u->name, 0, 2)) }}
                    </div>
                    <div class="flex-1 min-w-0">
                        <p class="font-semibold text-slate-800 truncate">{{ $u->name }}</p>
                        <p class="text-xs text-slate-400 truncate">{{ $u->email }}</p>
                    </div>
                    <span class="px-2 py-0.5 rounded-full text-xs font-semibold {{ $u->role === 'admin' ? 'bg-purple-50 text-purple-600' : 'badge-masuk' }}">
                        {{ ucfirst($u->role) }}
                    </span>
                </div>
                <div class="flex justify-between items-center">
                    <p class="text-xs text-slate-400">{{ $u->branch->name ?? '—' }}</p>
                    <div class="flex items-center gap-3">
                        <button onclick="openEditModal({{ $u->id }}, '{{ $u->name }}', '{{ $u->email }}', '{{ $u->role }}', '{{ $u->branch_id }}')"
                                class="text-xs text-blue-500">Edit</button>
                        <form method="POST" action="{{ route('users.destroy', $u) }}" onsubmit="return confirm('Hapus pengguna ini?')">
                            @csrf @method('DELETE')
                            <button class="text-xs text-red-400">Hapus</button>
                        </form>
                    </div>
                </div>
            </div>
            @empty
            <p class="text-center text-slate-400 text-sm py-8">Belum ada pengguna</p>
            @endforelse
        </div>
    </div>
</div>

{{-- Edit Modal --}}
<div id="edit-user-modal" class="fixed inset-0 z-50 hidden items-center justify-center" style="background:rgba(15,23,42,0.45)">
    <div class="bg-white rounded-2xl shadow-xl w-full max-w-md mx-4">
        <div class="flex items-center justify-between px-5 py-4 border-b border-slate-100">
            <h3 class="font-display font-bold text-slate-800">Edit Pengguna</h3>
            <button onclick="closeEditModal()" class="text-slate-400 hover:text-slate-600">✕</button>
        </div>
        <form id="edit-user-form" method="POST" class="p-5 space-y-4">
            @csrf
            @method('PUT')
            <div>
                <label class="block text-xs font-semibold text-slate-600 mb-1.5">Nama Lengkap</label>
                <input type="text" name="name" id="edit-name" class="field-input" required>
            </div>
            <div>
                <label class="block text-xs font-semibold text-slate-600 mb-1.5">Email</label>
                <input type="email" name="email" id="edit-email" class="field-input" required>
            </div>
            <div>
                <label class="block text-xs font-semibold text-slate-600 mb-1.5">Password Baru (opsional)</label>
                <input type="password" name="password" id="edit-password" class="field-input" minlength="6" placeholder="Biarkan kosong jika tidak diubah">
            </div>
            <div>
                <label class="block text-xs font-semibold text-slate-600 mb-1.5">Role</label>
                <select name="role" id="edit-role" class="field-input" onchange="toggleBranchField(this, 'edit-branch-field')">
                    <option value="karyawan">Karyawan</option>
                    <option value="admin">Admin</option>
                </select>
            </div>
            <div id="edit-branch-field">
                <label class="block text-xs font-semibold text-slate-600 mb-1.5">Cabang</label>
                <select name="branch_id" id="edit-branch" class="field-input">
                    <option value="">— Pilih Cabang —</option>
                    @foreach($branches as $b)
                        <option value="{{ $b->id }}">{{ $b->name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="flex gap-2 pt-2">
                <button type="button" onclick="closeEditModal()" class="flex-1 py-2.5 rounded-lg text-sm font-semibold text-slate-600 bg-slate-100 hover:bg-slate-200">Batal</button>
                <button type="submit" class="flex-1 btn-primary">Simpan</button>
            </div>
        </form>
    </div>
</div>

@push('scripts')
<script>
function toggleBranchField(select, fieldId) {
    document.getElementById(fieldId).style.display = select.value === 'admin' ? 'none' : 'block';
}
// Set tampilan awal field cabang sesuai role default
toggleBranchField(document.getElementById('role-select'), 'branch-field');

function openEditModal(id, name, email, role, branchId) {
    document.getElementById('edit-user-form').action = '/users/' + id;
    document.getElementById('edit-name').value = name;
    document.getElementById('edit-email').value = email;
    document.getElementById('edit-password').value = '';
    document.getElementById('edit-role').value = role;
    document.getElementById('edit-branch').value = branchId || '';
    toggleBranchField(document.getElementById('edit-role'), 'edit-branch-field');
    const modal = document.getElementById('edit-user-modal');
    modal.classList.remove('hidden');
    modal.classList.add('flex');
}
function closeEditModal() {
    const modal = document.getElementById('edit-user-modal');
    modal.classList.add('hidden');
    modal.classList.remove('flex');
}
</script>
@endpush
@endsection
