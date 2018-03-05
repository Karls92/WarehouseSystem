<?php

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
            $table->string('username',45)->unique();
            $table->string('first_name',45);
            $table->string('last_name',45);
            $table->string('phone',15);
            $table->string('image',45)->default('default.png');
            $table->string('email')->unique();
            $table->string('password', 60)->default(bcrypt('123456'));
            $table->enum('type',['member','admin'])->default('member');
            $table->string('level',45);
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
        Schema::drop('users');
    }
}
