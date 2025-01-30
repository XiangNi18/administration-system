<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\Schema;

class DropJournalsTable extends Migration
{
    public function up()
    {
        Schema::dropIfExists('journals');
    }

    public function down()
    {
        // Logic untuk rollback jika diperlukan
    }
}
