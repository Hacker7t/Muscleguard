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
        Schema::create('subscriber', function (Blueprint $table) {
            $table->id('subscriber_id');
            $table->text('email');
            $table->integer('status')->default(0);
            $table->date('date');
            $table->time('time');
            $table->string('code',200)->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('subscriber');
    }
};
