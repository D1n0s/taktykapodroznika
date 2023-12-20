<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateVehicleTable extends Migration
{
    public function up()
    {
        Schema::create('vehicle', function (Blueprint $table) {
            $table->increments('vehicle_id');
            $table->integer('trip_id')->unsigned();
            $table->string('name', 30);
            $table->decimal('consumption',3,1)->nullable();
            $table->enum('fuel', ['benzyna', 'diesel','gaz']);

        });
        Schema::table('vehicle', function (Blueprint $table) {
            $table->foreign('trip_id')->references('trip_id')->on('trips');
        });
    }

    public function down()
    {
        Schema::table('vehicle', function (Blueprint $table) {
            $table->dropForeign(['trip_id']);
            $table->dropColumn('trip_id');
        });
        Schema::dropIfExists('vehicle');
    }
}
