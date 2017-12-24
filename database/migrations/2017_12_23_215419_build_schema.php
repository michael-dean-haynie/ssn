<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class BuildSchema extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            // $table->increments('id');
            $table->timestamps();
            $table->string('name');
        });

        Schema::create('friendships', function (Blueprint $table) {
            $table->increments('id');
            $table->timestamps();
            $table->string('user_a');
            $table->string('user_b');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('friendships');
        Schema::dropIfExists('users');
    }
}   