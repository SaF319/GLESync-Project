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
        Schema::create('crea', function (Blueprint $table) {
            $table->unsignedBigInteger('id_evento');
            $table->string('email');
            $table->primary(['email', 'id_evento']);
            $table->foreign('id_evento')->references('id_evento')->on('evento');
            $table->foreign('email')->references('email')->on('organizador');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('crea');
    }
};
