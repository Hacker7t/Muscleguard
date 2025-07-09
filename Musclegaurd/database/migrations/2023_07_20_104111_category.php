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
        Schema::create('category', function (Blueprint $table) {
            $table->id('category_id');
            $table->integer('parent_id')->default(0);
            $table->text('name');
            $table->text('description')->nullable();
            $table->text('url');
            $table->string('id',100);
            $table->integer('account_id');
            $table->date('date');
            $table->time('time');
            $table->string('card',100)->nullable();
            $table->string('small',100)->nullable();
            $table->string('code',200)->nullable();
            $table->integer('status')->default(0);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('category');
    }
};
