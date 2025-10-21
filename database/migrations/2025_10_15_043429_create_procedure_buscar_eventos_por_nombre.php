<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        // Primero eliminar el procedimiento si existe (por si acaso)
        DB::unprepared('DROP PROCEDURE IF EXISTS buscar_eventos_por_nombre');

        // Crear el procedimiento almacenado CORREGIDO con imagen
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
                    -- Calcular relevancia (título tiene mayor peso)
                    CASE
                        WHEN e.titulo LIKE CONCAT("%", p_termino_busqueda, "%") THEN 3
                        WHEN e.descripcion LIKE CONCAT("%", p_termino_busqueda, "%") THEN 2
                        ELSE 1
                    END as relevancia,
                    -- Información de fechas (tomar solo la primera fecha para simplificar)
                    (SELECT fh.fecha_hora
                     FROM fechas_horas fh
                     WHERE fh.evento_id = e.id
                     ORDER BY fh.fecha_hora ASC
                     LIMIT 1) as fechas_evento,
                    -- Información de categorías
                    (SELECT GROUP_CONCAT(c.nombre SEPARATOR ", ")
                     FROM categorias c
                     INNER JOIN categoria_evento ce ON c.id = ce.categoria_id
                     WHERE ce.evento_id = e.id) as categorias,
                    -- Contador de comentarios
                    (SELECT COUNT(*) FROM comentarios co WHERE co.evento_id = e.id) as total_comentarios,
                    -- Información de imagen (AGREGADO)
                    (SELECT i.ruta FROM imagenes i WHERE i.evento_id = e.id LIMIT 1) as imagen_ruta
                FROM eventos e
                WHERE e.organizador_id = p_organizador_id
                AND (
                    e.titulo LIKE CONCAT("%", p_termino_busqueda, "%")
                    OR e.descripcion LIKE CONCAT("%", p_termino_busqueda, "%")
                )
                ORDER BY
                    relevancia DESC,
                    e.created_at DESC
                LIMIT p_limit
                OFFSET p_offset;
            END
        ');
    }

    public function down(): void
    {
        DB::unprepared('DROP PROCEDURE IF EXISTS buscar_eventos_por_nombre');
    }
};
