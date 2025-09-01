<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('eventos', function (Blueprint $table) {
            $table->bigIncrements('id_evento'); // PK auto-incremental
            $table->string('titulo', 200);      // obligatorio por defecto
            $table->geometry('ubicacion');      // asegúrate de que MySQL lo soporte
            $table->mediumText('descripcion');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('eventos');
    }
};
