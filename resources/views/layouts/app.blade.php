<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Portal de Eventos</title>
    <link rel="icon" type="image/x-icon" href="/imagenes/logoWeb.png">
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
        .navbar .mx-auto span {
            font-size: 1.8rem;
            font-weight: bold;
        }
        .navbar {
        background: linear-gradient(to bottom, #ffb24d, #ff8c00);
        }
        .nav-link {
            color: white !important;
        }
        .nav-link:hover {
            text-decoration: underline;
        }

        body {
        background-color: #fff3e0;
        font-family: 'Poppins', sans-serif;
        color: #212121;
        }
        main {
        padding: 2rem 0;
        }

    </style>
</head>
<body>

<nav class="navbar navbar-expand-lg navbar-light bg-white shadow-sm flex-column py-3">

    {{-- Logo centrado arriba --}}
    <div class="text-center mb-2">
        <a class="navbar-brand d-inline-block" href="{{ url('/') }}">
            <img
                src="{{ asset('imagenes/planazoLogo.png') }}"
                alt="Planazo Logo"
                class="mx-auto d-block"
                style="max-height:180px; width:auto; display:block;"
            >
        </a>
    </div>

    {{-- Links centrados debajo del logo --}}
    <div class="text-center">
        <ul class="navbar-nav justify-content-center flex-wrap">
            @guest
                <li class="nav-item mx-2">
                    <a class="nav-link fw-semibold" href="{{ route('login') }}">Login</a>
                </li>
                <li class="nav-item mx-2">
                    <a class="nav-link fw-semibold" href="{{ route('register.form') }}">Registro</a>
                </li>
            @endguest

            @auth
                <li class="nav-item mx-2">
                    @if(Route::is('home'))
                        <a class="nav-link fw-semibold" href="{{ route('dashboard') }}">Dashboard</a>
                    @elseif(Route::is('dashboard'))
                        <a class="nav-link fw-semibold" href="{{ route('home') }}">Ir a Home</a>
                    @else
                        <a class="nav-link fw-semibold" href="{{ route('home') }}">Home</a>
                    @endif
                </li>
                <li class="nav-item mx-2">
                    <form action="{{ route('logout') }}" method="POST" class="d-inline">
                        @csrf
                        <button type="submit" class="btn btn-link nav-link fw-semibold" style="display:inline; cursor:pointer;">
                            Cerrar sesión
                        </button>
                    </form>
                </li>
            @endauth
        </ul>
    </div>

</nav>

{{-- Contenido principal --}}
<main class="py-4">
    @yield('content')
</main>

<script>
    window.Laravel = {
        csrfToken: '{{ csrf_token() }}'
    };

    if (typeof axios !== 'undefined') {
        axios.defaults.headers.common['X-CSRF-TOKEN'] = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
    }
</script>

{{-- Mapa APIGoogleMaps --}}
{{--<script src="https://maps.googleapis.com/maps/api/js?key={{ env('GOOGLE_MAPS_KEY') }}&callback=initMap" async defer></script>--}}

{{-- Footer --}}
<footer class="text-light text-center py-4 mt-5" style="background: linear-gradient(135deg, #f57c00, #e65100);">
  <p class="mb-1">&copy; {{ date('Y') }} Planazo | Promoviendo cultura, deporte y música.</p>
  <img src="{{ asset('imagenes/logoOficial.png') }}" alt="Logo" style="width:50px; height:50px; border-radius:50%;">
</footer>


<!-- Bootstrap 5 JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
