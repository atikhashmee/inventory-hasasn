<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProductsTable extends Migration
{

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('products', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name');
            $table->text('description');
            $table->decimal('old_price', 10, 2)->nullable();
            $table->decimal('price', 10, 2);
            $table->decimal('selling_price', 10, 2)->nullable();
            $table->integer('quantity');
            $table->string('slug')->unique();
            $table->string('sku')->unique();
            $table->integer('category_id')->unsigned();
            $table->integer('brand_id')->unsigned()->nullable();
            $table->integer('supplier_id')->unsigned()->nullable();
            $table->integer('menufacture_id')->unsigned()->nullable();
            $table->integer('warehouse_id')->unsigned();
            $table->string('feature_image');
            $table->timestamps();
            $table->softDeletes();
            // $table->foreign('category_id')->references('id')->on('categories');
            // $table->foreign('brand_id')->references('id')->on('brands');
            // $table->foreign('supplier_id')->references('id')->on('suppliers');
            // $table->foreign('menufacture_id')->references('id')->on('menufactures');
            // $table->foreign('warehouse_id')->references('id')->on('ware_houses');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('products');
    }
}
