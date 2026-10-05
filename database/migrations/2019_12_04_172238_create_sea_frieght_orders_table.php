<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSeaFrieghtOrdersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sea_freight_orders', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('added_by_id');
            $table->string('container_no');
            $table->double('total_amount')->nullable();
            $table->text('customer_details')->nullable();
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
        Schema::dropIfExists('sea_freight_orders');
    }
}
