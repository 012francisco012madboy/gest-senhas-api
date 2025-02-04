<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('tb_states', function (Blueprint $table) {
            $table->id();
            $table->string('name', 25);
            $table->timestamps();
        });

        DB::table('tb_states')->insert([
            'name' => 'Activo',
            'created_at' => now(),
            'updated_at' => now()
        ]);

        DB::table('tb_states')->insert([
            'name' => 'Desativado',
            'created_at' => now(),
            'updated_at' => now()
        ]);

        DB::table('tb_states')->insert([
            'name' => 'Em atendimento',
            'created_at' => now(),
            'updated_at' => now()
        ]);

        DB::table('tb_states')->insert([
            'name' => 'Voltou na fila',
            'created_at' => now(),
            'updated_at' => now()
        ]);

        DB::table('tb_states')->insert([
            'name' => 'Não compareceu',
            'created_at' => now(),
            'updated_at' => now()
        ]);

        DB::table('tb_states')->insert([
            'name' => 'Finalizado',
            'created_at' => now(),
            'updated_at' => now()
        ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tb_states');
    }
};
