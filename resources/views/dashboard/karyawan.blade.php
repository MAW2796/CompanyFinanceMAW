@extends('layouts.app')

@section('page-title', $branch->name ?? 'Dashboard Karyawan')
@section('page-subtitle', 'Periode: ' . $startDate . ' s/d ' . $endDate)

@section('content')

{{-- KPI Cards --}}
<div class="grid grid-cols-1 sm:grid-cols-3 gap-3 sm:gap-4 mb-6">
    <div class="stat-card">
        <div class="flex items-start justify-between mb-3">
            <div class="w-9 h-9 rounded-xl bg-green-50 flex items-center justify-center">
                <svg class="w-5 h-5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 11l5-5m0 0l5 5m-5-5v12"/>
                </svg>
            </div>
        </div>
        <p class="text-xs text-slate-500 mb-1">Pemasukan Bulan Ini</p>
        <p class="font-display text-2xl font-bold text-green-600">Rp {{ number_format($pemasukan, 0, ',', '.') }}</p>
    </div>
    <div class="stat-card">
        <div class="flex items-start justify-between mb-3">
            <div class="w-9 h-9 rounded-xl bg-red-50 flex items-center justify-center">
                <svg class="w-5 h-5 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 13l-5 5m0 0l-5-5m5 5V6"/>
                </svg>
            </div>
        </div>
        <p class="text-xs text-slate-500 mb-1">Pengeluaran Bulan Ini</p>
        <p class="font-display text-2xl font-bold text-red-500">Rp {{ number_format($pengeluaran, 0, ',', '.') }}</p>
    </div>
    <div class="stat-card text-white" style="background:linear-gradient(135deg, #2563EB, #1440B8)">
        <div class="flex items-start justify-between mb-3">
            <div class="w-9 h-9 rounded-xl flex items-center justify-center" style="background:rgba(255,255,255,0.2)">
                <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"/>
                </svg>
            </div>
        </div>
        <p class="text-xs text-blue-200 mb-1">Saldo Cabang {{ $branch->name ?? '' }}</p>
        <p class="font-display text-2xl font-bold text-white">Rp {{ number_format($branch->balance ?? 0, 0, ',', '.') }}</p>
    </div>
</div>

{{-- Cash Flow chart + Category breakdown --}}
<div class="grid grid-cols-1 lg:grid-cols-3 gap-5 mb-6">
    <div class="lg:col-span-2 bg-white rounded-2xl shadow-sm border border-slate-100 p-5">
        <div class="flex items-center justify-between mb-4">
            <h2 class="font-semibold text-slate-800">Cash Flow Bulanan</h2>
            <div class="flex items-center gap-4 text-xs text-slate-500">
                <span class="flex items-center gap-1.5"><span class="w-2.5 h-2.5 rounded-full bg-green-500"></span>Pemasukan</span>
                <span class="flex items-center gap-1.5"><span class="w-2.5 h-2.5 rounded-full bg-red-400"></span>Pengeluaran</span>
                <span class="flex items-center gap-1.5"><span class="w-2.5 h-2.5 rounded-full bg-blue-500"></span>Profit</span>
            </div>
        </div>
        <canvas id="cashFlowChart" height="220"></canvas>
    </div>

    <div class="bg-white rounded-2xl shadow-sm border border-slate-100 p-5">
        <h2 class="font-semibold text-slate-800 mb-4">Kategori Pengeluaran</h2>
        @if($categoryBreakdown['total'] > 0)
        <div class="relative">
            <canvas id="categoryChart" height="210"></canvas>
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
            Belum ada data pengeluaran
        </div>
        @endif
    </div>
</div>

{{-- Action bar --}}
<div class="flex items-center justify-between mb-4">
    <h2 class="font-semibold text-slate-800">Transaksi Terbaru</h2>
    <a href="{{ route('transactions.create') }}"
       class="btn-primary flex items-center gap-2 text-sm">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4"/>
        </svg>
        Input Transaksi
    </a>
</div>

{{-- Desktop table --}}
<div class="hidden sm:block bg-white rounded-2xl shadow-sm border border-slate-100 overflow-hidden mb-4">
    <table class="w-full data-table">
        <thead>
            <tr>
                <th>Tanggal</th>
                <th>Kategori</th>
                <th>Keterangan</th>
                <th>Tipe</th>
                <th class="text-right">Jumlah</th>
                <th>Diinput oleh</th>
            </tr>
        </thead>
        <tbody>
            @forelse($transactions as $t)
            <tr>
                <td class="text-slate-500 text-xs">{{ $t->transaction_date->format('d M Y') }}</td>
                <td><span class="font-medium text-slate-700">{{ $t->category->name }}</span></td>
                <td class="text-slate-400 text-xs max-w-[180px] truncate">{{ $t->description ?? '-' }}</td>
                <td>
                    <span class="px-2.5 py-1 rounded-full text-xs font-semibold {{ $t->type === 'pemasukan' ? 'badge-masuk' : 'badge-keluar' }}">
                        {{ ucfirst($t->type) }}
                    </span>
                </td>
                <td class="text-right font-semibold {{ $t->type === 'pemasukan' ? 'text-green-600' : 'text-red-500' }}">
                    {{ $t->type === 'pemasukan' ? '+' : '-' }}Rp {{ number_format($t->amount, 0, ',', '.') }}
                </td>
                <td class="text-slate-400 text-xs">{{ $t->user->name }}</td>
            </tr>
            @empty
            <tr><td colspan="6" class="text-center py-10 text-slate-400">Belum ada transaksi</td></tr>
            @endforelse
        </tbody>
    </table>
</div>

{{-- Mobile cards --}}
<div class="sm:hidden space-y-2 mb-4">
    @forelse($transactions as $t)
    <div class="bg-white rounded-xl shadow-sm border border-slate-100 p-4">
        <div class="flex justify-between items-start mb-1">
            <div>
                <p class="font-semibold text-slate-800 text-sm">{{ $t->category->name }}</p>
                @if($t->description)
                <p class="text-xs text-slate-400 mt-0.5">{{ $t->description }}</p>
                @endif
            </div>
            <span class="px-2 py-0.5 rounded-full text-xs font-semibold {{ $t->type === 'pemasukan' ? 'badge-masuk' : 'badge-keluar' }}">
                {{ ucfirst($t->type) }}
            </span>
        </div>
        <p class="text-lg font-bold {{ $t->type === 'pemasukan' ? 'text-green-600' : 'text-red-500' }}">
            {{ $t->type === 'pemasukan' ? '+' : '-' }}Rp {{ number_format($t->amount, 0, ',', '.') }}
        </p>
        <p class="text-xs text-slate-400 mt-1">{{ $t->transaction_date->format('d M Y') }} · {{ $t->user->name }}</p>
    </div>
    @empty
    <div class="text-center py-12 text-slate-400">
        <svg class="w-10 h-10 mx-auto mb-2 text-slate-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2"/>
        </svg>
        <p class="text-sm">Belum ada transaksi</p>
    </div>
    @endforelse
</div>

<div class="mt-2">{{ $transactions->links() }}</div>
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
                datasets: [{ data: categoryData.values, backgroundColor: ['#2563EB', '#EF4444', '#F59E0B', '#10B981', '#8B5CF6', '#64748B'], borderWidth: 0 }]
            },
            options: { responsive: true, cutout: '72%', plugins: { legend: { display: false } } }
        });
    }
});
</script>
@endpush
