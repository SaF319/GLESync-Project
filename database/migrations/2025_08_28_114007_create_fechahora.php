<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('fechas_horas', function (Blueprint $table) {
            $table->unsignedBigInteger('id_evento');
            $table->dateTimeTz('fechaHora');  // obligatorio por defecto
            $table->primary(['id_evento', 'fechaHora']); // PK compuesta
            $table->foreign('id_evento')
                  ->references('id_evento')
                  ->on('eventos')
                  ->onDelete('cascade');  // opcional
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('fechas_horas');
    }
};
