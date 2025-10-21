<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log; // ✅ Importar Facade Log

class Evento extends Model
{
    use HasFactory;

    protected $fillable = [
        'titulo',
        'latitud',
        'longitud',
        'descripcion',
        'organizador_id'
    ];

    // Relación con organizador
    public function organizador()
    {
        return $this->belongsTo(Organizador::class, 'organizador_id');
    }

    // Relación con fechas y horas
    public function fechasHoras()
    {
        return $this->hasMany(FechaHora::class, 'evento_id');
    }

    // Relación con comentarios
    public function comentarios()
    {
        return $this->hasMany(Comentario::class, 'evento_id');
    }

    // Relación con interacciones
    public function interacciones()
    {
        return $this->hasMany(Interaccion::class, 'evento_id');
    }

    // Relación con categorías (muchos a muchos)
    public function categorias()
    {
        return $this->belongsToMany(Categoria::class, 'categoria_evento', 'evento_id', 'categoria_id');
    }

    // Relación con creaciones
    public function creaciones()
    {
        return $this->hasMany(Creacion::class, 'evento_id');
    }

    // Relación con imágenes (uno a uno)
    public function imagen()
    {
        return $this->hasOne(Imagen::class, 'evento_id');
    }

    /**
     * Buscar eventos por nombre usando procedimiento almacenado
     */
    public static function buscarPorNombre($organizadorId, $searchTerm, $page = 1, $perPage = 10)
{
    try {
        $offset = ($page - 1) * $perPage;

        Log::info("📋 Ejecutando procedimiento almacenado", [
            'organizador_id' => $organizadorId,
            'search_term' => $searchTerm,
            'per_page' => $perPage,
            'offset' => $offset
        ]);

        // ✅ LLAMAR AL PROCEDIMIENTO ALMACENADO
        $results = DB::select(
            'CALL buscar_eventos_por_nombre(?, ?, ?, ?)',
            [$organizadorId, $searchTerm, $perPage, $offset]
        );

        Log::info("📦 Resultados del procedimiento:", ['count' => count($results)]);

        // DEBUG: Mostrar estructura del primer resultado
        if (!empty($results)) {
            $primerResultado = (array) $results[0];
            Log::info("🔍 ESTRUCTURA del primer resultado:", array_keys($primerResultado));
            Log::info("🔍 VALORES del primer resultado:", $primerResultado);
        }

        return collect($results);

    } catch (\Exception $e) {
        Log::error('❌ Error en procedimiento buscar_eventos_por_nombre: ' . $e->getMessage());

        // Fallback a búsqueda Eloquent
        return self::where('organizador_id', $organizadorId)
            ->where(function($query) use ($searchTerm) {
                $query->where('titulo', 'LIKE', "%{$searchTerm}%")
                        ->orWhere('descripcion', 'LIKE', "%{$searchTerm}%");
            })
            ->with(['categorias', 'fechasHoras', 'imagen'])
            ->skip($offset)
            ->take($perPage)
            ->get();
    }
}
    /**
     * Contar total de resultados de búsqueda
     */
    public static function contarBusqueda($organizadorId, $searchTerm)
    {
        return self::where('organizador_id', $organizadorId)
            ->where(function($query) use ($searchTerm) {
                $query->where('titulo', 'LIKE', "%{$searchTerm}%")
                        ->orWhere('descripcion', 'LIKE', "%{$searchTerm}%");
            })
            ->count();
    }

    /**
     * Obtener eventos con información relacionada
     */
    public function cargarRelacionesCompletas()
    {
        return $this->load([
            'fechasHoras',
            'categorias',
            'comentarios',
            'imagen',
            'organizador'
        ]);
    }

    /**
     * Scope para búsqueda rápida
     */
    public function scopeBuscar($query, $searchTerm)
    {
        return $query->where(function($q) use ($searchTerm) {
            $q->where('titulo', 'LIKE', "%{$searchTerm}%")
                ->orWhere('descripcion', 'LIKE', "%{$searchTerm}%");
        });
    }

    /**
     * Scope para eventos de un organizador específico
     */
    public function scopeDelOrganizador($query, $organizadorId)
    {
        return $query->where('organizador_id', $organizadorId);
    }
}
