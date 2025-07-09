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
        Schema::create('product', function (Blueprint $table) {
            $table->id('product_id');
            $table->string('id',100);
            $table->integer('category_id')->default(0);
            $table->integer('sub_category_id')->default(0);
            $table->text('name');
            $table->text('size')->nullable();
            $table->text('description');
            $table->longText('specifications');
            $table->integer('discount')->default(0);
            $table->bigInteger('price');
            $table->text('colors')->nullable();
            $table->text('keywords')->nullable();
            $table->string('sku',20)->nullable();
            $table->text('url');
            $table->date('date');
            $table->time('time');
            $table->integer('account_id')->default(0);
            $table->string('card',100)->nullable();
            $table->longText('images')->nullable();
            $table->string('code',200)->nullable();
            $table->integer('status')->default(0);
            $table->integer('availability')->default(0);
            $table->integer('rating')->default(0);
            $table->integer('views')->default(0);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('product');
    }
};
