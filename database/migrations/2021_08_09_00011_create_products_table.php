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
            $table->id();
            $table->string('name');
            $table->string('code', 200)->unique();
            $table->text('description');
            $table->decimal('product_cost', 10, 2);
            $table->decimal('selling_price', 10, 2);
            $table->unsignedBigInteger('user_id');
            $table->unsignedBigInteger('category_id');
            $table->unsignedBigInteger('origin')->nullable();
            $table->unsignedBigInteger('brand_id')->nullable();
            $table->unsignedBigInteger('menufacture_id')->nullable();
            $table->string('feature_image')->nullable();
            $table->string('warenty_duration')->nullable()->comment('(in month) from sales date');
            $table->timestamps();
            $table->softDeletes();
            $table->foreign('category_id')->references('id')->on('categories');
            $table->foreign('origin')->references('id')->on('countries');
            $table->foreign('brand_id')->references('id')->on('brands');
            $table->foreign('menufacture_id')->references('id')->on('menufactures');
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
