<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateJournalEntriesTable extends Migration
{
    public function up()
    {
        Schema::create('journal_entries', function (Blueprint $table) {
            $table->id();
            $table->foreignId('account_id')->constrained()->onDelete('cascade'); // Hubungkan dengan tabel 'accounts'
            $table->date('date');               // Tanggal transaksi jurnal
            $table->string('description');      // Deskripsi jurnal
            $table->decimal('debit', 15, 2)->default(0);  // Jumlah debit
            $table->decimal('credit', 15, 2)->default(0); // Jumlah kredit
            $table->timestamps(); // Waktu pembuatan dan pembaruan data
        });
    }

    public function down()
    {
        Schema::dropIfExists('journal_entries');
    }
}
