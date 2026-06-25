@extends('layouts.app')

@section('page-title', 'Kelola Kategori')
@section('page-subtitle', 'Pengaturan kategori pemasukan dan pengeluaran')

@section('content')
<div class="grid grid-cols-1 lg:grid-cols-3 gap-5">

    {{-- Add Form --}}
    <div class="lg:col-span-1">
        <div class="bg-white rounded-2xl shadow-sm border border-slate-100 overflow-hidden sticky top-20">
            <div class="bg-gradient-to-r from-purple-600 to-purple-700 px-5 py-4">
                <h2 class="font-display text-white font-bold">Tambah Kategori</h2>
                <p class="text-purple-200 text-xs mt-0.5">Buat kategori baru untuk klasifikasi</p>
            </div>
            <form method="POST" action="{{ route('categories.store') }}" class="p-5 space-y-4">
                @csrf
                <div>
                    <label class="block text-xs font-semibold text-slate-600 mb-1.5">Nama Kategori <span class="text-red-500">*</span></label>
                    <input type="text" name="name" class="field-input" required placeholder="contoh: Gaji, Sewa Kantor, Listrik...">
                </div>
                <div>
                    <label class="block text-xs font-semibold text-slate-600 mb-2">Tipe <span class="text-red-500">*</span></label>
                    <div class="grid grid-cols-2 gap-2">
                        <label class="cursor-pointer">
                            <input type="radio" name="type" value="pemasukan" class="sr-only" checked>
                            <div class="type-pill text-center py-2.5 rounded-xl border-2 border-green-400 bg-green-50 text-green-700 text-sm font-semibold transition-all">
                                ↑ Pemasukan
                            </div>
                        </label>
                        <label class="cursor-pointer">
                            <input type="radio" name="type" value="pengeluaran" class="sr-only">
                            <div class="type-pill text-center py-2.5 rounded-xl border-2 border-slate-200 bg-white text-slate-500 text-sm font-semibold transition-all">
                                ↓ Pengeluaran
                            </div>
                        </label>
                    </div>
                </div>
                <button class="btn-primary w-full flex items-center justify-center gap-2">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4"/>
                    </svg>
                    Tambah Kategori
                </button>
            </form>
        </div>
    </div>

    {{-- Categories List --}}
    <div class="lg:col-span-2 space-y-4">

        {{-- Pemasukan --}}
        <div class="bg-white rounded-2xl shadow-sm border border-slate-100 overflow-hidden">
            <div class="flex items-center gap-2 px-5 py-3.5 border-b border-slate-100 bg-green-50">
                <div class="w-2 h-2 rounded-full bg-green-500"></div>
                <h3 class="font-semibold text-green-800 text-sm">Kategori Pemasukan</h3>
                <span class="ml-auto text-xs text-green-600 bg-green-100 px-2 py-0.5 rounded-full">{{ $categories->where('type','pemasukan')->count() }} kategori</span>
            </div>
            @forelse($categories->where('type','pemasukan') as $c)
            <div class="flex items-center gap-3 px-5 py-3 border-t border-slate-50 first:border-t-0">
                <div class="w-7 h-7 rounded-lg bg-green-50 flex items-center justify-center">
                    <svg class="w-3.5 h-3.5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 11l5-5m0 0l5 5m-5-5v12"/>
                    </svg>
                </div>
                <p class="flex-1 text-sm font-medium text-slate-700">{{ $c->name }}</p>
                <form method="POST" action="{{ route('categories.destroy', $c) }}" onsubmit="return confirm('Hapus kategori {{ $c->name }}?')">
                    @csrf @method('DELETE')
                    <button class="text-xs text-red-400 hover:text-red-600 hover:bg-red-50 px-2 py-1 rounded transition-colors">Hapus</button>
                </form>
            </div>
            @empty
            <p class="px-5 py-4 text-sm text-slate-400">Belum ada kategori pemasukan</p>
            @endforelse
        </div>

        {{-- Pengeluaran --}}
        <div class="bg-white rounded-2xl shadow-sm border border-slate-100 overflow-hidden">
            <div class="flex items-center gap-2 px-5 py-3.5 border-b border-slate-100 bg-red-50">
                <div class="w-2 h-2 rounded-full bg-red-500"></div>
                <h3 class="font-semibold text-red-800 text-sm">Kategori Pengeluaran</h3>
                <span class="ml-auto text-xs text-red-600 bg-red-100 px-2 py-0.5 rounded-full">{{ $categories->where('type','pengeluaran')->count() }} kategori</span>
            </div>
            @forelse($categories->where('type','pengeluaran') as $c)
            <div class="flex items-center gap-3 px-5 py-3 border-t border-slate-50 first:border-t-0">
                <div class="w-7 h-7 rounded-lg bg-red-50 flex items-center justify-center">
                    <svg class="w-3.5 h-3.5 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 13l-5 5m0 0l-5-5m5 5V6"/>
                    </svg>
                </div>
                <p class="flex-1 text-sm font-medium text-slate-700">{{ $c->name }}</p>
                <form method="POST" action="{{ route('categories.destroy', $c) }}" onsubmit="return confirm('Hapus kategori {{ $c->name }}?')">
                    @csrf @method('DELETE')
                    <button class="text-xs text-red-400 hover:text-red-600 hover:bg-red-50 px-2 py-1 rounded transition-colors">Hapus</button>
                </form>
            </div>
            @empty
            <p class="px-5 py-4 text-sm text-slate-400">Belum ada kategori pengeluaran</p>
            @endforelse
        </div>
    </div>
</div>

<script>
document.querySelectorAll('input[name="type"]').forEach(r => {
    r.addEventListener('change', function() {
        document.querySelectorAll('.type-pill').forEach(p => {
            p.className = 'type-pill text-center py-2.5 rounded-xl border-2 border-slate-200 bg-white text-slate-500 text-sm font-semibold transition-all';
        });
        const pill = this.closest('label').querySelector('.type-pill');
        if (this.value === 'pemasukan') {
            pill.className = 'type-pill text-center py-2.5 rounded-xl border-2 border-green-400 bg-green-50 text-green-700 text-sm font-semibold transition-all';
        } else {
            pill.className = 'type-pill text-center py-2.5 rounded-xl border-2 border-red-400 bg-red-50 text-red-700 text-sm font-semibold transition-all';
        }
    });
});
</script>
@endsection
