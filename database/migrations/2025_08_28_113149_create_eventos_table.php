<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;



return new class extends Migration
{
    public function up(): void
    {
        Schema::create('eventos', function (Blueprint $table) {
            $table->id(); // PK auto-incremental estándar
            $table->string('titulo', 200);
            $table->decimal('latitud', 10, 7)->nullable(); // Alternativa a geometry
            $table->decimal('longitud', 10, 7)->nullable(); // Alternativa a geometry
            $table->mediumText('descripcion');
            $table->unsignedBigInteger('organizador_id'); // Relación con organizadores
            $table->timestamps();

            $table->foreign('organizador_id')
                    ->references('id')
                    ->on('organizadores')
                    ->onDelete('cascade');


        });
    }

    public function down(): void
    {
        Schema::dropIfExists('eventos');
    }
};
