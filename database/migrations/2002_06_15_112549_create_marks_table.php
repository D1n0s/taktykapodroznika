<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMarksTable extends Migration
{
    public function up()
    {
        Schema::create('marks', function (Blueprint $table) {
            $table->increments('mark_id'); // Dodaj autoinkrementację
            $table->integer('trip_id')->unsigned();
            $table->string('name', 255);
            $table->text('desc');
            $table->string('address', 255);
            $table->decimal('latitude', 20, 11);
            $table->decimal('longitude', 20, 11);
            $table->integer('queue')->nullable();
            $table->integer('category_id')->nullable();
            $table->boolean('is_general');
            $table->dateTime('created_at');
            $table->dateTime('updated_at');
        });

        Schema::table('marks', function (Blueprint $table) {
            $table->foreign('trip_id')->references('trip_id')->on('trips');
        });
    }

    public function down()
    {
        Schema::table('marks', function (Blueprint $table) {
            $table->dropForeign(['trip_id']);
        });

        Schema::dropIfExists('marks');
    }
}
