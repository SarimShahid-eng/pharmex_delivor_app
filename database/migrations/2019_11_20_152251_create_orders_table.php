<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

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
            $table->bigIncrements('id');
            $table->string('order_no', 50);
            $table->bigInteger('branch_id')->nullable();
            $table->bigInteger('agent_id')->nullable();
            $table->bigInteger('booked_by');
            $table->string('sender_name')->nullable();
            $table->string('sender_mobile')->nullable();
            $table->string('sender_address')->nullable();
            $table->string('sender_cnic')->nullable();
            $table->string('receiver_name')->nullable();
            $table->string('receiver_mobile')->nullable();
            $table->string('receiver_address')->nullable();
            $table->string('receiver_cnic')->nullable();
            $table->enum('sending_through', ['local', 'air', 'sea'])->default('air');
            $table->enum('sending_mode', ['normal', 'express']);
            $table->double('total_amount')->default(0);
            $table->text('types_of_goods')->nullable();
            $table->double('agent_expense')->nullable()->default(0);
            $table->text('agent_desc')->nullable();
            $table->string('airway_bill_no')->nullable();
            $table->string('weight')->nullable();
            $table->string('d_or_n')->nullable();
            $table->enum('status', ['booked', 'assigned', 'received', 'shipped'])->nullable();
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
        Schema::dropIfExists('orders');
    }
}
