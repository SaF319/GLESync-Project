<?php $__env->startSection('content'); ?>
<div class="container">
    <h2 class="mb-4">Evento <?php echo e($evento->titulo); ?></h2>

    
    <div class="mb-3">
        <?php if($evento->imagen): ?>
            <img src="<?php echo e(asset($evento->imagen->ruta)); ?>" class="card-img-top" alt="<?php echo e($evento->titulo); ?>" style="height: 400px; object-fit: cover;">
        <?php else: ?>
            <h3>Imagen no disponible</h3>
        <?php endif; ?>
    </div>

    
    <?php if($errors->any()): ?>
        <div class="alert alert-danger">
            <ul>
                <?php $__currentLoopData = $errors->all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $error): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <li><?php echo e($error); ?></li>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </ul>
        </div>
    <?php endif; ?>

    
    <div class="mb-3">
        <h3>Descripción</h3>
        <p><?php echo e($evento->descripcion); ?></p>
    </div>

    
    <div class="mb-3">
        <h3>Hora/s del Evento</h3>
        <ul>
            <li>
                <?php echo e(optional($evento->fechasHoras->first())->fecha_hora
                    ? $evento->fechasHoras->first()->fecha_hora->format('Y-m-d H:i')
                    : 'Fecha no disponible'); ?>

            </li>
        </ul>
    </div>

    
    <div class="mb-3">
        <h2>Categorías</h2>
        <h3><?php echo e($evento->categorias->pluck('nombre')->implode(', ')); ?></h3>
    </div>

    
    <div class="mt-4">
        <a href="<?php echo e(route('dashboard')); ?>" class="btn btn-dark">
            ← Volver al Dashboard
        </a>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\laragon\www\src\resources\views/eventos/show.blade.php ENDPATH**/ ?>