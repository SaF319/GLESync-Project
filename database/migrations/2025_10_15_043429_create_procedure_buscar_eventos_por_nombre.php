<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        // 🚫 SQLite (PHPUnit) no soporta procedimientos
        if (app()->environment('testing')) {
            return;
        }

        DB::unprepared('DROP PROCEDURE IF EXISTS buscar_eventos_por_nombre');

        DB::unprepared('
            CREATE PROCEDURE buscar_eventos_por_nombre(
                IN p_organizador_id BIGINT,
                IN p_termino_busqueda VARCHAR(255),
                IN p_limit INT,
                IN p_offset INT
            )
            BEGIN
                SELECT
                    e.id,
                    e.titulo,
                    e.descripcion,
                    e.latitud,
                    e.longitud,
                    e.organizador_id,
                    e.created_at,
                    e.updated_at,
                    CASE
                        WHEN LOWER(e.titulo) LIKE CONCAT("%", LOWER(p_termino_busqueda), "%") THEN 3
                        WHEN LOWER(e.descripcion) LIKE CONCAT("%", LOWER(p_termino_busqueda), "%") THEN 2
                        ELSE 1
                    END AS relevancia,
                    (SELECT fh.fecha_hora
                     FROM fechas_horas fh
                     WHERE fh.evento_id = e.id
                     ORDER BY fh.fecha_hora ASC
                     LIMIT 1) AS fechas_evento,
                    (SELECT GROUP_CONCAT(c.nombre SEPARATOR ", ")
                     FROM categorias c
                     INNER JOIN categoria_evento ce ON c.id = ce.categoria_id
                     WHERE ce.evento_id = e.id) AS categorias,
                    (SELECT COUNT(*) FROM comentarios co WHERE co.evento_id = e.id) AS total_comentarios,
                    (SELECT i.ruta FROM imagenes i WHERE i.evento_id = e.id LIMIT 1) AS imagen_ruta
                FROM eventos e
                WHERE e.organizador_id = p_organizador_id
                AND (
                    LOWER(e.titulo) LIKE CONCAT("%", LOWER(p_termino_busqueda), "%")
                    OR LOWER(e.descripcion) LIKE CONCAT("%", LOWER(p_termino_busqueda), "%")
                )
                ORDER BY relevancia DESC, e.created_at DESC
                LIMIT p_limit
                OFFSET p_offset;
            END
        ');
    }

    public function down(): void
    {
        if (app()->environment('testing')) {
            return;
        }

        DB::unprepared('DROP PROCEDURE IF EXISTS buscar_eventos_por_nombre');
    }
};
