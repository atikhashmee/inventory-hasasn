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
            $table->id();
            $table->string('order_number', 200)->nullable();
            $table->unsignedBigInteger('customer_id');
            $table->unsignedBigInteger('shop_id');
            $table->unsignedBigInteger('user_id')->comment('who by order is placed');
            $table->decimal('sub_total', 10, 2)->comment('sum of order_details total');
            $table->decimal('discount_amount', 10, 2)->default(0.00);
            $table->decimal('total_amount', 10, 2)->comment('(sub_total + total_shippings_charge) - discount_amount');
            $table->decimal('total_product_cost', 10, 2)->default(0.00)->comment('sum of order_details product_cost');
            $table->decimal('total_returned_amount', 10, 2)->default(0.00)->comment('sum of order_details returned_amount');
            $table->decimal('total_final_amount', 10, 2)->default(0.00)->comment('sum of order_details final_amount');
            $table->enum('status', ['Awaiting', 'Pending', 'In Progress', 'Ready to Ship', 'Shipped', 'Returned', 'Canceled', 'Delivered', 'Failed'])->default('Pending');
            $table->longText('notes')->nullable();
            $table->enum('refund_status', ['partial', 'full'])->nullable();
            $table->timestamp('delivered_at')->nullable();
            $table->timestamps();
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade')->onUpdate('cascade');
            $table->foreign('customer_id')->references('id')->on('customers')->onDelete('cascade')->onUpdate('cascade');
            $table->foreign('shop_id')->references('id')->on('shops')->onDelete('cascade')->onUpdate('cascade');
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
