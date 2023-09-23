<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePublicTripsTable extends Migration
{
    public function up()
    {
        Schema::create('public_trips', function (Blueprint $table) {
            $table->increments('public_id');
            $table->integer('trip_id', )->unsigned();
            $table->dateTime('created_at');
            $table->dateTime('updated_at');
        });

        Schema::table('public_trips', function (Blueprint $table) {
            $table->foreign('trip_id')->references('trip_id')->on('trips');
        });
    }

    public function down()
    {
        Schema::table('public_trips', function (Blueprint $table) {
            $table->dropForeign(['trip_id']);
        });

        Schema::dropIfExists('public_trips');
    }
}
