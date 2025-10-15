<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{

    public function up(): void
    {
        Schema::create('imagenes', function (Blueprint $table) {
            $table->id();$table->unsignedBigInteger('evento_id');
            $table->string('nombre', 200);$table->string('ruta', 500);
            $table->timestamps();

            $table->foreign('evento_id')
                    ->references('id')
                    ->on('eventos')
                    ->onDelete('cascade');

            $table->unique('evento_id');
        });
    }


    public function down(): void
    {
        Schema::dropIfExists('imagenes');
    }
};
