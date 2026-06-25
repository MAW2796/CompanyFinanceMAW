<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <style>
        body { font-family: 'DejaVu Sans', sans-serif; font-size: 11px; color: #222; }
        h1 { font-size: 16px; margin-bottom: 2px; }
        p.period { color: #666; margin-top: 0; margin-bottom: 16px; }
        table { width: 100%; border-collapse: collapse; margin-bottom: 20px; }
        th, td { border: 1px solid #ddd; padding: 6px 8px; text-align: left; }
        th { background-color: #f3f4f6; }
        .summary-table td { border: none; padding: 4px 8px; }
        .text-right { text-align: right; }
        .text-green { color: #15803d; }
        .text-red { color: #b91c1c; }
        .total-row td { font-weight: bold; border-top: 2px solid #333; }
    </style>
</head>
<body>
    <h1>Laporan Keuangan Perusahaan</h1>
    <p class="period">Periode: {{ \Carbon\Carbon::parse($startDate)->format('d M Y') }} s/d {{ \Carbon\Carbon::parse($endDate)->format('d M Y') }}</p>

    <table class="summary-table" style="width: 50%; margin-bottom: 24px;">
        <tr>
            <td>Total Pemasukan</td>
            <td class="text-right text-green">Rp {{ number_format($summary['pemasukan'], 0, ',', '.') }}</td>
        </tr>
        <tr>
            <td>Total Pengeluaran</td>
            <td class="text-right text-red">Rp {{ number_format($summary['pengeluaran'], 0, ',', '.') }}</td>
        </tr>
        <tr class="total-row">
            <td>Selisih</td>
            <td class="text-right">Rp {{ number_format($summary['selisih'], 0, ',', '.') }}</td>
        </tr>
    </table>

    <table>
        <thead>
            <tr>
                <th>Tanggal</th>
                <th>Cabang</th>
                <th>Kategori</th>
                <th>Tipe</th>
                <th class="text-right">Jumlah</th>
                <th>Keterangan</th>
                <th>Diinput Oleh</th>
            </tr>
        </thead>
        <tbody>
            @forelse($transactions as $t)
            <tr>
                <td>{{ $t->transaction_date->format('d-m-Y') }}</td>
                <td>{{ $t->branch->name }}</td>
                <td>{{ $t->category->name }}</td>
                <td>{{ ucfirst($t->type) }}</td>
                <td class="text-right {{ $t->type === 'pemasukan' ? 'text-green' : 'text-red' }}">
                    Rp {{ number_format($t->amount, 0, ',', '.') }}
                </td>
                <td>{{ $t->description }}</td>
                <td>{{ $t->user->name }}</td>
            </tr>
            @empty
            <tr><td colspan="7" style="text-align:center;">Tidak ada transaksi pada periode ini.</td></tr>
            @endforelse
        </tbody>
    </table>

    <p style="color:#999; font-size:9px; margin-top:24px;">
        Dicetak otomatis pada {{ now()->format('d M Y H:i') }}
    </p>
</body>
</html>
