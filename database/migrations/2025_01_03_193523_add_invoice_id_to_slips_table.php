<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddInvoiceIdToSlipsTable extends Migration
{
    public function up(): void
    {
        Schema::table('slips', function (Blueprint $table) {
            $table->foreignId('invoice_id')->nullable()->constrained('invoices')->onDelete('cascade')->after('id');
        });
    }

    public function down(): void
    {
        Schema::table('slips', function (Blueprint $table) {
            $table->dropForeign(['invoice_id']);
            $table->dropColumn('invoice_id');
        });
    }
}
