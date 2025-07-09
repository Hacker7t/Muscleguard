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
        Schema::create('user', function (Blueprint $table) {
            $table->id('user_id');
            $table->text('name');
            $table->text('email');
            $table->text('phone');
            $table->integer('country_id')->default(0);
            $table->integer('state_id')->default(0);
            $table->integer('city_id')->default(0);
            $table->text('address')->nullable();
            $table->text('zip_code')->nullable();
            $table->text('password');
            $table->date('date');
            $table->time('time');
            $table->string('code',200)->nullable();
            $table->integer('status')->default(0);
            $table->integer('otp')->default(0);
            $table->integer('verified')->default(0);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('user');
    }
};
