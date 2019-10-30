<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSettingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('settings', function (Blueprint $table) {
            $table->increments('id');
            $table->string('hotel_name')->default('Hotel');
            $table->string('logo')->default('logo.png');
            $table->string('description')->default('Hotel Management');
            $table->string('address')->default('Agartala');
            $table->string('contact')->default('9612699356');
            $table->string('currency')->default('$');
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
        Schema::dropIfExists('settings');
    }
}
