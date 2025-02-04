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
        Schema::create('tb_user_assistants', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('id_user');
            $table->unsignedBigInteger('id_front_desk');
            $table->unsignedBigInteger('id_state')->default('1');
            $table->timestamps();

            $table->foreign('id_user')->references('id')->on('tb_users')->onDelete('cascade');
            $table->foreign('id_front_desk')->references('id')->on('tb_front_desks')->onDelete('cascade');
            $table->foreign('id_state')->references('id')->on('tb_states')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tb_user_assistants');
    }
};
