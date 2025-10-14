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
        Schema::create('datos_socioeconomicos', function (Blueprint $table) {
            $table->id();
            $table->string('situacion_familiar', 100);
            $table->decimal('ingresos', 8, 2);
            $table->integer('cantidad_personas');
            $table->string('necesidades', 150);
            $table->string('comunidad',150);
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
        Schema::dropIfExists('datos_socioeconomicos');
    }
};
