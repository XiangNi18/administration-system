<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('invoices', function (Blueprint $table) {
            $table->id();
            $table->string('invoice_number')->unique();
            $table->foreignId('customer_id')->constrained()->onDelete('cascade');
            $table->date('invoice_date');
            $table->decimal('total_dpp', 15, 2);
            $table->decimal('ppn', 15, 2)->default(0);
            $table->decimal('pph23', 15, 2)->default(0);
            $table->decimal('total_invoice', 15, 2);
            $table->timestamps();
        });

        Schema::create('invoice_slip', function (Blueprint $table) {
            $table->id();
            $table->foreignId('invoice_id')->constrained()->onDelete('cascade');
            $table->foreignId('slip_id')->constrained()->onDelete('cascade');
            $table->decimal('oa', 15, 2); // Ongkos Angkut
            $table->decimal('dpp', 15, 2); // DPP per slip
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('invoice_slip');
        Schema::dropIfExists('invoices');
    }
};
