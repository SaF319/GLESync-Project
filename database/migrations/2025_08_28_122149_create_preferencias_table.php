<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('preferencias', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('usuario_id');
            $table->unsignedBigInteger('categoria_id');
            $table->timestamps();

            $table->foreign('usuario_id')
                    ->references('id')
                    ->on('usuarios')
                    ->onDelete('cascade');

            $table->foreign('categoria_id')
                    ->references('id')
                    ->on('categorias')
                    ->onDelete('cascade');

            $table->unique(['usuario_id', 'categoria_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('preferencias');
    }
};
