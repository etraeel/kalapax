<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePricesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('prices', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('product_id');
            $table->foreign('product_id')->references('id')->on('products')->onDelete('cascade');
            $table->integer('attribute')->default(0);
            $table->integer('value')->default(0);
            $table->integer('price')->default(0);
            $table->integer('off_price')->default(0);
            $table->integer('inventory')->default(0);
            $table->integer('status')->default(0);
            $table->timestamps();

        });

        Schema::table('products', function (Blueprint $table) {
            $table->dropColumn(['price' ,'off_price' ,'amazon_price' ,'amazon_link' , 'inventory' ]);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('prices');
        Schema::table('products', function (Blueprint $table) {
            $table->integer('price')->default(0);
            $table->integer('off_price')->default(0);
            $table->integer('amazon_price')->default(0);
            $table->string('amazon_link')->nullable();
            $table->integer('inventory')->default(0);
        });
    }
}
