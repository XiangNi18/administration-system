<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddCategoryToAccountsTable2 extends Migration
{
    public function up()
    {
        Schema::table('accounts', function (Blueprint $table) {
            // Menambahkan kolom 'category' dengan tipe string
            $table->string('category')->nullable();
        });
    }

    public function down()
    {
        Schema::table('accounts', function (Blueprint $table) {
            // Menghapus kolom 'category' jika rollback
            $table->dropColumn('category');
        });
    }
}
