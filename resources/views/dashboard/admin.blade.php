@extends('layouts.app')

@section('page-title', 'Dashboard Admin')
@section('page-subtitle', 'Konsolidasi keuangan seluruh cabang')

@section('content')

{{-- KPI Cards --}}
<div class="grid grid-cols-2 lg:grid-cols-4 gap-3 sm:gap-4 mb-6">
    {{-- Pemasukan --}}
    <div class="stat-card">
        <div class="flex items-start justify-between mb-3">
            <div class="w-9 h-9 rounded-xl bg-green-50 flex items-center justify-center">
                <svg class="w-5 h-5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 11l5-5m0 0l5 5m-5-5v12"/>
                </svg>
            </div>
            <span class="text-xs font-medium text-green-600 bg-green-50 px-2 py-0.5 rounded-full">Masuk</span>
        </div>
        <p class="text-xs text-slate-500 mb-1">Total Pemasukan</p>
        <p class="font-display text-lg sm:text-2xl font-bold text-slate-800">Rp {{ number_format($konsolidasi['pemasukan'], 0, ',', '.') }}</p>
        <p class="text-xs text-slate-400 mt-1">Periode yang dipilih</p>
    </div>

    {{-- Pengeluaran --}}
    <div class="stat-card">
        <div class="flex items-start justify-between mb-3">
            <div class="w-9 h-9 rounded-xl bg-red-50 flex items-center justify-center">
                <svg class="w-5 h-5 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 13l-5 5m0 0l-5-5m5 5V6"/>
                </svg>
            </div>
            <span class="text-xs font-medium text-red-600 bg-red-50 px-2 py-0.5 rounded-full">Keluar</span>
        </div>
        <p class="text-xs text-slate-500 mb-1">Total Pengeluaran</p>
        <p class="font-display text-lg sm:text-2xl font-bold text-slate-800">Rp {{ number_format($konsolidasi['pengeluaran'], 0, ',', '.') }}</p>
        <p class="text-xs text-slate-400 mt-1">Periode yang dipilih</p>
    </div>

    {{-- Net --}}
    <div class="stat-card {{ $konsolidasi['selisih'] >= 0 ? 'border-l-4 border-l-green-400' : 'border-l-4 border-l-red-400' }}">
        <div class="flex items-start justify-between mb-3">
            <div class="w-9 h-9 rounded-xl bg-blue-50 flex items-center justify-center">
                <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 7h6m0 10v-3m-3 3h.01M9 17h.01M9 11h.01M12 11h.01M15 11h.01M4 6h16a2 2 0 012 2v8a2 2 0 01-2 2H4a2 2 0 01-2-2V8a2 2 0 012-2z"/>
                </svg>
            </div>
            <span class="text-xs font-medium {{ $konsolidasi['selisih'] >= 0 ? 'text-green-600 bg-green-50' : 'text-red-600 bg-red-50' }} px-2 py-0.5 rounded-full">Net</span>
        </div>
        <p class="text-xs text-slate-500 mb-1">Selisih Periode</p>
        <p class="font-display text-lg sm:text-2xl font-bold {{ $konsolidasi['selisih'] >= 0 ? 'text-green-600' : 'text-red-600' }}">
            Rp {{ number_format(abs($konsolidasi['selisih']), 0, ',', '.') }}
        </p>
        <p class="text-xs text-slate-400 mt-1">{{ $konsolidasi['selisih'] >= 0 ? '▲ Surplus' : '▼ Defisit' }}</p>
    </div>

    {{-- Total Saldo --}}
    <div class="stat-card text-white" style="background:linear-gradient(135deg, #2563EB, #1440B8)">
        <div class="flex items-start justify-between mb-3">
            <div class="w-9 h-9 rounded-xl flex items-center justify-center" style="background:rgba(255,255,255,0.2)">
                <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"/>
                </svg>
            </div>
            <span class="text-xs font-medium px-2 py-0.5 rounded-full" style="color:rgba(255,255,255,0.85);background:rgba(255,255,255,0.2)">Total</span>
        </div>
        <p class="text-xs text-blue-200 mb-1">Total Saldo Semua Cabang</p>
        <p class="font-display text-lg sm:text-2xl font-bold text-white">Rp {{ number_format($konsolidasi['saldo'], 0, ',', '.') }}</p>
        <p class="text-xs text-blue-200 mt-1">Kumulatif seluruh cabang</p>
    </div>
</div>

{{-- Filter + Quick Stats Row --}}
<div class="bg-white rounded-2xl shadow-sm border border-slate-100 p-4 mb-5">
    <form method="GET" class="flex flex-wrap gap-3 items-end">
        <div>
            <label class="block text-xs font-medium text-slate-500 mb-1.5">Dari Tanggal</label>
            <input type="date" name="start_date" value="{{ $startDate }}"
                   class="field-input" style="width:auto">
        </div>
        <div>
            <label class="block text-xs font-medium text-slate-500 mb-1.5">Sampai Tanggal</label>
            <input type="date" name="end_date" value="{{ $endDate }}"
                   class="field-input" style="width:auto">
        </div>
        <button class="btn-primary flex items-center gap-2">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2a1 1 0 01-.293.707L13 13.414V19a1 1 0 01-.553.894l-4 2A1 1 0 017 21v-7.586L3.293 6.707A1 1 0 013 6V4z"/>
            </svg>
            Terapkan Filter
        </button>
        <a href="{{ route('dashboard') }}" class="text-sm text-slate-500 hover:text-slate-700 self-end pb-0.5">Reset</a>
        <div class="ml-auto flex gap-2">
            <a href="{{ route('reports.index') }}" class="flex items-center gap-1.5 text-xs font-medium text-blue-600 bg-blue-50 hover:bg-blue-100 px-3 py-2 rounded-lg transition-colors">
                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                </svg>
                Laporan Lengkap
            </a>
        </div>
    </form>
</div>

{{-- Cash Flow chart + Category breakdown --}}
<div class="grid grid-cols-1 lg:grid-cols-3 gap-5 mb-5">
    {{-- Cash Flow Bulanan --}}
    <div class="lg:col-span-2 bg-white rounded-2xl shadow-sm border border-slate-100 p-5">
        <div class="flex items-center justify-between mb-4">
            <h2 class="font-semibold text-slate-800">Cash Flow Bulanan</h2>
            <div class="flex items-center gap-4 text-xs text-slate-500">
                <span class="flex items-center gap-1.5"><span class="w-2.5 h-2.5 rounded-full bg-green-500"></span>Pemasukan</span>
                <span class="flex items-center gap-1.5"><span class="w-2.5 h-2.5 rounded-full bg-red-400"></span>Pengeluaran</span>
                <span class="flex items-center gap-1.5"><span class="w-2.5 h-2.5 rounded-full bg-blue-500"></span>Profit</span>
            </div>
        </div>
        <canvas id="cashFlowChart" height="230"></canvas>
    </div>

    {{-- Kategori Pengeluaran --}}
    <div class="bg-white rounded-2xl shadow-sm border border-slate-100 p-5">
        <h2 class="font-semibold text-slate-800 mb-4">Kategori Pengeluaran</h2>
        @if($categoryBreakdown['total'] > 0)
        <div class="relative">
            <canvas id="categoryChart" height="220"></canvas>
            <div class="absolute inset-0 flex flex-col items-center justify-center pointer-events-none">
                <p class="text-xs text-slate-400">Total</p>
                <p class="font-display font-bold text-slate-800 text-sm">Rp {{ number_format($categoryBreakdown['total'], 0, ',', '.') }}</p>
            </div>
        </div>
        <div class="mt-4 space-y-2">
            @php $colors = ['#2563EB', '#EF4444', '#F59E0B', '#10B981', '#8B5CF6', '#64748B']; @endphp
            @foreach($categoryBreakdown['labels'] as $i => $label)
            <div class="flex items-center justify-between text-xs">
                <span class="flex items-center gap-2 text-slate-500">
                    <span class="w-2.5 h-2.5 rounded-full" style="background:{{ $colors[$i] ?? '#94A3B8' }}"></span>
                    {{ $label }}
                </span>
                <span class="font-medium text-slate-700">{{ $categoryBreakdown['percentages'][$i] }}%</span>
            </div>
            @endforeach
        </div>
        @else
        <div class="flex flex-col items-center justify-center py-10 text-slate-400 text-sm">
            <svg class="w-10 h-10 mb-2 text-slate-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M11 3.055A9 9 0 1020.945 13H11V3.055z"/>
            </svg>
            Belum ada data pengeluaran
        </div>
        @endif
    </div>
</div>

{{-- Branch breakdown --}}
<div class="bg-white rounded-2xl shadow-sm border border-slate-100 overflow-hidden mb-5">
    <div class="flex items-center justify-between px-5 py-4 border-b border-slate-100">
        <div>
            <h2 class="font-semibold text-slate-800">Rincian per Cabang</h2>
            <p class="text-xs text-slate-400 mt-0.5">{{ $perBranch->count() }} cabang aktif</p>
        </div>
        <a href="{{ route('branches.index') }}" class="text-xs text-blue-600 hover:underline">Kelola Cabang →</a>
    </div>

    {{-- Desktop table --}}
    <div class="hidden sm:block overflow-x-auto">
        <table class="w-full data-table">
            <thead>
                <tr>
                    <th>Cabang</th>
                    <th>Pemasukan Periode</th>
                    <th>Pengeluaran Periode</th>
                    <th>Net Periode</th>
                    <th>Saldo Saat Ini</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody>
                @foreach($perBranch as $row)
                <tr>
                    <td>
                        <div class="flex items-center gap-2.5">
                            <div class="w-7 h-7 rounded-lg bg-slate-100 flex items-center justify-center text-xs font-bold text-slate-600">
                                {{ strtoupper(substr($row['branch']->code, 0, 2)) }}
                            </div>
                            <div>
                                <p class="font-medium text-slate-800 text-sm">{{ $row['branch']->name }}</p>
                                <p class="text-xs text-slate-400">{{ $row['branch']->code }}</p>
                            </div>
                        </div>
                    </td>
                    <td class="text-green-600 font-medium">Rp {{ number_format($row['pemasukan'], 0, ',', '.') }}</td>
                    <td class="text-red-500 font-medium">Rp {{ number_format($row['pengeluaran'], 0, ',', '.') }}</td>
                    <td>
                        <span class="font-semibold {{ $row['selisih'] >= 0 ? 'text-green-600' : 'text-red-500' }}">
                            {{ $row['selisih'] >= 0 ? '+' : '' }}Rp {{ number_format($row['selisih'], 0, ',', '.') }}
                        </span>
                    </td>
                    <td class="font-bold text-slate-800">Rp {{ number_format($row['saldo'], 0, ',', '.') }}</td>
                    <td>
                        @php $pct = $row['pemasukan'] > 0 ? round(($row['selisih'] / $row['pemasukan']) * 100) : 0; @endphp
                        <span class="inline-flex items-center gap-1 text-xs font-medium px-2 py-1 rounded-full {{ $row['selisih'] >= 0 ? 'bg-green-50 text-green-700' : 'bg-red-50 text-red-700' }}">
                            {{ $row['selisih'] >= 0 ? '▲' : '▼' }} {{ abs($pct) }}%
                        </span>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    {{-- Mobile cards --}}
    <div class="sm:hidden divide-y divide-slate-100">
        @foreach($perBranch as $row)
        <div class="p-4">
            <div class="flex items-center gap-2 mb-3">
                <div class="w-8 h-8 rounded-lg bg-slate-100 flex items-center justify-center text-xs font-bold text-slate-600">
                    {{ strtoupper(substr($row['branch']->code, 0, 2)) }}
                </div>
                <div>
                    <p class="font-semibold text-slate-800 text-sm">{{ $row['branch']->name }}</p>
                    <p class="text-xs text-slate-400">{{ $row['branch']->code }}</p>
                </div>
                <span class="ml-auto text-xs font-medium px-2 py-0.5 rounded-full {{ $row['selisih'] >= 0 ? 'badge-masuk' : 'badge-keluar' }}">
                    {{ $row['selisih'] >= 0 ? 'Surplus' : 'Defisit' }}
                </span>
            </div>
            <div class="grid grid-cols-2 gap-2 text-sm">
                <div class="bg-green-50 rounded-lg p-2.5">
                    <p class="text-xs text-green-600 mb-0.5">Pemasukan</p>
                    <p class="font-semibold text-green-700 text-xs">Rp {{ number_format($row['pemasukan'], 0, ',', '.') }}</p>
                </div>
                <div class="bg-red-50 rounded-lg p-2.5">
                    <p class="text-xs text-red-600 mb-0.5">Pengeluaran</p>
                    <p class="font-semibold text-red-700 text-xs">Rp {{ number_format($row['pengeluaran'], 0, ',', '.') }}</p>
                </div>
            </div>
            <div class="flex justify-between mt-2 text-xs text-slate-500">
                <span>Saldo: <strong class="text-slate-800">Rp {{ number_format($row['saldo'], 0, ',', '.') }}</strong></span>
                <span class="{{ $row['selisih'] >= 0 ? 'text-green-600' : 'text-red-600' }} font-semibold">
                    {{ $row['selisih'] >= 0 ? '+' : '' }}Rp {{ number_format($row['selisih'], 0, ',', '.') }}
                </span>
            </div>
        </div>
        @endforeach
    </div>
</div>

{{-- Transaksi Terbaru --}}
<div class="bg-white rounded-2xl shadow-sm border border-slate-100 overflow-hidden mb-5">
    <div class="flex items-center justify-between px-5 py-4 border-b border-slate-100">
        <h2 class="font-semibold text-slate-800">Transaksi Terbaru</h2>
        <a href="{{ route('transactions.index') }}" class="text-xs font-medium text-blue-600 hover:underline">Lihat Semua</a>
    </div>
    <div class="hidden sm:block overflow-x-auto">
        <table class="w-full data-table">
            <thead>
                <tr>
                    <th>Tanggal</th>
                    <th>Kategori</th>
                    <th>Cabang</th>
                    <th>Deskripsi</th>
                    <th class="text-right">Nominal</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody>
                @forelse($recentTransactions as $t)
                <tr>
                    <td class="text-slate-500 text-xs whitespace-nowrap">{{ $t->transaction_date->format('d M Y') }}</td>
                    <td class="font-medium text-slate-700 text-sm">{{ $t->category->name }}</td>
                    <td><span class="bg-slate-100 text-slate-600 text-xs font-medium px-2 py-0.5 rounded-md">{{ $t->branch->name }}</span></td>
                    <td class="text-slate-400 text-xs max-w-[180px] truncate">{{ $t->description ?? '—' }}</td>
                    <td class="text-right font-semibold {{ $t->type === 'pemasukan' ? 'text-green-600' : 'text-red-500' }}">
                        Rp {{ number_format($t->amount, 0, ',', '.') }}
                    </td>
                    <td>
                        <span class="px-2.5 py-1 rounded-full text-xs font-semibold {{ $t->type === 'pemasukan' ? 'badge-masuk' : 'badge-keluar' }}">
                            {{ $t->type === 'pemasukan' ? 'Masuk' : 'Keluar' }}
                        </span>
                    </td>
                </tr>
                @empty
                <tr><td colspan="6" class="text-center py-10 text-slate-400">Belum ada transaksi</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <div class="sm:hidden divide-y divide-slate-100">
        @forelse($recentTransactions as $t)
        <div class="p-4 flex justify-between items-start">
            <div>
                <p class="font-semibold text-slate-800 text-sm">{{ $t->category->name }}</p>
                <p class="text-xs text-slate-400">{{ $t->branch->name }} · {{ $t->transaction_date->format('d M Y') }}</p>
            </div>
            <span class="font-semibold text-sm {{ $t->type === 'pemasukan' ? 'text-green-600' : 'text-red-500' }}">
                Rp {{ number_format($t->amount, 0, ',', '.') }}
            </span>
        </div>
        @empty
        <div class="text-center py-10 text-slate-400">Belum ada transaksi</div>
        @endforelse
    </div>
</div>

{{-- Quick link - Transaksi terbaru --}}
<div class="bg-white rounded-2xl shadow-sm border border-slate-100 overflow-hidden">
    <div class="flex items-center justify-between px-5 py-4 border-b border-slate-100">
        <h2 class="font-semibold text-slate-800">Aksi Cepat</h2>
    </div>
    <div class="p-4 grid grid-cols-2 sm:grid-cols-4 gap-3">
        <a href="{{ route('transactions.create') }}" class="flex flex-col items-center gap-2 p-4 rounded-xl bg-blue-50 hover:bg-blue-100 transition-colors text-center">
            <div class="w-10 h-10 rounded-xl bg-blue-600 flex items-center justify-center">
                <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                </svg>
            </div>
            <p class="text-xs font-semibold text-blue-700">Input Transaksi</p>
        </a>
        <a href="{{ route('transactions.index') }}" class="flex flex-col items-center gap-2 p-4 rounded-xl bg-slate-50 hover:bg-slate-100 transition-colors text-center">
            <div class="w-10 h-10 rounded-xl bg-slate-600 flex items-center justify-center">
                <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2"/>
                </svg>
            </div>
            <p class="text-xs font-semibold text-slate-700">Semua Transaksi</p>
        </a>
        <a href="{{ route('reports.index') }}" class="flex flex-col items-center gap-2 p-4 rounded-xl bg-purple-50 hover:bg-purple-100 transition-colors text-center">
            <div class="w-10 h-10 rounded-xl bg-purple-600 flex items-center justify-center">
                <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                </svg>
            </div>
            <p class="text-xs font-semibold text-purple-700">Buat Laporan</p>
        </a>
        <a href="{{ route('branches.index') }}" class="flex flex-col items-center gap-2 p-4 rounded-xl bg-orange-50 hover:bg-orange-100 transition-colors text-center">
            <div class="w-10 h-10 rounded-xl bg-orange-500 flex items-center justify-center">
                <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1"/>
                </svg>
            </div>
            <p class="text-xs font-semibold text-orange-700">Tambah Cabang</p>
        </a>
    </div>
</div>

@endsection

@push('scripts')
<script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/4.4.1/chart.umd.min.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function () {
    const cashFlowData = @json($cashFlow);
    const ctx1 = document.getElementById('cashFlowChart');
    if (ctx1) {
        new Chart(ctx1, {
            type: 'line',
            data: {
                labels: cashFlowData.labels,
                datasets: [
                    { label: 'Pemasukan', data: cashFlowData.pemasukan, borderColor: '#22C55E', backgroundColor: 'rgba(34,197,94,0.08)', tension: 0.35, fill: true, pointRadius: 3 },
                    { label: 'Pengeluaran', data: cashFlowData.pengeluaran, borderColor: '#F87171', backgroundColor: 'rgba(248,113,113,0.06)', tension: 0.35, fill: true, pointRadius: 3 },
                    { label: 'Profit', data: cashFlowData.profit, borderColor: '#2563EB', backgroundColor: 'rgba(37,99,235,0.06)', tension: 0.35, fill: true, pointRadius: 3 },
                ]
            },
            options: {
                responsive: true,
                plugins: { legend: { display: false } },
                scales: {
                    y: { ticks: { callback: (v) => (v/1000000).toFixed(0) + ' Jt' }, grid: { color: '#F1F5F9' } },
                    x: { grid: { display: false } }
                }
            }
        });
    }

    const categoryData = @json($categoryBreakdown);
    const ctx2 = document.getElementById('categoryChart');
    if (ctx2 && categoryData.total > 0) {
        new Chart(ctx2, {
            type: 'doughnut',
            data: {
                labels: categoryData.labels,
                datasets: [{
                    data: categoryData.values,
                    backgroundColor: ['#2563EB', '#EF4444', '#F59E0B', '#10B981', '#8B5CF6', '#64748B'],
                    borderWidth: 0,
                }]
            },
            options: {
                responsive: true,
                cutout: '72%',
                plugins: { legend: { display: false } }
            }
        });
    }
});
</script>
@endpush
