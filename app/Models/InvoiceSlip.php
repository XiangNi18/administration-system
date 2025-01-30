<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class InvoiceSlip extends Model
{
    protected $table = 'invoice_slip';

    protected $fillable = [
        'invoice_id',
        'slip_id',
        'oa',
        'dpp',
    ];
}
