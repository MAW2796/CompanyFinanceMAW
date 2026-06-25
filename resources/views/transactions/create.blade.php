@extends('layouts.app')

@section('page-title', 'Input Transaksi Baru')
@section('page-subtitle', 'Catat pemasukan atau pengeluaran cabang')

@section('content')
<div class="max-w-2xl mx-auto">
    <div class="bg-white rounded-2xl shadow-sm border border-slate-100 overflow-hidden">
        {{-- Form header --}}
        <div class="bg-gradient-to-r from-blue-600 to-blue-700 px-6 py-5">
            <h2 class="font-display text-white font-bold text-lg">Form Transaksi</h2>
            <p class="text-blue-200 text-sm mt-0.5">Isi semua field yang diperlukan dengan teliti</p>
        </div>

        <form method="POST" action="{{ route('transactions.store') }}" enctype="multipart/form-data" class="p-6 space-y-5">
            @csrf

            {{-- Tipe transaksi - prominent toggle --}}
            <div>
                <label class="block text-sm font-semibold text-slate-700 mb-2">Tipe Transaksi <span class="text-red-500">*</span></label>
                <div class="grid grid-cols-2 gap-3" id="type-toggle">
                    <label class="cursor-pointer">
                        <input type="radio" name="type" value="pemasukan" class="sr-only" {{ old('type', 'pemasukan') === 'pemasukan' ? 'checked' : '' }}>
                        <div class="type-btn flex items-center gap-3 p-4 rounded-xl border-2 transition-all {{ old('type', 'pemasukan') === 'pemasukan' ? 'border-green-500 bg-green-50' : 'border-slate-200 bg-white hover:border-slate-300' }}">
                            <div class="w-9 h-9 rounded-lg {{ old('type', 'pemasukan') === 'pemasukan' ? 'bg-green-500' : 'bg-slate-100' }} flex items-center justify-center transition-all">
                                <svg class="w-5 h-5 {{ old('type', 'pemasukan') === 'pemasukan' ? 'text-white' : 'text-slate-500' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 11l5-5m0 0l5 5m-5-5v12"/>
                                </svg>
                            </div>
                            <div>
                                <p class="font-semibold text-sm {{ old('type', 'pemasukan') === 'pemasukan' ? 'text-green-700' : 'text-slate-700' }}">Pemasukan</p>
                                <p class="text-xs text-slate-400">Uang masuk</p>
                            </div>
                        </div>
                    </label>
                    <label class="cursor-pointer">
                        <input type="radio" name="type" value="pengeluaran" class="sr-only" {{ old('type') === 'pengeluaran' ? 'checked' : '' }}>
                        <div class="type-btn flex items-center gap-3 p-4 rounded-xl border-2 transition-all {{ old('type') === 'pengeluaran' ? 'border-red-500 bg-red-50' : 'border-slate-200 bg-white hover:border-slate-300' }}">
                            <div class="w-9 h-9 rounded-lg {{ old('type') === 'pengeluaran' ? 'bg-red-500' : 'bg-slate-100' }} flex items-center justify-center transition-all">
                                <svg class="w-5 h-5 {{ old('type') === 'pengeluaran' ? 'text-white' : 'text-slate-500' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 13l-5 5m0 0l-5-5m5 5V6"/>
                                </svg>
                            </div>
                            <div>
                                <p class="font-semibold text-sm {{ old('type') === 'pengeluaran' ? 'text-red-600' : 'text-slate-700' }}">Pengeluaran</p>
                                <p class="text-xs text-slate-400">Uang keluar</p>
                            </div>
                        </div>
                    </label>
                </div>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                @if($branches)
                <div>
                    <label class="block text-sm font-semibold text-slate-700 mb-1.5">Cabang <span class="text-red-500">*</span></label>
                    <select name="branch_id" class="field-input" required>
                        <option value="">— Pilih Cabang —</option>
                        @foreach($branches as $b)
                            <option value="{{ $b->id }}" {{ old('branch_id') == $b->id ? 'selected' : '' }}>{{ $b->name }} ({{ $b->code }})</option>
                        @endforeach
                    </select>
                </div>
                @endif

                <div>
                    <label class="block text-sm font-semibold text-slate-700 mb-1.5">Kategori <span class="text-red-500">*</span></label>
                    <select name="category_id" id="category-select" class="field-input" required>
                        <option value="">— Pilih Kategori —</option>
                        @foreach($categories as $c)
                            <option value="{{ $c->id }}" data-type="{{ $c->type }}" {{ old('category_id') == $c->id ? 'selected' : '' }}>
                                {{ $c->name }}
                            </option>
                        @endforeach
                    </select>
                </div>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-semibold text-slate-700 mb-1.5">Jumlah (Rp) <span class="text-red-500">*</span></label>
                    <div class="relative">
                        <span class="absolute left-3 top-1/2 -translate-y-1/2 text-slate-400 text-sm font-medium">Rp</span>
                        <input type="text" inputmode="numeric" id="amount_display"
                               value="{{ old('amount') ? number_format((float) old('amount'), 0, ',', '.') : '' }}"
                               oninput="formatAmountInput(this)"
                               class="field-input pl-9"
                               placeholder="0" required>
                        <input type="hidden" name="amount" id="amount_raw" value="{{ old('amount') }}">
                    </div>
                </div>

                <div>
                    <label class="block text-sm font-semibold text-slate-700 mb-1.5">Tanggal Transaksi <span class="text-red-500">*</span></label>
                    <input type="date" name="transaction_date"
                           value="{{ old('transaction_date', date('Y-m-d')) }}"
                           class="field-input" required>
                </div>
            </div>

            <div>
                <label class="block text-sm font-semibold text-slate-700 mb-1.5">Keterangan / Deskripsi</label>
                <input type="text" name="description"
                       value="{{ old('description') }}"
                       class="field-input"
                       placeholder="Contoh: Pembayaran listrik bulan Juni, Penerimaan dari klien ABC...">
            </div>

            <div>
                <label class="block text-sm font-semibold text-slate-700 mb-1.5">Bukti / Lampiran <span class="text-slate-400 font-normal">(opsional, maks 2MB)</span></label>
                <div class="border-2 border-dashed border-slate-200 rounded-xl p-4 text-center hover:border-blue-300 transition-colors">
                    <input type="file" name="attachment" id="attachment" accept="image/*"
                           class="hidden" onchange="showFileName(this)">
                    <label for="attachment" class="cursor-pointer flex flex-col items-center gap-2">
                        <svg class="w-8 h-8 text-slate-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                        </svg>
                        <p class="text-sm text-slate-500" id="file-label">Klik untuk pilih gambar bukti</p>
                        <p class="text-xs text-slate-400">JPG, PNG, WEBP maks 2MB</p>
                    </label>
                </div>
            </div>

            <div class="flex items-center gap-3 pt-2 border-t border-slate-100">
                <button type="submit" class="btn-primary flex items-center gap-2 flex-1 sm:flex-none justify-center">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                    </svg>
                    Simpan Transaksi
                </button>
                <a href="{{ route('transactions.index') }}" class="text-sm text-slate-500 hover:text-slate-700 px-4 py-2">Batal</a>
            </div>
        </form>
    </div>
</div>

<script>
function showFileName(input) {
    const label = document.getElementById('file-label');
    if (input.files[0]) {
        label.textContent = '✓ ' + input.files[0].name;
        label.classList.add('text-blue-600', 'font-medium');
    }
}

// Type toggle visual
document.querySelectorAll('input[name="type"]').forEach(radio => {
    radio.addEventListener('change', function() {
        document.querySelectorAll('.type-btn').forEach(btn => {
            btn.classList.remove('border-green-500', 'bg-green-50', 'border-red-500', 'bg-red-50');
            btn.classList.add('border-slate-200');
            const icon = btn.querySelector('div');
            icon.classList.remove('bg-green-500', 'bg-red-500');
            icon.classList.add('bg-slate-100');
            const svg = icon.querySelector('svg');
            svg.classList.remove('text-white');
            svg.classList.add('text-slate-500');
            const title = btn.querySelector('p');
            title.classList.remove('text-green-700', 'text-red-600');
            title.classList.add('text-slate-700');
        });
        const selectedBtn = this.closest('label').querySelector('.type-btn');
        const isIncome = this.value === 'pemasukan';
        selectedBtn.classList.remove('border-slate-200');
        selectedBtn.classList.add(isIncome ? 'border-green-500' : 'border-red-500', isIncome ? 'bg-green-50' : 'bg-red-50');
        const icon = selectedBtn.querySelector('div');
        icon.classList.remove('bg-slate-100');
        icon.classList.add(isIncome ? 'bg-green-500' : 'bg-red-500');
        const svg = icon.querySelector('svg');
        svg.classList.remove('text-slate-500');
        svg.classList.add('text-white');
        const title = selectedBtn.querySelector('p');
        title.classList.remove('text-slate-700');
        title.classList.add(isIncome ? 'text-green-700' : 'text-red-600');

        // Filter kategori
        const catSelect = document.getElementById('category-select');
        catSelect.querySelectorAll('option[data-type]').forEach(opt => {
            opt.style.display = opt.dataset.type === this.value ? '' : 'none';
        });
        catSelect.value = '';
    });
});

// Initial filter
(function() {
    const checked = document.querySelector('input[name="type"]:checked');
    if (checked) {
        const catSelect = document.getElementById('category-select');
        catSelect.querySelectorAll('option[data-type]').forEach(opt => {
            opt.style.display = opt.dataset.type === checked.value ? '' : 'none';
        });
    }
})();

// Format input nominal dengan separator ribuan (titik) secara live
function formatAmountInput(el) {
    let raw = el.value.replace(/[^0-9]/g, '');
    raw = raw.replace(/^0+(?=\d)/, ''); // buang leading zero
    document.getElementById('amount_raw').value = raw;
    el.value = raw ? new Intl.NumberFormat('id-ID').format(raw) : '';
}
</script>
@endsection
