<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Account extends Model
{
    protected $fillable = ['name', 'type', 'code', 'category'];

    public function transactionDetails()
    {
        return $this->hasMany(TransactionDetail::class);
    }

    public function journal()
    {
        return $this->hasMany(JournalEntry::class);
    }

    public function getTotalDebitAttribute()
    {
        $startDate = request('filters.start_date') ?? now()->startOfMonth();
        $endDate = request('filters.end_date') ?? now()->endOfMonth();

        return $this->journal()
            ->whereBetween('date', [$startDate, $endDate])
            ->sum('debit');
    }

    public function getTotalCreditAttribute()
    {
        $startDate = request('filters.start_date') ?? now()->startOfMonth();
        $endDate = request('filters.end_date') ?? now()->endOfMonth();

        return $this->journal()
            ->whereBetween('date', [$startDate, $endDate])
            ->sum('credit');
    }

    public function getBalanceAttribute()
    {
        $startDate = request('filters.start_date') ?? now()->startOfMonth();
        $endDate = request('filters.end_date') ?? now()->endOfMonth();

        $debit = $this->transactionDetails()
            ->whereBetween('created_at', [$startDate, $endDate])
            ->sum('debit');

        $credit = $this->transactionDetails()
            ->whereBetween('created_at', [$startDate, $endDate])
            ->sum('credit');

        return $debit - $credit;
    }

}
