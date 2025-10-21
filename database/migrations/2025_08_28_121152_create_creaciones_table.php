<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('creaciones', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('evento_id');
            $table->unsignedBigInteger('organizador_id');
            $table->timestamps();

            $table->foreign('evento_id')
                    ->references('id')
                    ->on('eventos')
                    ->onDelete('cascade');

            $table->foreign('organizador_id')
                    ->references('id')
                    ->on('organizadores')
                    ->onDelete('cascade');

            $table->unique(['evento_id', 'organizador_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('creaciones');
    }
};
