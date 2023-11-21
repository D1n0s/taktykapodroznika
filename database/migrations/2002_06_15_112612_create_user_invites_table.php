<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUserinvitesTable extends Migration
{
    public function up()
    {
        Schema::create('user_invites', function (Blueprint $table) {
            $table->increments('invite_id');
            $table->integer('user_id')->unsigned();
            $table->integer('invited_by')->unsigned();
            $table->integer('invited_trip')->unsigned();
            $table->integer('permission');
        });

        Schema::table('user_invites', function (Blueprint $table) {
            $table->foreign('user_id')->references('user_id')->on('users');
            $table->foreign('invited_by')->references('user_id')->on('users');
            $table->foreign('invited_trip')->references('trip_id')->on('trips');
        });
    }

    public function down()
    {
        Schema::table('user_settings', function (Blueprint $table) {
            $table->dropForeign(['user_id']);
        });

        Schema::dropIfExists('user_settings');
    }
}

