<?php

namespace App\Http\Controllers;

use App\Models\Branch;
use App\Models\Transaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Carbon;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        $user = Auth::user();

        // Filter rentang tanggal (default: bulan ini)
        $startDate = $request->input('start_date', now()->startOfMonth()->toDateString());
        $endDate = $request->input('end_date', now()->endOfMonth()->toDateString());

        if ($user->isAdmin()) {
            // ADMIN: lihat semua cabang + konsolidasi
            $branches = Branch::all();

            $perBranch = $branches->map(function (Branch $branch) use ($startDate, $endDate) {
                $pemasukan = $branch->transactions()
                    ->where('type', 'pemasukan')
                    ->whereBetween('transaction_date', [$startDate, $endDate])
                    ->sum('amount');

                $pengeluaran = $branch->transactions()
                    ->where('type', 'pengeluaran')
                    ->whereBetween('transaction_date', [$startDate, $endDate])
                    ->sum('amount');

                return [
                    'branch' => $branch,
                    'pemasukan' => $pemasukan,
                    'pengeluaran' => $pengeluaran,
                    'selisih' => $pemasukan - $pengeluaran,
                    'saldo' => $branch->balance,
                ];
            });

            $konsolidasi = [
                'pemasukan' => $perBranch->sum('pemasukan'),
                'pengeluaran' => $perBranch->sum('pengeluaran'),
                'selisih' => $perBranch->sum('selisih'),
                'saldo' => $perBranch->sum('saldo'),
            ];

            $cashFlow = $this->monthlyCashFlow(null);
            $categoryBreakdown = $this->categoryBreakdown(null, $startDate, $endDate);
            $recentTransactions = Transaction::with(['category', 'branch', 'user'])
                ->latest('transaction_date')
                ->latest('id')
                ->take(7)
                ->get();

            return view('dashboard.admin', compact(
                'perBranch', 'konsolidasi', 'startDate', 'endDate',
                'cashFlow', 'categoryBreakdown', 'recentTransactions'
            ));
        }

        // KARYAWAN: hanya lihat cabang sendiri
        $branch = $user->branch;

        $transactions = Transaction::where('branch_id', $branch?->id)
            ->whereBetween('transaction_date', [$startDate, $endDate])
            ->latest('transaction_date')
            ->with(['category', 'user'])
            ->paginate(15);

        $pemasukan = Transaction::where('branch_id', $branch?->id)
            ->where('type', 'pemasukan')
            ->whereBetween('transaction_date', [$startDate, $endDate])
            ->sum('amount');

        $pengeluaran = Transaction::where('branch_id', $branch?->id)
            ->where('type', 'pengeluaran')
            ->whereBetween('transaction_date', [$startDate, $endDate])
            ->sum('amount');

        $cashFlow = $this->monthlyCashFlow($branch?->id);
        $categoryBreakdown = $this->categoryBreakdown($branch?->id, $startDate, $endDate);

        return view('dashboard.karyawan', compact(
            'branch', 'transactions', 'pemasukan', 'pengeluaran', 'startDate', 'endDate',
            'cashFlow', 'categoryBreakdown'
        ));
    }

    /**
     * Ringkasan pemasukan/pengeluaran/profit 6 bulan terakhir untuk grafik Cash Flow.
     */
    private function monthlyCashFlow(?int $branchId): array
    {
        $labels = [];
        $pemasukan = [];
        $pengeluaran = [];
        $profit = [];

        for ($i = 5; $i >= 0; $i--) {
            $month = now()->subMonths($i);
            $start = $month->copy()->startOfMonth()->toDateString();
            $end = $month->copy()->endOfMonth()->toDateString();

            $query = Transaction::whereBetween('transaction_date', [$start, $end]);
            if ($branchId) {
                $query->where('branch_id', $branchId);
            }

            $masuk = (clone $query)->where('type', 'pemasukan')->sum('amount');
            $keluar = (clone $query)->where('type', 'pengeluaran')->sum('amount');

            $labels[] = $month->translatedFormat('M');
            $pemasukan[] = (float) $masuk;
            $pengeluaran[] = (float) $keluar;
            $profit[] = (float) ($masuk - $keluar);
        }

        return compact('labels', 'pemasukan', 'pengeluaran', 'profit');
    }

    /**
     * Komposisi pengeluaran per kategori untuk grafik donut.
     */
    private function categoryBreakdown(?int $branchId, string $startDate, string $endDate): array
    {
        $query = Transaction::where('type', 'pengeluaran')
            ->whereBetween('transaction_date', [$startDate, $endDate]);
        if ($branchId) {
            $query->where('branch_id', $branchId);
        }

        $rows = $query->with('category')
            ->get()
            ->groupBy(fn ($t) => $t->category->name ?? 'Lainnya')
            ->map(fn ($group) => $group->sum('amount'))
            ->sortDesc();

        $total = $rows->sum();
        $labels = $rows->keys()->take(5)->values()->all();
        $values = $rows->values()->take(5)->values()->all();

        // Gabungkan sisanya jadi "Lainnya" jika kategori lebih dari 5
        if ($rows->count() > 5) {
            $labels[] = 'Lainnya';
            $values[] = $rows->values()->slice(5)->sum();
        }

        $percentages = array_map(fn ($v) => $total > 0 ? round(($v / $total) * 100) : 0, $values);

        return [
            'labels' => $labels,
            'values' => $values,
            'percentages' => $percentages,
            'total' => $total,
        ];
    }
}
