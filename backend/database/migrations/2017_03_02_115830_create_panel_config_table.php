<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePanelConfigTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('panel_config', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('user_id')->unsigned();//necesario agregar unsigned para relacionar las tablas
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');// relaciona el user_id de esta tabla con el id de la tabla de usuarios y crea un triger interno para que se elimine en cascada
            $table->string('theme_color',45)->default('skin-black');
            $table->char('screen')->default('N');
            $table->char('breadcrumb')->default('Y');
            $table->char('box_design')->default('N');
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
        Schema::drop('panel_config');
    }
}
