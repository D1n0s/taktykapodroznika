<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAttractionTable extends Migration
{
    public function up()
    {
        Schema::create('attractions', function (Blueprint $table) {
            $table->increments('attraction_id');
            $table->integer('post_id')->unsigned();
            $table->string('title', 255);
            $table->text('desc');
            $table->decimal('cost',10,2)->default(0.00);
            $table->time('duration')->nullable();
            $table->time('time_start')->nullable();
            $table->time('time_end')->nullable();
            $table->integer('mark_id')->unsigned()->nullable();

        });
        Schema::table('attractions', function (Blueprint $table) {
            $table->foreign('post_id')->references('post_id')->on('posts');
        });
        Schema::table('attractions', function (Blueprint $table) {
            $table->foreign('mark_id')->references('mark_id')->on('marks');
        });
    }

    public function down()
    {
        Schema::table('attractions', function (Blueprint $table) {
            $table->dropForeign(['trip_id']); // Usuń klucz obcy
            $table->dropColumn('trip_id'); // Usuń kolumnę owner_id
        });
        Schema::dropIfExists('attractions');
    }
}
