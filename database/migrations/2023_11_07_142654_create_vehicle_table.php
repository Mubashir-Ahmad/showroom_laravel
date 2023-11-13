<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('vehicle', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->unsignedBigInteger('buyer_id')->nullable();
            $table->unsignedBigInteger('seller_id')->nullable();
            $table->unsignedBigInteger('brand_id')->nullable();
            $table->string('brandname');
            $table->string('color');
            $table->string('description');
            $table->integer('model');
            $table->string('varient');
            $table->string('document')->nullable();
            $table->string('chasie_number');
            $table->string('engine_number');
            $table->integer('purchase_price')->nullable();
            $table->integer('expense')->nullable();
            $table->integer('sale_price')->nullable();
            $table->string('image_path')->nullable();
            $table->foreign('user_id')->references('id')->on('users');
            $table->foreign('seller_id')->references('id')->on('customer')->nullable();
            $table->foreign('buyer_id')->references('id')->on('customer')->nullable();
            $table->timestamps();
        });
    }
    
    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('vehicle');
    }
};
