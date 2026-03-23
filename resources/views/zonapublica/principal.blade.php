{{-- resources/views/zonapublica/principal.blade.php
    Vista para mostrar la página principal de la zona pública,
    Utiliza estilos de TailwindCSS para el diseño y la presentación de los elementos.
--}}
@extends('layouts.app')

@section('title', 'Zona Publica')

@push('styles')
@vite(['resources/css/paginas/paginainicio.css'])
@endpush

@section('content')

@auth
<div>
    <p class="mb-[10px] mt-[5px] text-sm font-semibold uppercase tracking-wider text-secundario-tailwind">Bienvenido, {{ Auth::user()->nombre }}. Puedes acceder a <span><a class="tw-enlace-animado" href=" {{ route('zonaprivada') }}">tu zona privada</a></span>.</p>
</div>
@endauth
@guest
<div>
    <p class="mb-[10px] mt-[5px] text-sm font-semibold uppercase tracking-wider text-secundario-tailwind">Bienvenido. No estás autenticado, por favor <span><a class="tw-enlace-animado" href=" {{ route('formlogin') }}">inicia sesión</a></span>.</p>
</div>
@endguest

<div>
    <div class="relative flex min-h-screen w-full flex-col">
        <div class="flex-grow">
            <!-- Hero Image -->
            <div class="w-full h-[65vh] flex items-center justify-center bg-center bg-cover bg-no-repeat heroimg">
                <div class="text-center text-white px-4">
                    <h1 class="text-5xl md:text-7xl font-extrabold tracking-tight">ARTE EN CADA CAPTURA</h1>
                    <p class="mt-4 max-w-2xl mx-auto text-lg md:text-xl font-light text-white/90">Inmortalizamos tus momentos con una visión limpia, profesional y atemporal.</p>
                </div>
            </div>
            <!-- Principal -->
            <section class="container mx-auto px-4 sm:px-6 lg:px-8 py-16 md:py-24">
                <!-- Titular -->
                <div class="mb-12 text-center">
                    <h3 class="text-4xl  font-bold tracking-tight">
                        Nuestros servicios
                    </h3>
                    <p
                        class="mt-3 text-lg text-secundario-tailwind max-w-2xl mx-auto">
                        Descubre lo que podemos hacer por tí y tu proyectos de imagen.
                    </p>
                </div>
                <!-- Servicios -->
                <div class="text-left grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-8">
                    <!-- Servicio 1 -->
                    <div class="flex flex-col gap-4 group">

                        <div class="w-full aspect-square bg-cover bg-center rounded-sm shadow-md/50 overflow-hidden">
                            <img aria-label="Diseño de marca"
                                src="{{ Vite::asset('resources/images/marca.jpg') }}"
                                alt="Diseño de marca"
                                class="w-full h-full object-cover">
                        </div>


                        <div>
                            <p class="text-sm font-semibold tracking-wider">DISEÑO DE MARCA</p>
                            <h4 class="text-xl text-secundario-tailwind font-bold leading-normal mt-1">Lleva la imagen de tu negocio a otro nivel</h4>
                            <p
                                class="text-base font-normal leading-normal mt-2">
                                Desarrollamos marcas con identidad propia, creando proyectos integrales de identidad visual corporativa que dibujan cada marca.
                            </p>
                            <a
                                class="font-semibold leading-normal mt-4 tw-enlace-animado"
                                href="{{ route('contacto') }}">Quiero potenciar mi marca</a>
                        </div>
                    </div>
                    <!-- Servicio 2 -->
                    <div class="flex flex-col gap-4 group">

                        <div class="w-full aspect-square bg-cover bg-center rounded-sm shadow-md/50 overflow-hidden">
                            <img aria-label="Fotografía de Producto"
                                src="{{ Vite::asset('resources/images/producto.jpg') }}"
                                alt="Fotografía de producto"
                                class="w-full h-full object-cover">
                        </div>
                        <div>
                            <p class="text-sm font-semibold tracking-wider">FOTOGRAFÍA DE PRODUCTO</p>
                            <h4 class="text-xl text-secundario-tailwind font-bold leading-normal mt-1">Muestra la mejor faceta de tu producto</h4>
                            <p
                                class="text-base font-normal leading-normal mt-2">
                                Realizamos fotografía publicitaria y de producto, capturando imágenes que potencian la identidad de cada marca.
                            </p>
                            <a
                                class="font-semibold leading-normal mt-4 tw-enlace-animado"
                                href="{{ route('contacto') }}">Quiero enseñar lo que hago</a>
                        </div>
                    </div>
                    <!-- Servicio 3 -->
                    <div class="flex flex-col gap-4 group">

                        <div class="w-full aspect-square bg-cover bg-center rounded-sm shadow-md/50 overflow-hidden">
                            <img aria-label="Diseño editorial"
                                src="{{ Vite::asset('resources/images/editorial.jpg') }}"
                                alt="Diseño editorial"
                                class="w-full h-full object-cover">
                        </div>
                        <div>
                            <p class="text-sm font-semibold tracking-wider">DISEÑO EDITORIAL</p>
                            <h4 class="text-xl text-secundario-tailwind font-bold leading-normal mt-1">Haz que tus publicaciones luzcan</h4>
                            <p
                                class="text-base font-normal leading-normal mt-2">
                                Nos especializamos en diseño editorial y publicitario, creando revistas, catálogos, folletos y carteles que comunican con impacto.
                            </p>
                            <a
                                class="font-semibold leading-normal mt-4 tw-enlace-animado"
                                href="{{ route('contacto') }}">Quiero editar una publicación</a>
                        </div>
                    </div>
                    <!-- Servicio 4 -->
                    <div class="flex flex-col gap-4 group">

                        <div class="w-full aspect-square bg-cover bg-center rounded-sm shadow-md/50 overflow-hidden">
                            <img aria-label="Fotografía de eventos"
                                src="{{ Vite::asset('resources/images/social.jpg') }}"
                                alt="Fotografía de eventos"
                                class="w-full h-full object-cover">
                        </div>

                        <div>
                            <p class="text-sm font-semibold tracking-wider">FOTOGRAFÍA DE EVENTOS</p>
                            <h4 class="text-xl text-secundario-tailwind font-bold leading-normal mt-1">Capturamos los momentos más importantes</h4>
                            <p
                                class="text-base font-normal leading-normal mt-2">
                                Guardamos los recuerdos de tus mejores momentos, documentando cada detalle y emoción para que cada recuerdo perdure.
                            </p>
                            <a
                                class="font-semibold leading-normal mt-4 tw-enlace-animado"
                                href="{{ route('contacto') }}">Tengo algo que celebrar</a>
                        </div>
                    </div>
                    <!-- Fin de servicios -->
                </div>
            </section>
        </div>
    </div>
</div>

@endsection