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
        Schema::create('val_form_dinamicos', function (Blueprint $table) {
            $table->id();
            $table->string('nombre',200);
            $table->string('modulo_form',50);
            $table->string('identicador',50);
            $table->unsignedBigInteger('usuario_id');
            
            $table->foreign('usuario_id')->references('id')->on('users');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('val_form_dinamicos');
    }
};
