<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        DB::unprepared('DROP PROCEDURE IF EXISTS crear_evento');

        DB::unprepared('
            CREATE PROCEDURE crear_evento(
                IN p_titulo VARCHAR(200),
                IN p_descripcion TEXT,
                IN p_fechaHora DATETIME,
                IN p_latitud DECIMAL(10, 7),
                IN p_longitud DECIMAL(10, 7),
                IN p_organizador_id BIGINT  -- ✅ CORREGIDO
            )
            BEGIN
                INSERT INTO eventos (titulo, latitud, longitud, descripcion, organizador_id, created_at, updated_at)
                VALUES (p_titulo, p_latitud, p_longitud, p_descripcion, p_organizador_id, NOW(), NOW());

                SET @last_evento_id = LAST_INSERT_ID();

                INSERT INTO fechas_horas (evento_id, fecha_hora, created_at, updated_at)
                VALUES (@last_evento_id, p_fechaHora, NOW(), NOW());
            END
        ');
    }

    public function down(): void
    {
        DB::unprepared('DROP PROCEDURE IF EXISTS crear_evento');
    }
};
