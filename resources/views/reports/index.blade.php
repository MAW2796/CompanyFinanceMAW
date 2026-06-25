@extends('layouts.app')

@section('page-title', 'Laporan & Analitik Keuangan')
@section('page-subtitle', 'Ringkasan lengkap kondisi keuangan perusahaan')

@section('content')

{{-- KPI Summary --}}
<div class="grid grid-cols-1 sm:grid-cols-3 gap-3 sm:gap-4 mb-5">
    <div class="stat-card">
        <div class="w-9 h-9 rounded-xl bg-green-50 flex items-center justify-center mb-3">
            <svg class="w-5 h-5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 11l5-5m0 0l5 5m-5-5v12"/>
            </svg>
        </div>
        <p class="text-xs text-slate-500 mb-1">Total Pemasukan</p>
        <p class="font-display text-2xl font-bold text-green-600">Rp {{ number_format($summary['pemasukan'], 0, ',', '.') }}</p>
    </div>
    <div class="stat-card">
        <div class="w-9 h-9 rounded-xl bg-red-50 flex items-center justify-center mb-3">
            <svg class="w-5 h-5 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 13l-5 5m0 0l-5-5m5 5V6"/>
            </svg>
        </div>
        <p class="text-xs text-slate-500 mb-1">Total Pengeluaran</p>
        <p class="font-display text-2xl font-bold text-red-500">Rp {{ number_format($summary['pengeluaran'], 0, ',', '.') }}</p>
    </div>
    <div class="stat-card text-white" style="background:linear-gradient(135deg, {{ $summary['selisih'] >= 0 ? '#22C55E, #16A34A' : '#EF4444, #DC2626' }})">
        <div class="w-9 h-9 rounded-xl flex items-center justify-center mb-3" style="background:rgba(255,255,255,0.2)">
            <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 7h6m0 10v-3m-3 3h.01M9 17h.01M9 11h.01M12 11h.01M15 11h.01M4 6h16a2 2 0 012 2v8a2 2 0 01-2 2H4a2 2 0 01-2-2V8a2 2 0 012-2z"/>
            </svg>
        </div>
        <p class="text-xs mb-1" style="color:rgba(255,255,255,0.85)">Laba / Rugi Bersih</p>
        <p class="font-display text-2xl font-bold text-white">{{ $summary['selisih'] >= 0 ? '+' : '' }}Rp {{ number_format($summary['selisih'], 0, ',', '.') }}</p>
        <p class="text-xs mt-1" style="color:rgba(255,255,255,0.75)">{{ $summary['selisih'] >= 0 ? '▲ Profit' : '▼ Loss' }} pada periode ini</p>
    </div>
</div>

{{-- Filter + Export --}}
<div class="bg-white rounded-2xl shadow-sm border border-slate-100 p-4 mb-5">
    <form method="GET" class="flex flex-wrap gap-3 items-end">
        <div>
            <label class="block text-xs font-medium text-slate-500 mb-1.5">Dari</label>
            <input type="date" name="start_date" value="{{ $startDate }}" class="field-input" style="width:auto">
        </div>
        <div>
            <label class="block text-xs font-medium text-slate-500 mb-1.5">Sampai</label>
            <input type="date" name="end_date" value="{{ $endDate }}" class="field-input" style="width:auto">
        </div>
        <div>
            <label class="block text-xs font-medium text-slate-500 mb-1.5">Cabang</label>
            <select name="branch_id" class="field-input" style="width:auto">
                <option value="">Semua Cabang</option>
                @foreach($branches as $b)
                    <option value="{{ $b->id }}" {{ request('branch_id') == $b->id ? 'selected' : '' }}>{{ $b->name }}</option>
                @endforeach
            </select>
        </div>
        <div>
            <label class="block text-xs font-medium text-slate-500 mb-1.5">Tipe</label>
            <select name="type" class="field-input" style="width:auto">
                <option value="">Semua Tipe</option>
                <option value="pemasukan" {{ request('type') == 'pemasukan' ? 'selected' : '' }}>Pemasukan</option>
                <option value="pengeluaran" {{ request('type') == 'pengeluaran' ? 'selected' : '' }}>Pengeluaran</option>
            </select>
        </div>
        <button class="btn-primary">Terapkan</button>
    </form>

    {{-- Export buttons --}}
    <div class="flex flex-wrap gap-2 mt-4 pt-4 border-t border-slate-100">
        <p class="text-xs font-semibold text-slate-500 self-center mr-1">Export:</p>
        <a href="{{ route('reports.export-pdf', request()->query()) }}"
           class="flex items-center gap-2 text-xs font-semibold text-red-600 bg-red-50 hover:bg-red-100 px-3 py-2 rounded-lg transition-colors">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z"/>
            </svg>
            Export PDF
        </a>
        <a href="{{ route('reports.export-excel', request()->query()) }}"
           class="flex items-center gap-2 text-xs font-semibold text-green-700 bg-green-50 hover:bg-green-100 px-3 py-2 rounded-lg transition-colors">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
            </svg>
            Export Excel
        </a>
        <span class="text-xs text-slate-400 self-center ml-2">{{ $transactions->count() }} transaksi ditemukan</span>
    </div>
</div>

{{-- Breakdown by Category --}}
@if($transactions->count() > 0)
@php
$byCategory = $transactions->groupBy('category.name')->map(fn($t) => [
    'total' => $t->sum('amount'),
    'count' => $t->count(),
    'type' => $t->first()->type,
])->sortByDesc('total');
@endphp
<div class="bg-white rounded-2xl shadow-sm border border-slate-100 overflow-hidden mb-5">
    <div class="px-5 py-4 border-b border-slate-100">
        <h2 class="font-semibold text-slate-800">Breakdown per Kategori</h2>
        <p class="text-xs text-slate-400 mt-0.5">Distribusi transaksi berdasarkan kategori</p>
    </div>
    <div class="p-4 grid grid-cols-1 sm:grid-cols-2 gap-3">
        @foreach($byCategory as $catName => $data)
        @php
        $maxTotal = $byCategory->max('total');
        $pct = $maxTotal > 0 ? round(($data['total'] / $maxTotal) * 100) : 0;
        @endphp
        <div class="flex items-center gap-3 p-3 rounded-xl bg-slate-50">
            <div class="flex-1 min-w-0">
                <div class="flex justify-between items-baseline mb-1.5">
                    <p class="text-sm font-medium text-slate-700 truncate">{{ $catName }}</p>
                    <p class="text-xs font-bold {{ $data['type'] === 'pemasukan' ? 'text-green-600' : 'text-red-500' }} ml-2 flex-shrink-0">
                        Rp {{ number_format($data['total'], 0, ',', '.') }}
                    </p>
                </div>
                <div class="w-full bg-slate-200 rounded-full h-1.5">
                    <div class="h-1.5 rounded-full {{ $data['type'] === 'pemasukan' ? 'bg-green-500' : 'bg-red-500' }}"
                         style="width:{{ $pct }}%"></div>
                </div>
                <p class="text-xs text-slate-400 mt-1">{{ $data['count'] }} transaksi</p>
            </div>
        </div>
        @endforeach
    </div>
</div>
@endif

{{-- Transaction table --}}
<div class="bg-white rounded-2xl shadow-sm border border-slate-100 overflow-hidden">
    <div class="px-5 py-4 border-b border-slate-100">
        <h2 class="font-semibold text-slate-800">Detail Transaksi</h2>
    </div>

    {{-- Desktop --}}
    <div class="hidden sm:block overflow-x-auto">
        <table class="w-full data-table">
            <thead>
                <tr>
                    <th>Tanggal</th>
                    <th>Cabang</th>
                    <th>Kategori</th>
                    <th>Keterangan</th>
                    <th>Tipe</th>
                    <th class="text-right">Jumlah</th>
                    <th>Oleh</th>
                </tr>
            </thead>
            <tbody>
                @forelse($transactions as $t)
                <tr>
                    <td class="text-slate-500 text-xs whitespace-nowrap">{{ $t->transaction_date->format('d M Y') }}</td>
                    <td>
                        <span class="bg-slate-100 text-slate-600 text-xs font-medium px-2 py-0.5 rounded-md">{{ $t->branch->name }}</span>
                    </td>
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
                </tr>
                @empty
                <tr><td colspan="7" class="text-center py-10 text-slate-400">Tidak ada transaksi pada periode ini</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>

    {{-- Mobile --}}
    <div class="sm:hidden divide-y divide-slate-100">
        @forelse($transactions as $t)
        <div class="p-4">
            <div class="flex justify-between items-start mb-1">
                <div>
                    <p class="font-semibold text-slate-800 text-sm">{{ $t->category->name }}</p>
                    <p class="text-xs text-slate-400">{{ $t->branch->name }} · {{ $t->transaction_date->format('d M Y') }}</p>
                </div>
                <span class="px-2 py-0.5 rounded-full text-xs font-semibold {{ $t->type === 'pemasukan' ? 'badge-masuk' : 'badge-keluar' }}">
                    {{ ucfirst($t->type) }}
                </span>
            </div>
            <p class="text-base font-bold {{ $t->type === 'pemasukan' ? 'text-green-600' : 'text-red-500' }}">
                {{ $t->type === 'pemasukan' ? '+' : '-' }}Rp {{ number_format($t->amount, 0, ',', '.') }}
            </p>
        </div>
        @empty
        <p class="text-center py-10 text-slate-400 text-sm">Tidak ada transaksi</p>
        @endforelse
    </div>
</div>
@endsection
