<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Portal de Eventos</title>
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        /* Carrusel */
        .carousel-item img {
            height: 400px;
            width: 100%;
            object-fit: cover;
        }
        /* Cards de eventos */
        .card-img-top {
            height: 200px;
            width: 100%;
            object-fit: cover;
        }
        /* Botones generales */
        .btn-dark {
            background-color: #212529;
            border-color: #212529;
        }
        .btn-dark:hover {
            background-color: #343a40;
            border-color: #343a40;
        }
        /* Botones sociales */
        .btn-google {
            background-color: #db4437;
            color: #fff;
        }
        .btn-google:hover {
            background-color: #c33d30;
            color: #fff;
        }
        .btn-facebook {
            background-color: #1877f2;
            color: #fff;
        }
        .btn-facebook:hover {
            background-color: #145dbf;
            color: #fff;
        }
        /* Navbar personalizado */
        .navbar-brand img {
            max-height: 50px;
            margin-right: 10px;
        }
        .navbar .mx-auto span {
            font-size: 1.8rem;
            font-weight: bold;
            color: white;
        }
    </style>
</head>
<body>

<nav class="navbar navbar-expand-lg navbar-dark bg-dark">
    <div class="container d-flex justify-content-between align-items-center">

        
        
        
        <div class="mx-auto text-center">
            <span>Planazo</span>
        </div>

        
        <div>
            <ul class="navbar-nav flex-row">
                <li class="nav-item me-2"><a class="nav-link" href="<?php echo e(url('/login')); ?>">Login</a></li>
                <li class="nav-item"><a class="nav-link" href="<?php echo e(url('/registro')); ?>">Registro</a></li>
            </ul>
        </div>

    </div>
</nav>


<main class="py-4">
    <?php echo $__env->yieldContent('content'); ?>
</main>


<footer class="bg-dark text-light text-center py-3 mt-5">
    <p>&copy; <?php echo e(date('Y')); ?> Portal de Eventos | Sobre nosotros: Somos una comunidad que promueve cultura, deporte y música.</p>
    <a class="navbar-brand align-items-center" href="<?php echo e(url('/')); ?>">
            <img src="<?php echo e(asset('imagenes/logo.jpg')); ?>" alt="Logo">
        </a>

</footer>

<!-- Bootstrap 5 JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
<?php /**PATH C:\xampp\htdocs\GlesyncProject\resources\views/layouts/app.blade.php ENDPATH**/ ?>