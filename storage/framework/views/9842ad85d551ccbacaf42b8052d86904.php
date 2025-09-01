 

<?php $__env->startSection('content'); ?>
<div class="container">

    
    <?php
        function getEstadoClass($estado) {
            return match(strtolower($estado)) {
                'futuro' => 'bg-success',    // Verde
                'presente' => 'bg-warning',  // Amarillo
                'pasado' => 'bg-secondary',  // Gris
                'suspendido' => 'bg-danger', // Rojo
                default => 'bg-info'         // Azul si no coincide
            };
        }
    ?>

    
    <div id="eventCarousel" class="carousel slide mb-5" data-bs-ride="carousel" data-bs-interval="2000">
        <div class="carousel-inner">
            <?php $__currentLoopData = $eventos; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $evento): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <div class="carousel-item <?php if($index == 0): ?> active <?php endif; ?>">
                    <img src="<?php echo e($evento['imagen_url'] ?? 'https://via.placeholder.com/1200x400'); ?>" class="d-block w-100" alt="Evento">
                    <div class="carousel-caption d-none d-md-block bg-dark bg-opacity-50 rounded p-2">
                        <h5><?php echo e($evento['titulo']); ?></h5>
                        <p><?php echo e(\Illuminate\Support\Str::limit($evento['descripcion'], 100)); ?></p>
                        <small class="badge <?php echo e(getEstadoClass($evento['estado'])); ?> fs-5">
                            <?php echo e(ucfirst($evento['estado'])); ?>

                        </small>
                    </div>
                </div>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </div>

        
        <button class="carousel-control-prev" type="button" data-bs-target="#eventCarousel" data-bs-slide="prev">
            <span class="carousel-control-prev-icon"></span>
        </button>
        <button class="carousel-control-next" type="button" data-bs-target="#eventCarousel" data-bs-slide="next">
            <span class="carousel-control-next-icon"></span>
        </button>
    </div>

    
    <h2 class="mb-4">Eventos destacados</h2>
    <div class="row">
        <?php $__currentLoopData = $eventos; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $evento): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <div class="col-md-4 mb-4">
                <div class="card h-100 shadow-sm">
                    <img src="<?php echo e($evento['imagen_url'] ?? 'https://via.placeholder.com/400x200'); ?>" class="card-img-top" alt="Evento">
                    <div class="card-body">
                        <h5 class="card-title"><?php echo e($evento['titulo']); ?></h5>
                        <p class="card-text"><?php echo e(\Illuminate\Support\Str::limit($evento['descripcion'], 80)); ?></p>
                        <span class="badge <?php echo e(getEstadoClass($evento['estado'])); ?> fs-5">
                            <?php echo e(ucfirst($evento['estado'])); ?>

                        </span>
                    </div>
                    <div class="card-footer text-end">
                        
                        <a href="#" class="btn btn-sm btn-dark">Ver más</a>
                    </div>
                </div>
            </div>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
    </div>

</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\GlesyncProject\resources\views/home.blade.php ENDPATH**/ ?>