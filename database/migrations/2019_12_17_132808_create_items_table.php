<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateItemsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('items', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('categories_id');
            $table->string('item_code',100);
            $table->string('item_name',250);
            $table->text('description')->nullable();
            $table->string('unit_name',250);
            $table->string('unit_size',250);
            $table->bigInteger('ctn_size');
            $table->double('unit_cost');
            $table->double('ctn_cost');
            $table->bigInteger('par_level');
            $table->string('taxable',250);
            $table->string('groups',250);
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
        Schema::dropIfExists('items');
    }
}
