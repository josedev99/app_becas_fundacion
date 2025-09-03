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
        Schema::create('estudiantes', function (Blueprint $table) {
            $table->id();
            $table->string('nombre_completo',150);
            $table->string('documento',25)->unique();
            $table->string('fecha_nacimiento',15);
            $table->string('direccion',200);
            $table->string('telefono',15)->nullable();
            $table->string('email',100)->nullable();
            $table->string('telefono_emergencia',15)->nullable();

            $table->unsignedBigInteger('beca_id');
            $table->unsignedBigInteger('usuario_id');

            $table->foreign('usuario_id')->references('id')->on('users');
            $table->foreign('beca_id')->references('id')->on('becas');

            $table->index('usuario_id');
            $table->index('beca_id');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('becados');
    }
};
