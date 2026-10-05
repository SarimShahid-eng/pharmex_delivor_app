<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAdminTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('admins', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('firstname');
            $table->string('lastname')->nullable();
            $table->text('image')->nullable();
            $table->string('email')->unique();
            $table->enum('user_role', ['admin','user'])->default('user');
            $table->string('password');
            $table->boolean('is_active')->default('1');
            $table->text('rights')->nullable();
            $table->rememberToken();
            $table->dateTime('deleted_at')->nullable();
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
        Schema::dropIfExists('admins');
    }
}
