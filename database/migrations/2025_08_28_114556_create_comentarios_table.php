<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('comentarios', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('evento_id');
            $table->unsignedBigInteger('usuario_id');
            $table->string('comentario', 500); // Aumenté el límite a 500 caracteres
            $table->timestamps();

            $table->foreign('evento_id')
                    ->references('id')
                    ->on('eventos')
                    ->onDelete('cascade');

            $table->foreign('usuario_id')
                    ->references('id')
                    ->on('usuarios')
                    ->onDelete('cascade');

            $table->unique(['evento_id', 'usuario_id']); // Unique en lugar de primary compuesta
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('comentarios');
    }
};
