<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAddressesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('addresses', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->foreign('user_id')->on('users')->references('id')->onDelete('cascade');
            $table->unsignedInteger('province_id');
            $table->foreign('province_id')->on('provinces')->references('id')->onDelete('cascade');
            $table->unsignedInteger('city_id');
            $table->foreign('city_id')->on('cities')->references('id')->onDelete('cascade');
            $table->string('description');
            $table->string('postal_code')->nullable();
            $table->string('home_number')->nullable();
            $table->boolean('default_address')->default(true);
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
        Schema::dropIfExists('addresses');
    }
}
