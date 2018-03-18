<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->increments('id');
            $table->string('first_name');
            $table->string('last_name');
            $table->string('email')->unique();
            $table->bigInteger('contact', false, true)->unique();
            $table->string('password');
            $table->string('aadhaar_id')->default('0');
            $table->integer('reviews')->default(0);
            $table->integer('total_rating')->default(0);
            $table->string('address');
            $table->float('lat');
            $table->float('lng');
            $table->tinyInteger('verified')->unsigned();
            $table->string('profile_picture')->default('avatar.png');
            $table->rememberToken();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('users');
    }
}
