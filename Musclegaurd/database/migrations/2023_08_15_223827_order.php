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
        Schema::create('order', function (Blueprint $table) {
            $table->id('order_id');
            $table->text('name');
            $table->text('email');
            $table->text('phone');
            $table->integer('country_id');
            $table->integer('state_id');
            $table->integer('city_id');
            $table->text('address');
            $table->text('zip_code')->nullable();
            $table->integer('coupon_id')->default(0);
            $table->integer('charges')->default(0);
            $table->date('date');
            $table->time('time');
            $table->integer('user_id')->default(0);
            $table->string('code',200)->nullable();
            $table->integer('status')->default(0);
            $table->integer('paid')->default(0);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('order');
    }
};
