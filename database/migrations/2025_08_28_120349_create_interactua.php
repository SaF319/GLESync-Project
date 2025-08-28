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
        Schema::create('interactua', function (Blueprint $table) {
            $table->unsignedBigInteger('id_evento');
            $table->string('email');
            $table->integer('comentarios')->default(0);
            $table->integer('puntaje');
            $table->boolean('visitado')->default(false);
            $table->primary(['id_evento', 'email']);
            $table->foreign('id_evento')->references('id_evento')->on('evento');
            $table->foreign('email')->references('email')->on('usuarios');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('interactua');
    }
};
