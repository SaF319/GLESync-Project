<?php $__env->startSection('content'); ?>
<div class="row justify-content-center" style="min-height:70vh; align-items:center;">
    <div class="col-md-5">
        <h2 class="text-center mb-4">Bienvenido a Planazo 🎵</h2>
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


                
                <form action="<?php echo e(route('login.process')); ?>" method="POST">
                    <?php echo csrf_field(); ?>
                    <div class="mb-3">
                        <label for="email" class="form-label">Correo electrónico</label>
                        <input type="email" class="form-control" name="email" id="email" placeholder="tu@correo.com" required>
                    </div>
                    <div class="mb-3">
                        <label for="password" class="form-label">Contraseña</label>
                        <input type="password" class="form-control" name="password" id="password" placeholder="••••••••" required>
                    </div>
                    <button type="submit" class="btn btn-dark w-100">Ingresar</button>
                </form>

                <hr>

                
                <div class="d-grid gap-2">
                    <button class="btn btn-google">Iniciar con Google</button>
                    <button class="btn btn-facebook">Iniciar con Facebook</button>
                </div>

                <div class="mt-3 text-center">
                    <small>¿No tienes cuenta? <a href="<?php echo e(url('/registro')); ?>">Regístrate aquí</a></small>
                </div>

                
                <div class="mt-3 text-center">
                    <a href="<?php echo e(url('/')); ?>" class="btn btn-outline-dark w-50">Volver a Home</a>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    .btn-google {
        background-color: #db4437;
        color: white;
    }
    .btn-google:hover {
        background-color: #c33d30;
        color: white;
    }
    .btn-facebook {
        background-color: #1877f2;
        color: white;
    }
    .btn-facebook:hover {
        background-color: #145dbf;
        color: white;
    }
</style>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH /var/www/html/resources/views/login.blade.php ENDPATH**/ ?>