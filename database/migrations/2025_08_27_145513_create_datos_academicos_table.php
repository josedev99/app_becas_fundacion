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
            $table->unsignedBigInteger('becado_id');

            $table->foreign('becado_id')->references('id')->on('becados');
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
