<?php $__env->startSection('content'); ?>
<div class="container py-4">
    <h2>Conviértete en Organizador</h2>
    <p>Para crear un evento, primero necesitas registrarte como organizador.</p>

    <form action="<?php echo e(route('organizador.hacerse')); ?>" method="POST">
        <?php echo csrf_field(); ?>
        <div class="mb-3">
            <label for="contacto" class="form-label">Contacto</label>
            <input type="text" name="contacto" id="contacto" class="form-control"
                    value="<?php echo e(old('contacto', Auth::user()->email)); ?>">
        </div>

        <button type="submit" class="btn btn-primary">Registrarme como Organizador</button>
        <a href="<?php echo e(route('dashboard')); ?>" class="btn btn-secondary">Cancelar</a>
    </form>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\laragon\www\src\resources\views/eventos/no_organizador.blade.php ENDPATH**/ ?>