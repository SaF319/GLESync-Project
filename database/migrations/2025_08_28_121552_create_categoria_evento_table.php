<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('categoria_evento', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('categoria_id');
            $table->unsignedBigInteger('evento_id');
            $table->timestamps();

            $table->foreign('categoria_id')
                    ->references('id')
                    ->on('categorias')
                    ->onDelete('cascade');

            $table->foreign('evento_id')
                    ->references('id')
                    ->on('eventos')
                    ->onDelete('cascade');

            $table->unique(['categoria_id', 'evento_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('categoria_evento');
    }
};
