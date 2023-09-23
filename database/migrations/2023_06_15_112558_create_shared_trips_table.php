<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSharedTripsTable extends Migration
{
    public function up()
    {
        Schema::create('shared_trips', function (Blueprint $table) {
            $table->increments('shared_id');
            $table->integer('trip_id')->unsigned();
            $table->integer('shared_with_user_id')->unsigned();
        });

        Schema::table('shared_trips', function (Blueprint $table) {
            $table->foreign('trip_id')->references('trip_id')->on('trips');
            $table->foreign('shared_with_user_id')->references('user_id')->on('users');
        });
    }

    public function down()
    {
        Schema::table('shared_trips', function (Blueprint $table) {
            $table->dropForeign(['trip_id']);
            $table->dropForeign(['shared_with_user_id']);
        });

        Schema::dropIfExists('shared_trips');
    }
}
