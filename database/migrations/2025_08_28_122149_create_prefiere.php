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
        Schema::create('prefiere', function (Blueprint $table) {
            $table->string('email');
            $table->unsignedBigInteger('id_categoria');
            $table->primary(['email', 'id_categoria']);
            $table->timestamps();
            $table->foreign('email')->references('email')->on('usuarios');
            $table->foreign('id_categoria')->references('id_categoria')->on('categoria');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('prefiere');
    }
};
