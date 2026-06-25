<?php

namespace App\Exports;

use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class TransactionsExport implements FromCollection, WithHeadings, WithMapping, WithStyles
{
    public function __construct(protected Collection $transactions)
    {
    }

    public function collection(): Collection
    {
        return $this->transactions;
    }

    public function headings(): array
    {
        return [
            'Tanggal', 'Cabang', 'Kategori', 'Tipe', 'Jumlah (Rp)', 'Keterangan', 'Diinput Oleh',
        ];
    }

    /**
     * @param \App\Models\Transaction $transaction
     */
    public function map($transaction): array
    {
        return [
            $transaction->transaction_date->format('d-m-Y'),
            $transaction->branch->name,
            $transaction->category->name,
            ucfirst($transaction->type),
            (float) $transaction->amount,
            $transaction->description,
            $transaction->user->name,
        ];
    }

    public function styles(Worksheet $sheet): array
    {
        return [
            1 => ['font' => ['bold' => true]],
        ];
    }
}
