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
        Schema::create('categoria_evento', function (Blueprint $table) {
            $table->unsignedBigInteger('id_categoria');
            $table->unsignedBigInteger('id_evento');
            $table->primary(['id_categoria', 'id_evento']);
            $table->foreign('id_evento')->references('id_evento')->on('evento');
            $table->foreign('id_categoria')->references('id_categoria')->on('categoria');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('categoria_evento');
    }
};
