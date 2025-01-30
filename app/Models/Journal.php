<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Journal extends Model
{
    protected $fillable = [
        'description', // Menambahkan kolom description
        'date',
    ]; // kolom lain yang bisa diassign massal
    public function journalEntries()
    {
        return $this->hasMany(JournalEntry::class); // Relasi ke JournalEntry
    }
    public function getSumDebitAttribute()
    {
        return $this->journalEntries->sum('debit'); // Menghitung total debit
    }

    public function getSumCreditAttribute()
    {
        return $this->journalEntries->sum('credit'); // Menghitung total kredit
    }
}
