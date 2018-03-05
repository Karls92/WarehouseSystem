<?php

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
            $table->integer('brand_id')->unsigned();
            $table->foreign('brand_id')->references('id')->on('brands')->onDelete('cascade');
            $table->integer('model_id')->unsigned();
            $table->foreign('model_id')->references('id')->on('models')->onDelete('cascade');
            $table->integer('classification_id')->unsigned();
            $table->foreign('classification_id')->references('id')->on('classifications')->onDelete('cascade');
            $table->integer('uom_id')->unsigned();
            $table->foreign('uom_id')->references('id')->on('units_of_measure')->onDelete('cascade');
            $table->string('product_code', 45)->unique();
            $table->string('name', 100);
            $table->string('description', 500);
            $table->string('observation', 500)->nullable();
            $table->string('slug',500);
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
        Schema::drop('products');
    }
}