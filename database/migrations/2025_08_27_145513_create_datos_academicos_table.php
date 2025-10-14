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
        Schema::create('datos_academicos', function (Blueprint $table) {
            $table->id();
            $table->string('nivel_educativo', 100);
            $table->string('institucion', 150);
            $table->string('carrera_grado', 50);
            $table->decimal('promedio', 8,2);
            $table->string('estado_academico', 50);
            $table->string('fInicio',15);
            $table->string('fFin',15);
            $table->unsignedBigInteger('estudiante_id');
            $table->unsignedBigInteger('user_id');

            $table->foreign('estudiante_id')->references('id')->on('estudiantes');
            $table->foreign('user_id')->references('id')->on('users');
            $table->index('estudiante_id');
            $table->index('user_id');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('datos_academicos');
    }
};
