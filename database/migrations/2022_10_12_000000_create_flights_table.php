<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
		// create flights table
        Schema::create('flights', function (Blueprint $table) {
            $table->id();
			$table->foreignId('user_id')->constrained();
            $table->string('origin_airport_code');
            $table->string('origin_airport_name');
            $table->string('origin_city');
            $table->string('origin_country');
            $table->date('departure_date');
            $table->time('departure_time');
            $table->string('destination_airport_code');
            $table->string('destination_airport_name');
            $table->string('destination_city');
            $table->string('destination_country');
            $table->date('arrival_date');
            $table->time('arrival_time');
            $table->string('number_of_connections');
            $table->string('main_airline');
            $table->string('number_of_airlines');
            $table->string('trip_duration');
            $table->integer('price');
            $table->integer('date_diff');
            $table->timestamp('dateTime', 0);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('flights');
    }
};
