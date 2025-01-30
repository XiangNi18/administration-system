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
        Schema::table('slips', function (Blueprint $table) {
            $table->dropForeign(['transaction_id']); // Hapus foreign key
            $table->dropColumn('transaction_id'); // Hapus kolom transaction_id
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('slips', function (Blueprint $table) {
            $table->foreignId('transaction_id')->constrained()->onDelete('cascade'); // Tambahkan kembali kolom dan foreign key
        });
    }
};
