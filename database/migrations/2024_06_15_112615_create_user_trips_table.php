<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUserTripsTable extends Migration
{
    public function up()
    {
        Schema::create('user_trips', function (Blueprint $table) {
            $table->increments('user_trip_id');
            $table->integer('user_id')->unsigned();
            $table->integer('trip_id')->unsigned();
            $table->dateTime('created_at');
        });

        Schema::table('user_trips', function (Blueprint $table) {
            $table->foreign('user_id')->references('user_id')->on('users');
            $table->foreign('trip_id')->references('trip_id')->on('trips');
        });
    }

    public function down()
    {
        Schema::table('user_trips', function (Blueprint $table) {
            $table->dropForeign(['user_id']);
            $table->dropForeign(['trip_id']);
        });

        Schema::dropIfExists('user_trips');
    }
}
