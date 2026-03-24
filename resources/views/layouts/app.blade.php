<!doctype html>
<html lang="es">

{{-- resources/views/layouts/app.blade.php

    Plantilla principal de la aplicación. Define la estructura HTML común
    (head, header, main, footer), carga de recursos (favicon, Google Fonts, Vite)
    y zonas que las vistas individuales rellenan: '@yield('title')',
    '@yield('content')', '@stack('styles')' y '@stack('scripts')'.
--}}

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title')</title> {{-- Título dinámico por página --}}
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="icon" type="image/svg+xml" href="{{ Vite::asset('resources/images/favicon.svg') }}">

    {{-- Google Fonts: Manrope. Incluyo la tipografía principal de la aplicación aquí además 
    hacerlo en modo local pues vite no la carga si la aplicación no se carga en el root del
    dominio tampoco la añado como @import en app.css para que TailwindCSS no la 'pise' --}}
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Manrope:wght@200..800&display=swap" rel="stylesheet">

    {{-- Definir URL base ANTES de cargar scripts --}}
    <script>
        window.appBaseUrl = "{{ url('/') }}";
    </script>

    @vite(['resources/css/app.css', 'resources/css/plantillaprincipal.css', 'resources/js/app.js', 'resources/js/paginas/menuhamburguesa.js'])

    {{-- Zona para estilos por página --}}
    @stack('styles')

    {{-- Zona para scripts por página --}}
    @stack('scripts')
</head>

<body>

    <!-- CABECERA (HEADER) -->

    <header class="contenedor-header">
        <div class="separador-secciones"></div>
        <div class="header-dentro">
            <div class="header_logo">
                <a href="{{ route('zonapublica') }}" aria-label="Página principal">
                    <img aria-label="Icono de logo" src="{{ Vite::asset('resources/images/logo.svg') }}" alt="Logo de la empresa" width="100">
                </a>
            </div>

            <button class="header_hamburguesa" id="hamburguesa" aria-label="Abrir menú" aria-expanded="false">
                <span class="linea_hamburguesa"></span>
                <span class="linea_hamburguesa"></span>
                <span class="linea_hamburguesa"></span>
            </button>

            <nav class="navegacion_header" id="nav">
                <ul class="navegacion_lista">
                    <!-- Enlaces textuales -->
                    <li class="navegacion_item"><a href="{{ route('calendario') }}" class="navegacion_link">Cita</a></li>
                    <li class="navegacion_item"><a href="{{ route('fotospublicas') }}" class="navegacion_link">Galería</a></li>
                    <li class="navegacion_item"><a href="{{ route('fotospublicas') }}" class="navegacion_link">Galería</a></li>
                    <li class="navegacion_item"><a href="{{ route('about') }}" class="navegacion_link">Acerca de</a></li>

                    @guest
                    <li class="navegacion_item"><a href="{{ route('login') }}" class="navegacion_link">Login</a></li>
                    <li class="navegacion_item"><a href="{{ route('nuevousuario') }}" class="navegacion_link">Registrarse</a></li>
                    @endguest
                    @auth
                    <li class="navegacion_item"><a href="{{ route('logout') }}" class="navegacion_link" aria-label="Cerrar sesión">Logout</a></li>
                    <li class="navegacion_item"><a href="{{ route('zonaprivada') }}" class="navegacion_link" aria-label="Privado">Privado</a></li>
                    <li class="carro-texto navegacion_item"><a href="{{ route('mostrarcarrito') }}" class="navegacion_link" aria-label="Privado">Ver Carrito</a></li>
                    <!-- -->
                    <li class="carro-svg navegacion_item"><a href="{{ route('mostrarcarrito') }}" class="carro-enlace navegacion_link" aria-label="Privado"><img src="{{ Vite::asset('resources/images/carrito.png') }}" alt="Carrito"></img></a></li>
                    @endauth
                </ul>
            </nav>
        </div>
        <div class="separador-secciones"></div>
    </header>

    <!-- CONTENIDO PRINCIPAL (MAIN) -->
    <main class="flex-grow">
        {{-- Zona para el contenido por página --}}
        @yield('content')
    </main>

    <!-- PIE DE PÁGINA (FOOTER) -->

    <footer class="bg-fondo-tailwind text-primario-tailwind">
        <div class="separador-secciones"></div>
        <div class="max-w-6xl mx-auto px-6 py-2">
            <div class="flex flex-col items-center gap-2 ">

                {{-- Enlaces de navegación --}}
                <nav id="nav-pie-de-pagina">
                    <ul class="flex flex-col md:flex-row justify-center gap-1 md:gap-6 text-sm">
                        <li>
                            <a href="{{ route('contacto') }}"
                                class="tw-enlace">
                                Contacto
                            </a>
                        </li>
                        <li>
                            <a href="{{ route('cookies') }}"
                                class="tw-enlace">
                                Política de Cookies
                            </a>
                        </li>
                        <li>
                            <a href="{{ route('about') }}"
                                class="tw-enlace">
                                Acerca de
                            </a>
                        </li>
                        <li>
                            <a href="{{ route('manual') }}"
                                class="tw-enlace">
                                Ayuda
                            </a>
                        </li>
                    </ul>
                </nav>

                <div id="iconos-sociales-pie-de-pagina" class="flex items-center gap-5">
                    <a href="https://www.instagram.com" target="_blank" rel="noopener noreferrer"
                        aria-label="Instagram"
                        class="tw-social-icono">
                        <svg class="w-8 h-8" fill="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                            <path d="M12 2.163c3.204 0 3.584.012 4.85.07 3.252.148 4.771 1.691 4.919 4.919.058 1.265.069 1.645.069 4.85s-.011 3.585-.069 4.85c-.149 3.225-1.664 4.771-4.919 4.919-1.266.058-1.644.07-4.85.07s-3.585-.012-4.85-.07c-3.252-.148-4.771-1.691-4.919-4.919-.058-1.265-.069-1.645-.069-4.85s.011-3.585.069-4.85c.149-3.225 1.664-4.771 4.919-4.919C8.415 2.175 8.796 2.163 12 2.163zm0 1.802C8.952 3.965 8.618 3.975 7.42 4.025c-2.553.118-3.996 1.563-4.114 4.114-.05 1.198-.06 1.532-.06 4.43s.01 3.232.06 4.43c.118 2.553 1.563 3.996 4.114 4.114 1.198.05 1.532.06 4.43.06s3.232-.01 4.43-.06c2.553-.118 3.996-1.563 4.114-4.114.05-1.198.06-1.532.06-4.43s-.01-3.232-.06-4.43c-.118-2.553-1.563-3.996-4.114-4.114-1.198-.05-1.532-.06-4.43-.06zm0 2.996a5.144 5.144 0 100 10.288 5.144 5.144 0 000-10.288zm0 1.802a3.342 3.342 0 110 6.684 3.342 3.342 0 010-6.684zm6.406-3.332a1.2 1.2 0 100 2.4 1.2 1.2 0 000-2.4z" />
                        </svg>
                        <span class="sr-only">Instagram</span>
                    </a>
                    <a href="https://www.facebook.com" target="_blank" rel="noopener noreferrer"
                        aria-label="Facebook"
                        class="tw-social-icono">
                        <svg class="w-8 h-8" fill="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                            <path d="M22 12c0-5.523-4.477-10-10-10S2 6.477 2 12c0 4.991 3.657 9.128 8.438 9.878v-6.987h-2.54V12h2.54V9.797c0-2.506 1.492-3.89 3.777-3.89 1.094 0 2.238.195 2.238.195v2.46h-1.26c-1.243 0-1.63.771-1.63 1.562V12h2.773l-.443 2.89h-2.33v6.988C18.343 21.128 22 16.991 22 12z" fill-rule="evenodd" />
                        </svg>
                        <span class="sr-only">Facebook</span>
                    </a>
                    <a href="https://www.twitter.com" target="_blank" rel="noopener noreferrer"
                        aria-label="Twitter / X"
                        class="tw-social-icono">
                        <svg class="w-8 h-8" fill="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                            <g transform="translate(1.57, 4) scale(0.060)">
                                <path d="m236 0h46l-101 115 118 156h-92.6l-72.5-94.8-83 94.8h-46l107-123-113-148h94.9l65.5 86.6zm-16.1 244h25.5l-165-218h-27.4z" />
                            </g>
                        </svg>
                        <span class="sr-only">Twitter</span>
                    </a>
                </div>
                <div class="text-center text-xs text-terciario-tailwind ">
                    <p>© 2026 Opta Photos. Todos los derechos reservados.</p>
                </div>
            </div>
        </div>
        <div class="separador-secciones"></div>
    </footer>

</body>

</html>