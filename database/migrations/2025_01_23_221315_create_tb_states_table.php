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
            ['name' => 'Ativo', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Inativo', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Atendendo', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Retornado', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Ausente', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Transferido', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Concluído', 'created_at' => now(), 'updated_at' => now()],
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
