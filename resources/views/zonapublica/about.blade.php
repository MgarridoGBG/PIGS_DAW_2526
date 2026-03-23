{{-- resources/views/zonapublica/about.blade.php
    Vista para mostrar la sección "Acerca de Nosotros" en la zona pública.
    Trabaja con estilos de TailwindCSS.
--}}


@extends('layouts.app')

@section('title', 'Acerca de Nosotros')

@section('content')

<section class="container mx-auto px-4 sm:px-6 lg:px-8 py-16 md:py-24">

    <!-- Titular -->
    <div class="mb-12 text-center">
        <p class="text-sm font-semibold tracking-wider">QUIÉNES SOMOS</p>
        <h3 class="text-2xl font-bold tracking-tight mt-2">
            Detrás del objetivo
        </h3>
        <p class="mt-3 text-lg text-secundario-tailwind max-w-2xl mx-auto">
            Dos miradas, una misma pasión. Llevamos más de dos décadas capturando
            instantes que se convierten en recuerdos eternos.
        </p>
    </div>

    <!-- Presentación del estudio -->
    <div class="max-w-3xl mx-auto mb-16">
        <p class="text-base font-normal leading-relaxed text-secundario-tailwind">
            Opta Photos nació de la convicción de que una buena fotografía no solo
            retrata lo que ves, sino lo que sientes. Fundado en Jerez de la Frontera en 2001,
            trabajamos en proyectos editoriales, de identidad de marca, eventos y
            fotografía de producto para clientes que valoran la imagen como un
            activo estratégico. Cada encargo es único y lo tratamos como tal: con
            escucha, criterio y un proceso creativo que pone al cliente en el centro.
        </p>
    </div>

    <!-- Equipo: grid 2 columnas -->
    <div class="mb-12 text-center">
        <p class="text-sm font-semibold tracking-wider">EL EQUIPO</p>
        <h3 class="text-2xl font-bold tracking-tight mt-2">
            Las personas detrás del estudio
        </h3>
    </div>

    <div class="grid grid-cols-1 md:w-4/5 lg:w-3/5 md:grid-cols-2 gap-8 mx-auto">

        <!-- Perfil 1 -->
        <div class="flex flex-col gap-4">
            <div class="w-3/5 lg:w-full mx-auto aspect-square bg-cover bg-center rounded-sm shadow-md/50 overflow-hidden">
                <img aria-label="Fotografía de Manu Garrido"
                    src="{{ Vite::asset('resources/images/perfil1.jpg') }}"
                    alt="Fotografía de perfil"
                    class="w-full h-full object-cover">
            </div>
            <div>
                <p class="text-sm font-semibold tracking-wider">COFUNDADOR — DIRECCIÓN DE ARTE</p>
                <h4 class="text-xl text-secundario-tailwind font-bold leading-normal mt-1">
                    Manu Garrido
                </h4>
                <p class="text-base font-normal leading-normal mt-2">
                    Especializado en diseño de marca, fotografía social y de publicidad. Su mirada
                    limpia y su dominio de la luz convierten cada sesión y cada reportaje en
                    una pieza atemporal y cada diseño en arte.
                </p>
            </div>
        </div>

        <!-- Perfil 2 -->
        <div class="flex flex-col gap-4">
            <div class="w-3/5 lg:w-full mx-auto aspect-square bg-cover bg-center rounded-sm shadow-md/50 overflow-hidden">
                <img aria-label="Fotografía de Jorge Garrido"
                    src="{{ Vite::asset('resources/images/perfil2.jpg') }}"
                    alt="Fotografía de perfil"
                    class="w-full h-full object-cover">
            </div>
            <div>
                <p class="text-sm font-semibold tracking-wider">COFUNDADOR — FOTOGRAFÍA</p>
                <h4 class="text-xl text-secundario-tailwind font-bold leading-normal mt-1">
                    Jorge Garrido
                </h4>
                <p class="text-base font-normal leading-normal mt-2">
                    Fotógrafo profesional con especialidad en fotografía de la naturaleza y producto. Se encarga de capturar
                    momentos que transmiten emociones y cuentan historias, desde la
                    conceptualización hasta la entrega final.
                </p>
            </div>
        </div>

    </div>

    <!-- CTA -->
    <div class="mt-16 text-center">
        <p class="text-sm font-semibold tracking-wider">¿TIENES UN PROYECTO EN MENTE?</p>
        <h4 class="text-2xl font-bold tracking-tight mt-2 mb-4">
            Cuéntanos tu historia
        </h4>
        <a class="font-semibold tw-enlace-animado" href="{{ route('contacto') }}">
            Ir a contacto
        </a>
    </div>

</section>

@endsection