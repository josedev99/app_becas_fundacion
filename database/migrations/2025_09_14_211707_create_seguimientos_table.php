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
        Schema::create('seguimientos', function (Blueprint $table) {
            $table->id();
            $table->string('fecha_reporte',25);
            $table->string('nota_adicional',250);
            $table->text('participacion_actividades');
            $table->text('observaciones_tutor');
            $table->string('estado_beca',25);
            $table->string('proridad',25);
            $table->string('fecha_proximo',15);
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
        Schema::dropIfExists('seguimientos');
    }
};
