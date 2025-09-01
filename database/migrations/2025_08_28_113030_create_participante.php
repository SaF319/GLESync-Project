<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('participantes', function (Blueprint $table) {
            $table->string('email');           // columna email
            $table->timestamps();

            $table->primary('email');          // declaramos PK
            $table->foreign('email')           // declaramos FK
                    ->references('email')
                    ->on('usuarios')
                    ->onDelete('cascade');      // opcional: borrar participante si se borra usuario
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('participantes');
    }
};
