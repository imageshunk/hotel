<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddColumnsToCheckInsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('check_ins', function (Blueprint $table) {
            $table->double('total', 8,2)->default(0);
            $table->integer('nights')->default(1);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('check_ins', function (Blueprint $table) {
            $table->dropColumn('total', 8,2);
            $table->dropColumn('nights');
        });
    }
}
