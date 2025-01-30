<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Slip extends Model
{
    protected $fillable = [
        'slip_code',
        'plat_number',
        'driver_name',
        'delivery_order',
        'bruto_muat',
        'tara_muat',
        'bruto_bongkar',
        'tara_bongkar',
        'date_slip',
        'customer_id',
        'is_invoiced',
        'invoice_id'
    ];

    /**
     * Relasi ke model Customer.
     *
     * @return BelongsTo
     */
    public function customer(): BelongsTo
    {
        return $this->belongsTo(Customer::class);
    }

    /**
     * Relasi ke model Invoice.
     *
     * @return BelongsTo
     */
    public function invoice(): BelongsTo
    {
        return $this->belongsTo(Invoice::class);
    }

    /**
     * Booted Model Events.
     */
    protected static function booted()
    {
        // Event saat slip dibuat
        static::creating(function ($slip) {
            // Generate slip_code secara otomatis
            $lastSlip = Slip::latest('id')->first();
            $lastSlipCode = $lastSlip ? $lastSlip->slip_code : null;

            if (!$lastSlipCode) {
                $slip->slip_code = 'SLP-001';
            } else {
                $lastNumber = (int)substr($lastSlipCode, 4); // Ambil angka terakhir dari slip_code
                $newNumber = str_pad($lastNumber + 1, 3, '0', STR_PAD_LEFT); // Tambahkan 1, format 3 digit
                $slip->slip_code = 'SLP-' . $newNumber;
            }
        });

        // Event saat slip diupdate
        static::updating(function ($slip) {
            if ($slip->is_invoiced) {
                throw new \Exception("Slip {$slip->slip_code} sudah diinvoicekan dan tidak dapat diubah.");
            }
        });

        // Event saat slip dihapus
        static::deleting(function ($slip) {
            if ($slip->is_invoiced) {
                throw new \Exception("Slip {$slip->slip_code} sudah diinvoicekan dan tidak dapat dihapus.");
            }
        });
    }
}
