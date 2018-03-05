<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSiteConfigTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('site_config', function (Blueprint $table) {
            $table->increments('id');
            $table->string('page_description',300);
            $table->string('map_height',10);
            $table->string('map_zoom',10);
            $table->string('map_color',10);
            $table->string('map_latitude',100);
            $table->string('map_longitude',100);
            $table->string('social_facebook',500)->nullable();
            $table->string('social_twitter',500)->nullable();
            $table->string('social_instagram',500)->nullable();
            $table->string('social_youtube',500)->nullable();
            $table->string('social_google_plus',500)->nullable();
            $table->string('social_mercado_libre',500)->nullable();
            $table->string('api_id_google_analytics',100)->nullable();
            $table->string('api_id_facebook',100)->nullable();
            $table->string('email',100);
            $table->string('password_email',100);
            $table->string('smtp_host',100);
            $table->string('smtp_port',10);
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
        Schema::drop('site_config');
    }
}
