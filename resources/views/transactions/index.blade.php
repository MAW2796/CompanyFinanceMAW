@extends('layouts.app')

@section('page-title', 'Daftar Transaksi')
@section('page-subtitle', 'Semua aktivitas keuangan tercatat di sini')

@section('content')

{{-- Toolbar --}}
<div class="bg-white rounded-2xl shadow-sm border border-slate-100 p-4 mb-4">
    <form method="GET" class="flex flex-wrap gap-2 items-end">
        @if($branches)
        <div>
            <label class="block text-xs font-medium text-slate-500 mb-1.5">Cabang</label>
            <select name="branch_id" class="field-input" style="width:auto">
                <option value="">Semua Cabang</option>
                @foreach($branches as $b)
                    <option value="{{ $b->id }}" {{ request('branch_id') == $b->id ? 'selected' : '' }}>{{ $b->name }}</option>
                @endforeach
            </select>
        </div>
        @endif
        <div>
            <label class="block text-xs font-medium text-slate-500 mb-1.5">Tipe</label>
            <select name="type" class="field-input" style="width:auto">
                <option value="">Semua Tipe</option>
                <option value="pemasukan" {{ request('type') == 'pemasukan' ? 'selected' : '' }}>Pemasukan</option>
                <option value="pengeluaran" {{ request('type') == 'pengeluaran' ? 'selected' : '' }}>Pengeluaran</option>
            </select>
        </div>
        <div>
            <label class="block text-xs font-medium text-slate-500 mb-1.5">Dari</label>
            <input type="date" name="start_date" value="{{ request('start_date') }}" class="field-input" style="width:auto">
        </div>
        <div>
            <label class="block text-xs font-medium text-slate-500 mb-1.5">Sampai</label>
            <input type="date" name="end_date" value="{{ request('end_date') }}" class="field-input" style="width:auto">
        </div>
        <button class="btn-primary flex items-center gap-2">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
            </svg>
            Filter
        </button>
        @if(request()->hasAny(['branch_id','type','start_date','end_date']))
        <a href="{{ route('transactions.index') }}" class="text-sm text-slate-500 hover:text-slate-700 self-end pb-0.5">Reset</a>
        @endif
        <div class="ml-auto">
            <a href="{{ route('transactions.create') }}" class="btn-primary flex items-center gap-2">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4"/>
                </svg>
                Input Transaksi
            </a>
        </div>
    </form>
</div>

{{-- Summary bar --}}
@php
$totalMasuk = $transactions->where('type','pemasukan')->sum('amount');
$totalKeluar = $transactions->where('type','pengeluaran')->sum('amount');
@endphp
<div class="grid grid-cols-3 gap-3 mb-4">
    <div class="bg-green-50 border border-green-100 rounded-xl p-3 text-center">
        <p class="text-xs text-green-600 font-medium">Total Pemasukan</p>
        <p class="font-bold text-green-700 text-sm sm:text-base">Rp {{ number_format($totalMasuk, 0, ',', '.') }}</p>
    </div>
    <div class="bg-red-50 border border-red-100 rounded-xl p-3 text-center">
        <p class="text-xs text-red-600 font-medium">Total Pengeluaran</p>
        <p class="font-bold text-red-700 text-sm sm:text-base">Rp {{ number_format($totalKeluar, 0, ',', '.') }}</p>
    </div>
    <div class="bg-blue-50 border border-blue-100 rounded-xl p-3 text-center">
        <p class="text-xs text-blue-600 font-medium">Net</p>
        <p class="font-bold text-sm sm:text-base {{ ($totalMasuk-$totalKeluar) >= 0 ? 'text-blue-700' : 'text-red-700' }}">Rp {{ number_format($totalMasuk-$totalKeluar, 0, ',', '.') }}</p>
    </div>
</div>

{{-- Desktop table --}}
<div class="hidden sm:block bg-white rounded-2xl shadow-sm border border-slate-100 overflow-hidden mb-4">
    <table class="w-full data-table">
        <thead>
            <tr>
                <th>Tanggal</th>
                @if($branches)<th>Cabang</th>@endif
                <th>Kategori</th>
                <th>Keterangan</th>
                <th>Tipe</th>
                <th class="text-right">Jumlah</th>
                <th>Oleh</th>
                <th></th>
            </tr>
        </thead>
        <tbody>
            @forelse($transactions as $t)
            <tr>
                <td class="text-slate-500 text-xs whitespace-nowrap">{{ $t->transaction_date->format('d M Y') }}</td>
                @if($branches)
                <td>
                    <span class="bg-slate-100 text-slate-600 text-xs font-medium px-2 py-0.5 rounded-md">{{ $t->branch->name }}</span>
                </td>
                @endif
                <td class="font-medium text-slate-700 text-sm">{{ $t->category->name }}</td>
                <td class="text-slate-400 text-xs max-w-[160px] truncate">{{ $t->description ?? '—' }}</td>
                <td>
                    <span class="px-2.5 py-1 rounded-full text-xs font-semibold {{ $t->type === 'pemasukan' ? 'badge-masuk' : 'badge-keluar' }}">
                        {{ ucfirst($t->type) }}
                    </span>
                </td>
                <td class="text-right font-bold {{ $t->type === 'pemasukan' ? 'text-green-600' : 'text-red-500' }}">
                    {{ $t->type === 'pemasukan' ? '+' : '-' }}Rp {{ number_format($t->amount, 0, ',', '.') }}
                </td>
                <td class="text-slate-400 text-xs">{{ $t->user->name }}</td>
                <td>
                    <form method="POST" action="{{ route('transactions.destroy', $t) }}"
                          onsubmit="return confirm('Hapus transaksi ini?')">
                        @csrf @method('DELETE')
                        <button class="text-xs text-red-400 hover:text-red-600 hover:bg-red-50 px-2 py-1 rounded transition-colors">Hapus</button>
                    </form>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="8" class="text-center py-12 text-slate-400">
                    <svg class="w-10 h-10 mx-auto mb-2 text-slate-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2"/>
                    </svg>
                    Tidak ada transaksi ditemukan
                </td>
            </tr>
            @endforelse
        </tbody>
    </table>
</div>

{{-- Mobile cards --}}
<div class="sm:hidden space-y-2 mb-4">
    @forelse($transactions as $t)
    <div class="bg-white rounded-xl border border-slate-100 shadow-sm p-4">
        <div class="flex justify-between items-start mb-1.5">
            <div>
                <p class="font-semibold text-slate-800 text-sm">{{ $t->category->name }}</p>
                @if($t->description)
                <p class="text-xs text-slate-400">{{ $t->description }}</p>
                @endif
                @if($branches)
                <p class="text-xs text-slate-400">{{ $t->branch->name }}</p>
                @endif
            </div>
            <span class="px-2 py-0.5 rounded-full text-xs font-semibold {{ $t->type === 'pemasukan' ? 'badge-masuk' : 'badge-keluar' }}">
                {{ ucfirst($t->type) }}
            </span>
        </div>
        <p class="text-lg font-bold {{ $t->type === 'pemasukan' ? 'text-green-600' : 'text-red-500' }}">
            {{ $t->type === 'pemasukan' ? '+' : '-' }}Rp {{ number_format($t->amount, 0, ',', '.') }}
        </p>
        <div class="flex justify-between items-center mt-2">
            <p class="text-xs text-slate-400">{{ $t->transaction_date->format('d M Y') }} · {{ $t->user->name }}</p>
            <form method="POST" action="{{ route('transactions.destroy', $t) }}" onsubmit="return confirm('Hapus?')">
                @csrf @method('DELETE')
                <button class="text-xs text-red-400 hover:text-red-600">Hapus</button>
            </form>
        </div>
    </div>
    @empty
    <div class="text-center py-12 text-slate-400">Tidak ada transaksi</div>
    @endforelse
</div>

<div class="mt-2">{{ $transactions->links() }}</div>
@endsection
