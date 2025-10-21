<?php $__env->startSection('content'); ?>
<div class="container" style="min-height:70vh; display:flex; justify-content:center; align-items:center;">
    <div class="col-md-6">
        <div class="card shadow-sm">
            <div class="card-body text-center">
                <h2 class="mb-4">Bienvenido 🎉</h2>

                <?php if(auth()->guard()->check()): ?>
                    <h4 class="mb-3">Hola, <strong><?php echo e(Auth::user()->nombre); ?></strong></h4>
                    <p class="text-muted mb-4">Has iniciado sesión correctamente en Planazo</p>

                    <form action="<?php echo e(route('logout')); ?>" method="POST">
                        <?php echo csrf_field(); ?>
                        <button type="submit" class="btn btn-danger w-50">Cerrar sesión</button>
                    </form>
                <?php else: ?>
                    <p class="text-danger mb-3">No has iniciado sesión.</p>
                    <a href="<?php echo e(route('login')); ?>" class="btn btn-dark">Inicia sesión</a>
                <?php endif; ?>

                
                <div class="mt-4">
                    <a href="<?php echo e(url('/')); ?>" class="btn btn-outline-dark">Volver a Home</a>
                </div>
            </div>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH /var/www/html/resources/views/dashboard.blade.php ENDPATH**/ ?>