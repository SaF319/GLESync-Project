<?php $__env->startSection('content'); ?>
<div class="container">
    <h2 class="mb-4">Editar Evento</h2>

    
    <?php if($errors->any()): ?>
        <div class="alert alert-danger">
            <ul>
                <?php $__currentLoopData = $errors->all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $error): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <li><?php echo e($error); ?></li>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </ul>
        </div>
    <?php endif; ?>

    <form action="<?php echo e(route('eventos.update', $evento->id)); ?>" method="POST" enctype="multipart/form-data">
        <?php echo csrf_field(); ?>
        <?php echo method_field('PUT'); ?>

        
        <div class="mb-3">
            <label for="titulo" class="form-label">Título</label>
            <input type="text" name="titulo" id="titulo" class="form-control" value="<?php echo e(old('titulo', $evento->titulo)); ?>" required>
        </div>

        
        <div class="mb-3">
            <label for="descripcion" class="form-label">Descripción</label>
            <textarea name="descripcion" id="descripcion" class="form-control" rows="4" required><?php echo e(old('descripcion', $evento->descripcion)); ?></textarea>
        </div>

        
        <div class="mb-3">
            <label for="fecha_hora" class="form-label">Fecha y Hora</label>
            <input type="datetime-local" name="fecha_hora" id="fecha_hora" class="form-control"
                    value="<?php echo e(old('fecha_hora', $evento->fechasHoras->first() ? $evento->fechasHoras->first()->fecha_hora->format('Y-m-d\TH:i') : '')); ?>" required>
        </div>

        
        <div class="mb-3">
            <label for="categorias" class="form-label">Categorías</label>
            <select name="categorias[]" id="categorias" class="form-select" multiple required>
                <?php $__currentLoopData = $todasCategorias; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $categoria): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <option value="<?php echo e($categoria->id); ?>"
                        <?php if(in_array($categoria->id, $evento->categorias->pluck('id')->toArray())): ?> selected <?php endif; ?>>
                        <?php echo e($categoria->nombre); ?>

                    </option>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </select>
            <small class="text-muted">Mantén presionada la tecla Ctrl (o Cmd) para seleccionar múltiples categorías.</small>
        </div>

        
        <div class="mb-3">
            <label for="imagen" class="form-label">Imagen</label>
            <input type="file" name="imagen" id="imagen" class="form-control">
            <?php if($evento->imagen): ?>
                <img src="<?php echo e(asset($evento->imagen->ruta)); ?>" alt="Imagen Evento" class="mt-2" style="max-width:200px;">
            <?php endif; ?>
        </div>

        <button type="submit" class="btn btn-dark">Actualizar Evento</button>
        <a href="<?php echo e(route('dashboard')); ?>" class="btn btn-outline-secondary">Cancelar</a>
    </form>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\laragon\www\src\resources\views/eventos/edit.blade.php ENDPATH**/ ?>