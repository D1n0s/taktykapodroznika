<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCategorieMarksTable extends Migration
{
    public function up()
    {
        Schema::create('categorie_marks', function (Blueprint $table) {
            $table->increments('category_mark_id');
            $table->integer('category_id')->unsigned();
            $table->integer('mark_id')->unsigned();
           // $table->primary(['category_mark_id', 'category_id', 'mark_id']); //Czy na pewno ?
        });

        Schema::table('categorie_marks', function (Blueprint $table) {
            $table->foreign('category_id')->references('category_id')->on('categories');
            $table->foreign('mark_id')->references('mark_id')->on('marks');
        });
    }

    public function down()
    {
        Schema::table('categorie_marks', function (Blueprint $table) {
            $table->dropForeign(['category_id']);
            $table->dropForeign(['mark_id']);
        });

        Schema::dropIfExists('categorie_marks');
    }
}
