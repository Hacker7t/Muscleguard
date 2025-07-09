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
        Schema::create('cart', function (Blueprint $table) {
            $table->id('cart_id');
            $table->integer('product_id');
            $table->string('ip',100);
            $table->integer('qty');
            $table->text('size');
            $table->text('color');
            $table->bigInteger('price');
            $table->integer('discount')->default(0);
            $table->date('date');
            $table->time('time');
            $table->integer('order_id')->default(0);
            $table->string('code',200)->nullable();
            $table->integer('status')->default(0);
            $table->text('reason')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('cart');
    }
};
