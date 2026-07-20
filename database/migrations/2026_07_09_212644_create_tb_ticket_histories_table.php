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
        Schema::create('tb_ticket_histories', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('ticket_id');
            $table->unsignedBigInteger('counter_id');
            $table->unsignedBigInteger('daily_id');
            $table->unsignedBigInteger('state_id')->default('1');
            $table->timestamps();

            $table->foreign('ticket_id')->references('id')->on('tb_tickets')->onDelete('cascade');
            $table->foreign('counter_id')->references('id')->on('tb_counters')->onDelete('cascade');
            $table->foreign('daily_id')->references('id')->on('tb_dailies')->onDelete('cascade');
            $table->foreign('state_id')->references('id')->on('tb_states')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tb_ticket_histories');
    }
};
