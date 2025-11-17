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
        DB::unprepared('DROP TRIGGER IF EXISTS trg_log_usuario_insert');
        DB::unprepared('DROP TRIGGER IF EXISTS trg_log_usuario_update');
        
        DB::unprepared('
            CREATE TRIGGER trg_log_usuario_insert
            AFTER INSERT ON usuarios
            FOR EACH ROW
            BEGIN
                INSERT INTO logs_usuarios (usuario_id, accion)
                VALUES (NEW.id, "Creación de Usuario");
            END
        ');

        DB::unprepared('
            CREATE TRIGGER trg_log_usuario_update
            AFTER UPDATE ON usuarios
            FOR EACH ROW
            BEGIN
                INSERT INTO logs_usuarios (usuario_id, accion)
                VALUES (NEW.id, "Actualización de Usuario");
            END
        ');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::unprepared('DROP TRIGGER IF EXISTS trg_log_usuario_insert');
        DB::unprepared('DROP TRIGGER IF EXISTS trg_log_usuario_update');
    }
};
