<?php $__env->startSection('content'); ?>
<?php
    $eventos = $eventos ?? collect();
?>

<div class="container py-4">
    
    <div class="row mb-4">
        <div class="col-md-12">
            <div class="card shadow-sm">
                <div class="card-body text-center">
                    <h2 class="mb-3">Dashboard 🎉</h2>
                    <h4 class="mb-3">Hola, <strong><?php echo e(Auth::user()->nombre); ?></strong></h4>
                    <p class="text-muted mb-3">Bienvenido a tu panel de control de Planazo</p>

                    <div class="d-flex justify-content-center gap-3 flex-wrap">
                        <!--
                        <form action="<?php echo e(route('logout')); ?>" method="POST" class="d-inline">
                            <?php echo csrf_field(); ?>
                            <button type="submit" class="btn btn-danger">Cerrar sesión</button>
                        </form>
                        <a href="<?php echo e(url('/')); ?>" class="btn btn-outline-dark">Volver a Home</a>-->
                        <a href="<?php echo e(route('eventos.create')); ?>" class="btn btn-primary">
                            <i class="bi bi-plus-circle"></i> Crear Nuevo Evento
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    
    <div class="row mb-4">
        <div class="col-md-12">
            <div class="card shadow-sm">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0">
                        <i class="bi bi-calendar-event"></i> Mis Eventos
                    </h5>
                </div>
                <div class="card-body">
                    
                    <?php if(session('success')): ?>
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            <?php echo e(session('success')); ?>

                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    <?php endif; ?>

                    <?php if(session('error')): ?>
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            <?php echo e(session('error')); ?>

                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    <?php endif; ?>

                    
                    <?php if($eventos->count() > 0): ?>
                        <div class="row">
                            <?php $__currentLoopData = $eventos; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $evento): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <div class="col-md-6 col-lg-4 mb-4">
                                    <div class="card h-100 shadow-sm">
                                        <?php if($evento->imagen): ?>
                                            <img src="<?php echo e(asset($evento->imagen->ruta)); ?>" class="card-img-top" alt="<?php echo e($evento->titulo); ?>" style="height: 200px; object-fit: cover;">
                                        <?php else: ?>
                                            <div class="card-img-top bg-secondary d-flex align-items-center justify-content-center" style="height: 200px;">
                                                <i class="bi bi-image text-white" style="font-size: 3rem;"></i>
                                            </div>
                                        <?php endif; ?>
                                        <div class="card-body">
                                            <h6 class="card-title"><?php echo e(Str::limit($evento->titulo, 50)); ?></h6>
                                            <p class="card-text small text-muted">
                                                <strong>Fecha:</strong>
                                                <?php if($evento->fechasHoras->first()): ?>
                                                    <?php echo e(\Carbon\Carbon::parse($evento->fechasHoras->first()->fecha_hora)->format('d/m/Y H:i')); ?>

                                                <?php else: ?>
                                                    Sin fecha definida
                                                <?php endif; ?>
                                            </p>
                                            <p class="card-text small">
                                                <strong>Categorías:</strong>
                                                <?php echo e($evento->categorias->pluck('nombre')->implode(', ')); ?>

                                            </p>
                                        </div>
                                        <div class="card-footer bg-white">
                                            <div class="btn-group w-100">
                                                <a href="<?php echo e(route('eventos.show', $evento->id)); ?>" class="btn btn-sm btn-outline-info">
                                                    <i class="bi bi-eye"></i> Ver
                                                </a>
                                                <a href="<?php echo e(route('eventos.edit', $evento->id)); ?>" class="btn btn-sm btn-outline-warning">
                                                    <i class="bi bi-pencil"></i> Editar
                                                </a>
                                                <form action="<?php echo e(route('eventos.destroy', $evento->id)); ?>" method="POST" class="d-inline">
                                                    <?php echo csrf_field(); ?>
                                                    <?php echo method_field('DELETE'); ?>
                                                    <button type="submit" class="btn btn-sm btn-outline-danger"
                                                            onclick="return confirm('¿Estás seguro de eliminar este evento?')">
                                                        <i class="bi bi-trash"></i> Eliminar
                                                    </button>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </div>
                    <?php else: ?>
                        <div class="text-center py-5">
                            <i class="bi bi-calendar-x" style="font-size: 3rem; color: #6c757d;"></i>
                            <h5 class="mt-3 text-muted">No tienes eventos creados</h5>
                            <p class="text-muted">Comienza creando tu primer evento</p>
                            <a href="<?php echo e(route('eventos.create')); ?>" class="btn btn-primary">
                                <i class="bi bi-plus-circle"></i> Crear Primer Evento
                            </a>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>

    
    <div class="row">
        <div class="col-md-4 mb-3">
            <div class="card text-center shadow-sm">
                <div class="card-body">
                    <h3 class="text-primary"><?php echo e($eventos->count()); ?></h3>
                    <p class="text-muted mb-0">Eventos Totales</p>
                </div>
            </div>
        </div>
        <div class="col-md-4 mb-3">
            <div class="card text-center shadow-sm">
                <div class="card-body">
                    <h3 class="text-success">
                        <?php echo e($eventos->filter(fn($evento) => $evento->fechasHoras->count() > 0)->count()); ?>

                    </h3>
                    <p class="text-muted mb-0">Eventos Programados</p>
                </div>
            </div>
        </div>
        <div class="col-md-4 mb-3">
            <div class="card text-center shadow-sm">
                <div class="card-body">
                    <h3 class="text-info">
                        <?php echo e($eventos->filter(fn($evento) => $evento->imagen)->count()); ?>

                    </h3>
                    <p class="text-muted mb-0">Con Imágenes</p>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Bootstrap Icons -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">

<style>
    .card {
        transition: transform 0.2s ease-in-out;
    }
    .card:hover {
        transform: translateY(-2px);
    }
    .btn-group .btn {
        flex: 1;
    }
</style>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\laragon\www\src\resources\views/dashboard.blade.php ENDPATH**/ ?>