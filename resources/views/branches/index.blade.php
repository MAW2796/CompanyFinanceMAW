@extends('layouts.app')

@section('page-title', 'Kelola Cabang')
@section('page-subtitle', 'Manajemen cabang dan saldo awal')

@section('content')
<div class="grid grid-cols-1 lg:grid-cols-3 gap-5">

    {{-- Add Branch Form --}}
    <div class="lg:col-span-1">
        <div class="bg-white rounded-2xl shadow-sm border border-slate-100 overflow-hidden sticky top-20">
            <div class="bg-gradient-to-r from-slate-700 to-slate-800 px-5 py-4">
                <h2 class="font-display text-white font-bold">Tambah Cabang Baru</h2>
                <p class="text-slate-400 text-xs mt-0.5">Isi data cabang dengan lengkap</p>
            </div>
            <form method="POST" action="{{ route('branches.store') }}" class="p-5 space-y-4">
                @csrf
                <div>
                    <label class="block text-xs font-semibold text-slate-600 mb-1.5">Nama Cabang <span class="text-red-500">*</span></label>
                    <input type="text" name="name" class="field-input" required placeholder="contoh: Cabang Jakarta Pusat">
                </div>
                <div>
                    <label class="block text-xs font-semibold text-slate-600 mb-1.5">Kode Cabang <span class="text-red-500">*</span></label>
                    <input type="text" name="code" class="field-input" required placeholder="contoh: JKT-01" style="text-transform:uppercase">
                </div>
                <div>
                    <label class="block text-xs font-semibold text-slate-600 mb-1.5">Alamat</label>
                    <input type="text" name="address" class="field-input" placeholder="Jl. contoh No. 1, Jakarta">
                </div>
                <div>
                    <label class="block text-xs font-semibold text-slate-600 mb-1.5">Saldo Awal (Rp)</label>
                    <div class="relative">
                        <span class="absolute left-3 top-1/2 -translate-y-1/2 text-slate-400 text-sm">Rp</span>
                        <input type="number" step="1" name="initial_balance" class="field-input pl-9" value="0" min="0">
                    </div>
                </div>
                <button class="btn-primary w-full flex items-center justify-center gap-2">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4"/>
                    </svg>
                    Tambah Cabang
                </button>
            </form>
        </div>
    </div>

    {{-- Branches List --}}
    <div class="lg:col-span-2">
        {{-- Summary --}}
        <div class="grid grid-cols-2 gap-3 mb-4">
            <div class="bg-blue-50 border border-blue-100 rounded-xl p-4">
                <p class="text-xs text-blue-600 font-medium">Total Cabang</p>
                <p class="font-display text-2xl font-bold text-blue-700">{{ $branches->count() }}</p>
            </div>
            <div class="bg-green-50 border border-green-100 rounded-xl p-4">
                <p class="text-xs text-green-600 font-medium">Total Saldo Gabungan</p>
                <p class="font-display text-xl font-bold text-green-700">Rp {{ number_format($branches->sum('balance'), 0, ',', '.') }}</p>
            </div>
        </div>

        {{-- Desktop --}}
        <div class="hidden sm:block bg-white rounded-2xl shadow-sm border border-slate-100 overflow-hidden">
            <div class="px-5 py-4 border-b border-slate-100">
                <h2 class="font-semibold text-slate-800">Daftar Cabang Aktif</h2>
            </div>
            <table class="w-full data-table">
                <thead>
                    <tr>
                        <th>Cabang</th>
                        <th>Kode</th>
                        <th>Alamat</th>
                        <th class="text-right">Saldo Saat Ini</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($branches as $b)
                    <tr>
                        <td>
                            <div class="flex items-center gap-2.5">
                                <div class="w-8 h-8 rounded-lg bg-blue-50 flex items-center justify-center text-xs font-bold text-blue-600">
                                    {{ strtoupper(substr($b->code, 0, 2)) }}
                                </div>
                                <p class="font-medium text-slate-800 text-sm">{{ $b->name }}</p>
                            </div>
                        </td>
                        <td><span class="font-mono text-xs bg-slate-100 px-2 py-0.5 rounded">{{ $b->code }}</span></td>
                        <td class="text-slate-400 text-xs">{{ $b->address ?? '—' }}</td>
                        <td class="text-right font-bold text-slate-800">Rp {{ number_format($b->balance, 0, ',', '.') }}</td>
                        <td>
                            <form method="POST" action="{{ route('branches.destroy', $b) }}"
                                  onsubmit="return confirm('Hapus cabang {{ $b->name }}? Seluruh data terkait akan ikut terhapus.')">
                                @csrf @method('DELETE')
                                <button class="text-xs text-red-400 hover:text-red-600 hover:bg-red-50 px-2 py-1 rounded transition-colors">Hapus</button>
                            </form>
                        </td>
                    </tr>
                    @empty
                    <tr><td colspan="5" class="text-center py-10 text-slate-400">Belum ada cabang</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- Mobile --}}
        <div class="sm:hidden space-y-2">
            @forelse($branches as $b)
            <div class="bg-white rounded-xl border border-slate-100 shadow-sm p-4">
                <div class="flex items-center gap-3 mb-2">
                    <div class="w-9 h-9 rounded-xl bg-blue-50 flex items-center justify-center text-sm font-bold text-blue-600">
                        {{ strtoupper(substr($b->code, 0, 2)) }}
                    </div>
                    <div class="flex-1">
                        <p class="font-semibold text-slate-800">{{ $b->name }}</p>
                        <p class="text-xs text-slate-400">{{ $b->code }}{{ $b->address ? ' · ' . $b->address : '' }}</p>
                    </div>
                    <form method="POST" action="{{ route('branches.destroy', $b) }}"
                          onsubmit="return confirm('Hapus cabang ini?')">
                        @csrf @method('DELETE')
                        <button class="text-xs text-red-400">Hapus</button>
                    </form>
                </div>
                <div class="bg-slate-50 rounded-lg p-2.5">
                    <p class="text-xs text-slate-500">Saldo Saat Ini</p>
                    <p class="font-bold text-slate-800">Rp {{ number_format($b->balance, 0, ',', '.') }}</p>
                </div>
            </div>
            @empty
            <p class="text-center text-slate-400 text-sm py-8">Belum ada cabang</p>
            @endforelse
        </div>
    </div>
</div>
@endsection
