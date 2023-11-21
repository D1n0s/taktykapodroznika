<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePermTripTable extends Migration
{
    public function up()
    {
        Schema::create('trips_permissions', function (Blueprint $table) {
            $table->increments('permission_id');
            $table->string('name');
        });


    }

    public function down()
    {
        Schema::table('trips_permissions', function (Blueprint $table) {
        });

        Schema::dropIfExists('trips_permissions');
    }
}
