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
        Schema::create('city', function (Blueprint $table) {
            $table->id('city_id');
            $table->integer('country_id');
            $table->integer('state_id');
            $table->text('name');
            $table->bigInteger('charges')->default(0);
            $table->date('date');
            $table->time('time');
            $table->integer('account_id')->default(0);
            $table->string('code',200)->nullable();
            $table->integer('status')->default(0);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('city');
    }
};
