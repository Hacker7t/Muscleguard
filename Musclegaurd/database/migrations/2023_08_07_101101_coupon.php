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
        Schema::create('coupon', function (Blueprint $table) {
            $table->id('coupon_id');
            $table->text('coupon');
            $table->integer('discount');
            $table->integer('account_id');
            $table->date('date');
            $table->time('time');
            $table->string('card',100)->nullable();
            $table->string('code',200)->nullable();
            $table->integer('status')->default(0);
            $table->integer('availability')->default(0);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('coupon');
    }
};
