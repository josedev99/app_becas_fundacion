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
        Schema::create('permiso_usuarios', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('modulo_accion_id');
            $table->unsignedBigInteger('usuario_id');
            $table->unsignedBigInteger('asignador_id');

            $table->foreign('modulo_accion_id')->references('id')->on('modulo_accions');
            $table->foreign('usuario_id')->references('id')->on('users');
            $table->foreign('asignador_id')->references('id')->on('users');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('permiso_usuarios');
    }
};
