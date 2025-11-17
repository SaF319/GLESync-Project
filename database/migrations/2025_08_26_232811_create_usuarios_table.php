<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('usuarios', function (Blueprint $table) {
            $table->id();
            $table->string('nombre', 100);
            $table->string('email')->unique();
            $table->string('password');

            // Usuario root
            $table->boolean('es_root')->default(false);

            // Usuario baneado
            $table->boolean('baneado')->default(false);
            $table->string('motivo_baneo')->nullable();
            $table->timestamp('baneado_hasta')->nullable();

            // 2FA
            $table->boolean('is_2fa_enabled')->default(false);
            $table->string('google2fa_secret')->nullable();

            $table->rememberToken();
            $table->timestamps();

            // SoftDeletes
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('usuarios');
    }
};
