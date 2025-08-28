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
        Schema::create('comentarios', function (Blueprint $table) {
            $table->unsignedBigInteger('id_evento');
            $table->string('email');
            $table->string('comentario', 100)->notNull();
            $table->primary(['id_evento', 'email', 'comentario']);
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
        Schema::dropIfExists('comentarios');
    }
};
