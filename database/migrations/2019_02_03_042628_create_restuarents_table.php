<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateRestuarentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('restuarents', function (Blueprint $table) {
            $table->increments('id');
            $table->string('guest_id');
            $table->string('item_id');
            $table->string('quantity');
            $table->double('amount', 8,2);
            $table->string('payment_status')->default('Unpaid');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('restuarents');
    }
}
