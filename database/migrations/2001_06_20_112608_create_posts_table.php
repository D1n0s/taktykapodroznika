<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePostsTable extends Migration
{
    public function up()
    {
        Schema::create('posts', function (Blueprint $table) {
            $table->increments('post_id');
            $table->integer('trip_id')->unsigned();
            $table->string('title', 30);
            $table->integer('day')->nullable();
            $table->date('date')->nullable();
        });
        Schema::table('posts', function (Blueprint $table) {
            $table->foreign('trip_id')->references('trip_id')->on('trips');
        });
    }

    public function down()
    {
        Schema::table('posts', function (Blueprint $table) {
            $table->dropForeign(['trip_id']); // Usuń klucz obcy
            $table->dropColumn('trip_id'); // Usuń kolumnę owner_id
        });
        Schema::dropIfExists('posts');
    }
}
