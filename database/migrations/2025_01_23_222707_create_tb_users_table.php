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
        Schema::create('tb_users', function (Blueprint $table) {
            $table->id();
            $table->string('name', 50);
            $table->string('email', 50)->unique();
            $table->string('password');
            $table->boolean('state')->default(true);
            $table->unsignedBigInteger('role_id');
            $table->timestamps();

            $table->foreign('role_id')->references('id')->on('tb_roles')->onDelete('cascade');
        });

        DB::table('tb_users')->insert([[
            'name' => 'Super User',
            'email' => 'su@system.in',
            'password' => bcrypt('12345678'),
            'role_id' => 1,
            'created_at' => now(),
            'updated_at' => now()
        ]]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tb_users');
    }
};
