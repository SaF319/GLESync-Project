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

        {{-- Logo a la izquierda --}}
        <a class="navbar-brand d-flex align-items-center" href="{{ url('/') }}">
            <img src="{{ asset('imagenes/logo.jpg') }}" alt="Logo">
        </a>

        {{-- Nombre Planazo centrado --}}
        <div class="mx-auto text-center">
            <span>Planazo</span>
        </div>

        {{-- Links dinámicos a la derecha --}}
        <div>
            <ul class="navbar-nav flex-row">
                @guest
                    {{-- Usuario NO logueado --}}
                    <li class="nav-item me-2">
                        <a class="nav-link" href="{{ route('login') }}">Login</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('register.form') }}">Registro</a>
                    </li>
                @endguest

                @auth
                    {{-- Usuario logueado --}}
                    <li class="nav-item me-2">
                        @if(Route::is('home'))
                            <a class="nav-link" href="{{ route('dashboard') }}">Dashboard</a>
                        @elseif(Route::is('dashboard'))
                            <a class="nav-link" href="{{ route('home') }}">Ir a Home</a>
                        @else
                            <a class="nav-link" href="{{ route('home') }}">Home</a>
                        @endif
                    </li>
                    <li class="nav-item">
                        <form action="{{ route('logout') }}" method="POST" class="d-inline">
                            @csrf
                            <button type="submit" class="btn btn-link nav-link" style="display:inline; cursor:pointer;">
                                Cerrar sesión
                            </button>
                        </form>
                    </li>
                @endauth
            </ul>
        </div>

    </div>
</nav>

{{-- Contenido principal --}}
<main class="py-4">
    @yield('content')
</main>

{{-- Mapa APIGoogleMaps --}}
<script src="https://maps.googleapis.com/maps/api/js?key={{ env('GOOGLE_MAPS_KEY') }}&callback=initMap" async defer></script>

{{-- Footer --}}
<footer class="bg-dark text-light text-center py-3 mt-5">
    <p>&copy; {{ date('Y') }} Portal de Eventos | Sobre nosotros: Somos una comunidad que promueve cultura, deporte y música.</p>
</footer>

<!-- Bootstrap 5 JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
