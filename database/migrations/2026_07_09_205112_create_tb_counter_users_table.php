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
        Schema::create('tb_counter_users', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->unsignedBigInteger('counter_id');
            $table->unsignedBigInteger('daily_id');
            $table->boolean('state')->default(true);
            $table->timestamps();

            $table->foreign('user_id')->references('id')->on('tb_users')->onDelete('cascade');
            $table->foreign('counter_id')->references('id')->on('tb_counters')->onDelete('cascade');
            $table->foreign('daily_id')->references('id')->on('tb_dailies')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tb_counter_users');
    }
};
