<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTripsTable extends Migration
{
    public function up()
    {
        Schema::create('trips', function (Blueprint $table) {
            $table->increments('trip_id');
            $table->integer('owner_id')->unsigned();
            $table->string('title',30);
            $table->date('start_date');
            $table->date('end_date');
            //szczegóły dojazdu
            $table->time('travel_time')->nullable();
            $table->decimal('distance',10,2)->default(0.00)->nullable();
            $table->integer('avg_speed')->default(0)->nullable();
            $table->integer('fuel_consumed')->default(0)->nullable();
            $table->decimal('travel_cost',10,2)->default(0.00);
            $table->integer('persons')->default(1);
            $table->decimal('petrol_cost',4,2)->nullable();
            $table->decimal('diesel_cost',4,2)->nullable();
            $table->decimal('gas_cost',4,2)->nullable();

        });
        Schema::table('trips', function (Blueprint $table) {
            $table->foreign('owner_id')->references('user_id')->on('users');
        });
    }

    public function down()
    {
        Schema::table('trips', function (Blueprint $table) {
            $table->dropForeign(['owner_id']); // Usuń klucz obcy
            $table->dropColumn('owner_id'); // Usuń kolumnę owner_id
        });
        Schema::dropIfExists('trips');
    }
}
