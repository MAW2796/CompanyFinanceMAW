<?php

namespace App\Http\Controllers;

use App\Models\Branch;
use App\Models\Category;
use App\Models\Transaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class TransactionController extends Controller
{
    /**
     * List transaksi. Admin bisa lihat semua/filter cabang, karyawan hanya cabangnya.
     */
    public function index(Request $request)
    {
        $user = Auth::user();

        $query = Transaction::with(['branch', 'category', 'user'])
            ->latest('transaction_date');

        if ($user->isAdmin()) {
            if ($request->filled('branch_id')) {
                $query->where('branch_id', $request->branch_id);
            }
        } else {
            $query->where('branch_id', $user->branch_id);
        }

        if ($request->filled('type')) {
            $query->where('type', $request->type);
        }

        $transactions = $query->paginate(20)->withQueryString();
        $branches = $user->isAdmin() ? Branch::all() : null;

        return view('transactions.index', compact('transactions', 'branches'));
    }

    public function create()
    {
        $user = Auth::user();
        $categories = Category::all();

        // Karyawan hanya bisa input untuk cabangnya sendiri; admin bisa pilih cabang
        $branches = $user->isAdmin() ? Branch::all() : null;

        return view('transactions.create', compact('categories', 'branches'));
    }

    public function store(Request $request)
    {
        $user = Auth::user();

        $rules = [
            'category_id' => 'required|exists:categories,id',
            'type' => 'required|in:pemasukan,pengeluaran',
            'amount' => 'required|numeric|min:0.01',
            'transaction_date' => 'required|date',
            'description' => 'nullable|string|max:255',
            'attachment' => 'nullable|file|image|max:2048',
        ];

        // Admin wajib pilih cabang; karyawan otomatis pakai cabang sendiri
        if ($user->isAdmin()) {
            $rules['branch_id'] = 'required|exists:branches,id';
        }

        $validated = $request->validate($rules);

        $branchId = $user->isAdmin() ? $validated['branch_id'] : $user->branch_id;

        if (! $branchId) {
            return back()->withErrors(['branch_id' => 'Anda belum terdaftar di cabang manapun. Hubungi admin.']);
        }

        $path = null;
        if ($request->hasFile('attachment')) {
            $path = $request->file('attachment')->store('attachments', 'public');
        }

        Transaction::create([
            'branch_id' => $branchId,
            'category_id' => $validated['category_id'],
            'user_id' => $user->id,
            'type' => $validated['type'],
            'amount' => $validated['amount'],
            'transaction_date' => $validated['transaction_date'],
            'description' => $validated['description'] ?? null,
            'attachment' => $path,
        ]);

        return redirect()->route('transactions.index')
            ->with('success', 'Transaksi berhasil dicatat.');
    }

    public function destroy(Transaction $transaction)
    {
        $user = Auth::user();

        // Karyawan hanya boleh hapus transaksi cabangnya sendiri & miliknya sendiri
        if (! $user->isAdmin() && $transaction->user_id !== $user->id) {
            abort(403);
        }

        if ($transaction->attachment) {
            Storage::disk('public')->delete($transaction->attachment);
        }

        $transaction->delete();

        return back()->with('success', 'Transaksi dihapus.');
    }
}
