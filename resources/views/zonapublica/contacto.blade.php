 {{-- resources/views/zonapublica/contacto.blade.php
    Vista para mostrar la sección de contacto en la zona pública,
    Utiliza estilos de TailwindCSS.
--}}

@extends('layouts.app')

@section('title', 'Contacto')

@section('content')

<section class="container mx-auto px-4 sm:px-6 lg:px-8 py-16 md:py-24">

    <div class="mb-12 text-center">
        <p class="text-sm font-semibold tracking-wider">ESTAMOS AQUÍ PARA TI</p>
        <h3 class="text-2xl font-bold tracking-tight mt-2">
            Contacta con nosotros
        </h3>
        <p class="mt-3 text-lg text-secundario-tailwind max-w-2xl mx-auto">
            Cuéntanos tu proyecto y te responderemos en menos de 24 horas.
            Queremos conocer tu historia y ayudarte a hacerla memorable.
        </p>
    </div>
    <div class="text-left grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-8 max-w-5xl mx-auto">

        <div class="flex flex-col gap-4">
            <div>
                <p class="text-sm font-semibold tracking-wider">ESTUDIO</p>
                <h4 class="text-xl text-secundario-tailwind font-bold leading-normal mt-1">
                    Opta Photos
                </h4>
                <p class="text-base font-normal leading-normal mt-2">
                    Fotografía y diseño profesional con visión atemporal.
                </p>
            </div>
        </div>
        <div class="flex flex-col gap-4">
            <div>
                <p class="text-sm font-semibold tracking-wider">DIRECCIÓN</p>
                <h4 class="text-xl text-secundario-tailwind font-bold leading-normal mt-1">
                    Dónde encontrarnos
                </h4>
                <p class="text-base font-normal leading-normal mt-2">
                    Calle Siena, 1.<br>
                    11495 Jerez de la Frontera, Cádiz
                </p>
            </div>
        </div>

        <div class="flex flex-col gap-4">
            <div>
                <p class="text-sm font-semibold tracking-wider">EMAIL</p>
                <h4 class="text-xl text-secundario-tailwind font-bold leading-normal mt-1">
                    Escríbenos
                </h4>
                <p class="text-base font-normal leading-normal mt-2">
                    <a class="font-semibold tw-enlace-animado" href="mailto:privacidad@optaphotos.com">
                        contacto@optaphotos.com
                    </a>
                </p>
            </div>
        </div>
        <div class="flex flex-col gap-4">
            <div>
                <p class="text-sm font-semibold tracking-wider">TELÉFONO</p>
                <h4 class="text-xl text-secundario-tailwind font-bold leading-normal mt-1">
                    Llámanos
                </h4>
                <p class="text-base font-normal leading-normal mt-2">
                    +34 956 301 102
                </p>
            </div>
        </div>

    </div>
</section>

@endsection