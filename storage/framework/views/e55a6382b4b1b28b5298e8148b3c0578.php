<?php $__env->startSection('content'); ?>
<div class="row justify-content-center" style="min-height:70vh; align-items:center;">
    <div class="col-md-6">
        <h2 class="text-center mb-4">Crear cuenta en Planazo 🎵</h2>
        <div class="card shadow-sm">
            <div class="card-body">

                
                <?php if($errors->any()): ?>
                    <?php $__currentLoopData = $errors->all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $error): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <p style="color:red;"><?php echo e($error); ?></p>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                <?php endif; ?>

                
                <?php if(session('success')): ?>
                    <p style="color:green;"><?php echo e(session('success')); ?></p>
                <?php endif; ?>

                
                <form action="<?php echo e(route('register.process')); ?>" method="POST">
                    <?php echo csrf_field(); ?>
                    <div class="mb-3">
                        <label for="name" class="form-label">Nombre completo</label>
                        <input type="text" class="form-control" name="name" id="name" placeholder="Tu nombre" required>
                    </div>
                    <div class="mb-3">
                        <label for="email" class="form-label">Correo electrónico</label>
                        <input type="email" class="form-control" name="email" id="email" placeholder="tu@correo.com" required>
                    </div>
                    <div class="mb-3">
                        <label for="password" class="form-label">Contraseña</label>
                        <input type="password" class="form-control" name="password" id="password" placeholder="••••••••" required>
                    </div>
                    <div class="mb-3">
                        <label for="password_confirmation" class="form-label">Confirmar contraseña</label>
                        <input type="password" class="form-control" name="password_confirmation" id="password_confirmation" placeholder="••••••••" required>
                    </div>
                    <button type="submit" class="btn btn-dark w-100">Registrarse</button>
                </form>

                <div class="mt-3 text-center">
                    <small>¿Ya tienes cuenta? <a href="<?php echo e(url('/login')); ?>">Inicia sesión</a></small>
                </div>

                
                <div class="mt-3 text-center">
                    <a href="<?php echo e(url('/')); ?>" class="btn btn-outline-dark w-50">Volver a Home</a>
                </div>
            </div>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH /var/www/html/resources/views/registro.blade.php ENDPATH**/ ?>