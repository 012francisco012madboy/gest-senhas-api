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
            $table->string('reference');
            $table->unsignedBigInteger('service_id');
            $table->unsignedBigInteger('daily_id');
            $table->boolean('state')->default(true);
            $table->boolean('priority')->default(false);
            $table->timestamps();

            $table->foreign('service_id')->references('id')->on('tb_services')->onDelete('cascade');
            $table->foreign('daily_id')->references('id')->on('tb_dailies')->onDelete('cascade');
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
