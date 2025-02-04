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
        Schema::create('tb_types', function (Blueprint $table) {
            $table->id();
            $table->string('name', 25);
            $table->timestamps();
        });

        DB::table('tb_types')->insert([
            'name' => 'Administrador',
            'created_at' => now(),
            'updated_at' => now()
        ]);

        DB::table('tb_types')->insert([
            'name' => 'Funcionário',
            'created_at' => now(),
            'updated_at' => now()
        ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tb_types');
    }
};
