<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateProductsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('products', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name');
            $table->integer('subcategory_id')->unsigned();
            $table->foreign('subcategory_id')->references('id')->on('subcategories');
            $table->integer('lender_id')->unsigned();
            $table->foreign('lender_id')->references('id')->on('users');
            $table->boolean('availability');
            $table->text('description');
            $table->enum('duration', [0, 1, 2]);
            $table->integer('rate_1')->unsigned();
            $table->integer('rate_2')->unsigned();
            $table->integer('rate_3')->unsigned();
            $table->string('address');
            $table->decimal('lat', 9, 6);
            $table->decimal('lng', 9, 6);
            $table->string('image');

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
        Schema::dropIfExists('products');
    }
}
