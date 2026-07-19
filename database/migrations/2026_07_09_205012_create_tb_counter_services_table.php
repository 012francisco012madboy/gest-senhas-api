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
        Schema::create('tb_counter_services', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('service_id');
            $table->unsignedBigInteger('counter_id');
            $table->boolean('state')->default(true);
            $table->timestamps();

            $table->foreign('service_id')->references('id')->on('tb_services')->onDelete('cascade');
            $table->foreign('counter_id')->references('id')->on('tb_counters')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tb_counter_services');
    }
};
