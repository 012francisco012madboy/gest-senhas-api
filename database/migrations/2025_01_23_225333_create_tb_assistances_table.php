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
        Schema::create('tb_assistances', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('id_assistant');
            $table->unsignedBigInteger('id_ticket');
            $table->unsignedBigInteger('id_state')->default('1');
            $table->timestamps();

            $table->foreign('id_assistant')->references('id')->on('tb_user_assistants')->onDelete('cascade');
            $table->foreign('id_ticket')->references('id')->on('tb_tickets')->onDelete('cascade');
            $table->foreign('id_state')->references('id')->on('tb_states')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tb_assistances');
    }
};
