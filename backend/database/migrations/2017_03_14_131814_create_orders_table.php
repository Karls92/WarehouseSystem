<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateOrdersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('client_id')->unsigned();
            $table->foreign('client_id')->references('id')->on('clients')->onDelete('cascade');
            $table->integer('out_order_id')->nullable();
            $table->string('code',45)->unique();
            $table->string('received_by',100);
            $table->string('delivered_by',100);
            $table->enum('type',['entry','out','devolution']);
            $table->enum('is_processed',['Y','N'])->detault('N');
            $table->timestamp('date',20);
            $table->string('description',500)->nullable();
            $table->string('slug',500);
            $table->timestamps();
        });
    
        /*
         * relacion de muchos a muchos de la tabla Orders y la tabla Products
         * es necesario llamar a la tabla creada como el singular de ambas tablas
         * para ello se debe colocar en orden alfabetico
         */
        Schema::create('order_product', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('order_id')->unsigned();
            $table->foreign('order_id')->references('id')->on('orders')->onDelete('cascade');
            $table->integer('product_id')->unsigned();
            $table->foreign('product_id')->references('id')->on('products')->onDelete('cascade');
            $table->integer('quantity');
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
        Schema::drop('order_product');
        Schema::drop('orders');
    }
}