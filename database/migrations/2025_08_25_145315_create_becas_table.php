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
        Schema::create('becas', function (Blueprint $table) {
            $table->id();
            $table->string('fInicio',15);
            $table->string('fFin',15);
            $table->string('nombre',200);
            $table->string('tipo_beca',25);
            $table->string('financiamiento',50);
            $table->string('plazo_monto',50);
            $table->string('forma_entrega',25);
            $table->string('compromisos',150);
            $table->string('responsable',150);
            $table->string('estado',10);

            $table->unsignedBigInteger('user_id');

            $table->foreign('user_id')->references('id')->on('users');
            $table->index('user_id');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('becas');
    }
};
