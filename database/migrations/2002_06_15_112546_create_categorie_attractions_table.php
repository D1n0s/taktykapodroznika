<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCategorieAttractionsTable extends Migration
{
    public function up()
    {
        Schema::create('categorie_attractions', function (Blueprint $table) {
            $table->increments('category_attraction_id');
            $table->string('name',255);
            $table->string('icon',255);
        });


    }

    public function down()
    {
        Schema::table('categorie_attractions', function (Blueprint $table) {

        });

        Schema::dropIfExists('categorie_marks');
    }
}
