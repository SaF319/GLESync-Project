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
        Schema::create('fechahora', function (Blueprint $table) {
            $table->unsignedBigInteger('id_evento');
            $table->dateTimeTz('fechaHora')->notNull();
            $table->primary(['id_evento', 'fechaHora']);
            $table->foreign('id_evento')->references('id_evento')->on('evento');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('fechahora');
    }
};
