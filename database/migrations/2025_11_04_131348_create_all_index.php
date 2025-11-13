<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        /*
        |--------------------------------------------------------------------------
        | TABLA: eventos
        |--------------------------------------------------------------------------
        */
        Schema::table('eventos', function (Blueprint $table) {
            if (!Schema::hasColumn('eventos', 'titulo')) return;

            $table->index('titulo', 'idx_eventos_titulo');
            $table->index('organizador_id', 'idx_eventos_organizador');
        });

        /*
        |--------------------------------------------------------------------------
        | TABLA: categorias
        |--------------------------------------------------------------------------
        */
        Schema::table('categorias', function (Blueprint $table) {
            $table->index('nombre', 'idx_categorias_nombre');
        });

        /*
        |--------------------------------------------------------------------------
        | TABLA: organizadores
        |--------------------------------------------------------------------------
        */
        Schema::table('organizadores', function (Blueprint $table) {
            $table->index('id', 'idx_organizadores_id');
            $table->index('contacto', 'idx_organizadores_contacto');
        });

        /*
        |--------------------------------------------------------------------------
        | TABLA: imagenes
        |--------------------------------------------------------------------------
        */
        Schema::table('imagenes', function (Blueprint $table) {
            $table->index('evento_id', 'idx_imagenes_evento');
        });

        /*
        |--------------------------------------------------------------------------
        | TABLA: evento_categoria
        |--------------------------------------------------------------------------
        */
        Schema::table('categoria_evento', function (Blueprint $table) {
            $table->index('evento_id', 'idx_evcat_evento');
            $table->index('categoria_id', 'idx_evcat_categoria');
        });
    }

    public function down(): void
    {
        Schema::table('eventos', function (Blueprint $table) {
            $table->dropIndex('idx_eventos_titulo');
            $table->dropIndex('idx_eventos_organizador');
        });

        Schema::table('categorias', function (Blueprint $table) {
            $table->dropIndex('idx_categorias_nombre');
        });

        Schema::table('organizadores', function (Blueprint $table) {
            $table->dropIndex('idx_organizadores_id');
            $table->dropIndex('idx_organizadores_contacto');
        });

        Schema::table('imagenes', function (Blueprint $table) {
            $table->dropIndex('idx_imagenes_evento');
        });

        Schema::table('categoria_evento', function (Blueprint $table) {
            $table->dropIndex('idx_evcat_evento');
            $table->dropIndex('idx_evcat_categoria');
        });
    }
};
