<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCheckInsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('check_ins', function (Blueprint $table) {
            $table->increments('id');
            $table->string('guest_id');
            $table->string('guest_name');
            $table->string('check_in_date');
            $table->string('check_in_time');
            $table->string('check_out_date');
            $table->string('agent_id')->nullable();
            $table->integer('adults');
            $table->integer('childrens')->nullable();
            $table->mediumText('comments')->nullable();
            $table->string('package')->nullable();
            $table->string('mobile')->nullable();
            $table->string('organisation')->nullable();
            $table->string('previous_checkin')->nullable();
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
        Schema::dropIfExists('check_ins');
    }
}
