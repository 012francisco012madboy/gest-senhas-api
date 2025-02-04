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
        Schema::create('tb_tickets', function (Blueprint $table) {
            $table->id();
            $table->string('ref');
            $table->unsignedBigInteger('id_service');
            $table->unsignedBigInteger('id_session');
            $table->unsignedBigInteger('id_state')->default('1');
            $table->timestamps();

            $table->foreign('id_service')->references('id')->on('tb_services')->onDelete('cascade');
            $table->foreign('id_session')->references('id')->on('tb_sessions')->onDelete('cascade');
            $table->foreign('id_state')->references('id')->on('tb_states')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tb_tickets');
    }
};
