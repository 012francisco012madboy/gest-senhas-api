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
        Schema::create('tb_sessions', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('id_state')->default('1');
            $table->unsignedBigInteger('id_company');
            $table->timestamps();

            $table->foreign('id_state')->references('id')->on('tb_states')->onDelete('cascade');
            $table->foreign('id_company')->references('id')->on('tb_companies')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tb_sessions');
    }
};
