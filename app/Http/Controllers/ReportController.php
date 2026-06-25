<?php

namespace App\Http\Controllers;

use App\Exports\TransactionsExport;
use App\Models\Branch;
use App\Models\Transaction;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use Barryvdh\DomPDF\Facade\Pdf;

class ReportController extends Controller
{
    /**
     * Ambil query transaksi sesuai filter (dipakai bersama oleh index, exportPdf, exportExcel)
     */
    private function filteredQuery(Request $request)
    {
        $startDate = $request->input('start_date', now()->startOfMonth()->toDateString());
        $endDate = $request->input('end_date', now()->endOfMonth()->toDateString());

        $query = Transaction::with(['branch', 'category', 'user'])
            ->whereBetween('transaction_date', [$startDate, $endDate])
            ->orderBy('transaction_date');

        if ($request->filled('branch_id')) {
            $query->where('branch_id', $request->branch_id);
        }

        if ($request->filled('type')) {
            $query->where('type', $request->type);
        }

        return [$query, $startDate, $endDate];
    }

    public function index(Request $request)
    {
        [$query, $startDate, $endDate] = $this->filteredQuery($request);

        $transactions = $query->get();

        $summary = [
            'pemasukan' => $transactions->where('type', 'pemasukan')->sum('amount'),
            'pengeluaran' => $transactions->where('type', 'pengeluaran')->sum('amount'),
        ];
        $summary['selisih'] = $summary['pemasukan'] - $summary['pengeluaran'];

        $branches = Branch::all();

        return view('reports.index', compact('transactions', 'summary', 'branches', 'startDate', 'endDate'));
    }

    public function exportExcel(Request $request)
    {
        [$query, $startDate, $endDate] = $this->filteredQuery($request);
        $transactions = $query->get();

        $filename = 'laporan-keuangan_'.$startDate.'_'.$endDate.'.xlsx';

        return Excel::download(new TransactionsExport($transactions), $filename);
    }

    public function exportPdf(Request $request)
    {
        [$query, $startDate, $endDate] = $this->filteredQuery($request);
        $transactions = $query->get();

        $summary = [
            'pemasukan' => $transactions->where('type', 'pemasukan')->sum('amount'),
            'pengeluaran' => $transactions->where('type', 'pengeluaran')->sum('amount'),
        ];
        $summary['selisih'] = $summary['pemasukan'] - $summary['pengeluaran'];

        $pdf = Pdf::loadView('reports.pdf', compact('transactions', 'summary', 'startDate', 'endDate'))
            ->setPaper('a4', 'portrait');

        $filename = 'laporan-keuangan_'.$startDate.'_'.$endDate.'.pdf';

        return $pdf->download($filename);
    }
}
