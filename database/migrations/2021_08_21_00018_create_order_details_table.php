<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOrderDetailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('order_details', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('order_id');
            $table->unsignedBigInteger('product_id');
            $table->unsignedBigInteger('shop_id');
            $table->unsignedBigInteger('quantity_unit_id')->nullable();
            $table->string('product_name');
            $table->decimal('product_original_unit_price', 10, 2);
            $table->decimal('product_unit_price', 10, 2);
            $table->unsignedInteger('quantity_unit_value')->nullable();
            $table->string('product_quantity')->default(1);
            $table->string('returned_quantity')->default(0)->comment('if admin return');
            $table->string('final_quantity');
            $table->decimal('sub_total', 10, 2)->default(0)->comment('product_unit_price * quantity');
            $table->decimal('total', 10, 2)->default(0)->comment('(sub_total + additional_delivery_charge) - discount_amount');
            $table->decimal('returned_amount', 10, 2)->default(0)->comment('if admin return');
            $table->decimal('final_amount', 10, 2)->default(0)->comment('total - returned_amount');
            $table->decimal('product_cost', 10, 2)->default(0)->comment('stock out product cost');
            $table->string('warenty_duration')->nullable();
            $table->timestamp('rejected_at')->nullable();
            $table->enum('status', ['Pending', 'In Progress', 'Ready to Ship', 'Shipped', 'Canceled & Refund', 'Delivered'])->default('Pending');
            $table->foreign('order_id')->on('orders')->references('id')->onDelete('cascade')->onUpdate('cascade');
            $table->foreign('shop_id')->on('shops')->references('id')->onDelete('cascade')->onUpdate('cascade');
            $table->foreign('product_id')->on('products')->references('id')->onDelete('cascade')->onUpdate('cascade');
            $table->foreign('quantity_unit_id')->on('units')->references('id')->onDelete('cascade')->onUpdate('cascade');
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
        Schema::dropIfExists('order_details');
    }
}
