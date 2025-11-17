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
        DB::unprepared('DROP TRIGGER IF EXISTS trg_log_evento_insert');
        DB::unprepared('DROP TRIGGER IF EXISTS trg_log_evento_update');
        
        DB::unprepared('
            CREATE TRIGGER trg_log_evento_insert
            AFTER INSERT ON eventos
            FOR EACH ROW
            BEGIN
                INSERT INTO logs_eventos (evento_id, organizador_id, accion)
                VALUES (NEW.id, NEW.organizador_id, "Creación");
            END
        ');

        DB::unprepared('
            CREATE TRIGGER trg_log_evento_update
            AFTER UPDATE ON eventos
            FOR EACH ROW
            BEGIN
                INSERT INTO logs_eventos (evento_id, organizador_id, accion)
                VALUES (NEW.id, NEW.organizador_id, "Actualización");
            END
        ');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::unprepared('DROP TRIGGER IF EXISTS trg_log_evento_insert');
        DB::unprepared('DROP TRIGGER IF EXISTS trg_log_evento_update');
    }
};
