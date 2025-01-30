<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Facades\DB; // Tambahkan ini
use Illuminate\Support\Facades\Log;


class Invoice extends Model
{
    protected $fillable = [
        'invoice_number',
        'customer_id',
        'freight_cost',
        'invoice_date',
        'total_dpp',
        'ppn',
        'pph23',
        'total_invoice',
    ];

    public function customer(): BelongsTo
    {
        return $this->belongsTo(Customer::class);
    }

    public function slips(): HasMany
    {
        return $this->hasMany(Slip::class);
    }

    protected static function booted()
{
    static::creating(function ($invoice) {
        // Validasi slip yang dipilih
        $selectedSlipIds = request()->input('selected_slips') ?? [];
        $validSlips = Slip::whereIn('id', $selectedSlipIds)
            ->whereNull('invoice_id') // Pastikan slip belum diinvoice
            ->get();

        if ($validSlips->count() !== count($selectedSlipIds)) {
            throw new \Exception('Beberapa slip sudah diinvoicekan.');
        }

        // Nomor invoice
        $year = now()->format('Y');
        $month = now()->format('m');
        $lastNumber = self::whereYear('created_at', $year)
            ->whereMonth('created_at', $month)
            ->max(DB::raw("CAST(SUBSTRING_INDEX(invoice_number, '/', -1) AS UNSIGNED)")) ?? 0;
        $invoice->invoice_number = "INV/PHAN/{$year}-{$month}/" . str_pad($lastNumber + 1, 3, '0', STR_PAD_LEFT);
    });

    static::saved(function ($invoice) {
        $selectedSlipIds = request()->input('selected_slips') ?? [];

        if (!empty($selectedSlipIds)) {
            Slip::whereIn('id', $selectedSlipIds)->update([
                'invoice_id' => $invoice->id,
            ]);
        } else {
            Log::warning('No slips selected for invoice ID: ' . $invoice->id);
        }
    });
}
}

