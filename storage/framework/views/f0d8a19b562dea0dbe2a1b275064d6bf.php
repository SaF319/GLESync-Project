<?php $__env->startSection('title', 'Resultados de Búsqueda'); ?>

<?php $__env->startSection('content'); ?>
<div class="container py-4">
    <!-- Barra de búsqueda -->
    <div class="row mb-4">
        <div class="col-md-8">
            <form action="<?php echo e(route('home.buscar')); ?>" method="GET">
                <div class="input-group">
                    <input type="text"
                           name="q"
                           class="form-control form-control-lg"
                           placeholder="Buscar eventos..."
                           value="<?php echo e($terminoBusqueda); ?>"
                           required>
                    <button class="btn btn-primary btn-lg" type="submit">
                        <i class="fas fa-search"></i> Buscar
                    </button>
                </div>
            </form>
        </div>
        <div class="col-md-4 text-end">
            <a href="<?php echo e(route('home')); ?>" class="btn btn-outline-secondary">
                <i class="fas fa-arrow-left"></i> Volver al inicio
            </a>
        </div>
    </div>

    <!-- Información de búsqueda -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="alert alert-info">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <i class="fas fa-info-circle"></i>
                        <strong>Resultados para:</strong> "<?php echo e($terminoBusqueda); ?>"
                        <?php if($usuarioAutenticado): ?>
                            <span class="badge bg-primary ms-2">Tus eventos</span>
                        <?php else: ?>
                            <span class="badge bg-secondary ms-2">Todos los eventos</span>
                        <?php endif; ?>
                    </div>
                    <span class="badge bg-success fs-6"><?php echo e($totalResultados); ?> eventos encontrados</span>
                </div>
            </div>
        </div>
    </div>

    <!-- Resultados -->
    <?php if($eventos->isEmpty()): ?>
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-body text-center py-5">
                        <div class="text-muted mb-3">
                            <i class="fas fa-search fa-3x"></i>
                        </div>
                        <h4>No se encontraron eventos</h4>
                        <p class="text-muted mb-4">No hay eventos que coincidan con "<?php echo e($terminoBusqueda); ?>"</p>
                        <a href="<?php echo e(route('home')); ?>" class="btn btn-primary">
                            <i class="fas fa-home"></i> Volver al inicio
                        </a>
                        <a href="<?php echo e(route('home.buscar')); ?>" class="btn btn-outline-primary">
                            <i class="fas fa-redo"></i> Nueva búsqueda
                        </a>
                    </div>
                </div>
            </div>
        </div>
    <?php else: ?>
        <div class="row">
            <?php $__currentLoopData = $eventos; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $evento): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <div class="col-md-4 mb-4">
                    <div class="card h-100 shadow-sm">
                        <?php if(isset($evento->imagen_url) || (is_array($evento) && isset($evento['imagen_url']))): ?>
                            <img src="<?php echo e(is_array($evento) ? $evento['imagen_url'] : $evento->imagen_url); ?>"
                                 class="card-img-top"
                                 alt="<?php echo e(is_array($evento) ? $evento['titulo'] : $evento->titulo); ?>"
                                 style="height: 200px; object-fit: cover;">
                        <?php else: ?>
                            <div class="card-img-top bg-light d-flex align-items-center justify-content-center"
                                 style="height: 200px;">
                                <i class="fas fa-calendar-alt fa-3x text-muted"></i>
                            </div>
                        <?php endif; ?>

                        <div class="card-body">
                            <h6 class="card-title">
                                <?php echo e(is_array($evento) ? $evento['titulo'] : $evento->titulo); ?>

                                <?php if(isset($evento->relevancia) && $evento->relevancia >= 3): ?>
                                    <span class="badge bg-warning ms-2">Alta relevancia</span>
                                <?php endif; ?>
                            </h6>

                            <p class="card-text text-muted small">
                                <?php echo e(Str::limit(is_array($evento) ? $evento['descripcion'] : $evento->descripcion, 100)); ?>

                            </p>

                            <!-- Información adicional -->
                            <div class="evento-info">
                                <?php if(isset($evento->fechas_evento) || (is_array($evento) && isset($evento['fechas_evento']))): ?>
                                    <p class="mb-1">
                                        <small class="text-muted">
                                            <i class="fas fa-calendar"></i>
                                            <?php echo e(is_array($evento) ? $evento['fechas_evento'] : $evento->fechas_evento); ?>

                                        </small>
                                    </p>
                                <?php endif; ?>

                                <?php if(isset($evento->categorias) || (is_array($evento) && isset($evento['categorias']))): ?>
                                    <p class="mb-1">
                                        <small class="text-muted">
                                            <i class="fas fa-tag"></i>
                                            <?php echo e(is_array($evento) ? $evento['categorias'] : $evento->categorias); ?>

                                        </small>
                                    </p>
                                <?php endif; ?>

                                <?php if(isset($evento->total_comentarios)): ?>
                                    <p class="mb-0">
                                        <small class="text-muted">
                                            <i class="fas fa-comments"></i>
                                            <?php echo e($evento->total_comentarios); ?> comentarios
                                        </small>
                                    </p>
                                <?php endif; ?>
                            </div>
                        </div>

                        <div class="card-footer bg-transparent">
                            <div class="d-flex justify-content-between align-items-center">
                                <span class="badge
                                    <?php if((is_array($evento) ? $evento['estado'] : $evento->estado) === 'futuro'): ?> bg-success
                                    <?php elseif((is_array($evento) ? $evento['estado'] : $evento->estado) === 'presente'): ?> bg-warning
                                    <?php else: ?> bg-secondary <?php endif; ?>">
                                    <?php echo e(is_array($evento) ? $evento['estado'] : $evento->estado); ?>

                                </span>

                                <?php if(isset($evento->id)): ?>
                                    <a href="<?php echo e(route('eventos.show', is_array($evento) ? $evento['id'] : $evento->id)); ?>"
                                        class="btn btn-sm btn-outline-primary">
                                        Ver detalles
                                    </a>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                </div>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </div>

        <!-- Paginación -->
        <?php if($totalPaginas > 1): ?>
            <div class="row mt-4">
                <div class="col-12">
                    <nav>
                        <ul class="pagination justify-content-center">
                            <?php for($i = 1; $i <= $totalPaginas; $i++): ?>
                                <li class="page-item <?php echo e($i == $paginaActual ? 'active' : ''); ?>">
                                    <a class="page-link"
                                        href="<?php echo e(route('home.buscar', ['q' => $terminoBusqueda, 'page' => $i])); ?>">
                                        <?php echo e($i); ?>

                                    </a>
                                </li>
                            <?php endfor; ?>
                        </ul>
                    </nav>
                </div>
            </div>
        <?php endif; ?>
    <?php endif; ?>
</div>

<style>
.evento-info p {
    margin-bottom: 0.3rem;
}
.card {
    transition: transform 0.2s;
}
.card:hover {
    transform: translateY(-5px);
}
</style>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\laragon\www\src\resources\views/resultados.blade.php ENDPATH**/ ?>