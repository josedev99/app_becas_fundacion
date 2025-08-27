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
        Schema::create('modulo_accions', function (Blueprint $table) {
            $table->id();
            $table->string('clave',40);
            $table->string('nombre',75);
            $table->string('descripcion',150);
            $table->string('estado',12);
            $table->unsignedBigInteger('modulo_id');
            $table->unsignedBigInteger('usuario_id');

            $table->foreign('modulo_id')->references('id')->on('modulos');
            $table->foreign('usuario_id')->references('id')->on('users');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('modulo_accions');
    }
};
