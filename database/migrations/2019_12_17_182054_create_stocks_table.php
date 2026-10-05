<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateStocksTable extends Migration {

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up() {
        Schema::create('stocks', function (Blueprint $table) {
            $table->bigIncrements('id');

            $table->bigInteger('location_id');
            $table->bigInteger('pallet_id')->nullable();
            $table->bigInteger('item_id');
            $table->integer('qty');
            $table->date('expiry_date');
            $table->enum('type', ['in', 'out']);
            $table->text('description')->nullable();
            $table->integer('user_id');
            $table->tinyInteger('is_live');
            $table->string('item_code_stock', 100)->nullable();
            $table->date('batch_code', 100);
            $table->string('bar_code', 255)->nullable();
            $table->dateTime('deleted_at')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down() {
        Schema::dropIfExists('stocks');
    }

}
