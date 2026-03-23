{{-- resources/views/zonapublica/cookies.blade.php
    Vista para mostrar la sección de política de cookies en la zona pública,
    Utiliza estilos de TailwindCSS.
--}}

@extends('layouts.app')

@section('title', 'Política de Cookies')

@section('content')

<section class="container mx-auto px-4 sm:px-6 lg:px-8 py-16 md:py-24">

    <div class="mb-12 text-center">
        <p class="text-sm font-semibold tracking-wider">INFORMACIÓN LEGAL</p>
        <h3 class="text-2xl font-bold tracking-tight mt-2">
            Política de Cookies
        </h3>
        <p class="mt-3 text-lg text-secundario-tailwind max-w-2xl mx-auto">
            Esta política explica cómo Opta Photos utiliza cookies y tecnologías
            similares para reconocerte cuando visitas nuestro sitio web, y cuáles
            son tus derechos para controlar su uso.
        </p>
    </div>

    <div class="text-left flex flex-col gap-10 max-w-4xl mx-auto">

        <div class="flex flex-col gap-4">
            <div>
                <p class="text-sm font-semibold tracking-wider">¿QUÉ SON LAS COOKIES?</p>
                <h4 class="text-xl text-secundario-tailwind font-bold leading-normal mt-1">
                    Pequeños archivos que mejoran tu experiencia
                </h4>
                <p class="text-base font-normal leading-normal mt-2">
                    Las cookies son pequeños archivos de datos que se almacenan en tu
                    ordenador o dispositivo móvil cuando visitas un sitio web. Son
                    ampliamente utilizadas para hacer que las páginas funcionen
                    correctamente y de manera más eficiente.
                </p>
                <p class="text-base font-normal leading-normal mt-2">
                    Las cookies del propietario del sitio se denominan "cookies propias"
                    y las establecidas por terceros, "cookies de terceros".
                </p>
            </div>
        </div>

        <!-- Tipos de cookies -->
        <div class="flex flex-col gap-4">
            <div>
                <p class="text-sm font-semibold tracking-wider">TIPOS DE COOKIES</p>
                <h4 class="text-xl text-secundario-tailwind font-bold leading-normal mt-1">
                    Cookies que utilizamos en el sitio
                </h4>
                <div class="flex flex-col gap-4 mt-2">
                    <div class="rounded-sm shadow-md/50 bg-quinario-tailwind p-5">
                        <p class="text-sm font-semibold tracking-wider">TÉCNICAS Y ESENCIALES</p>
                        <p class="text-base font-normal leading-normal mt-2">
                            Estrictamente necesarias para que el sitio funcione y puedas
                            acceder a las áreas seguras.
                        </p>
                    </div>
                    <div class="rounded-sm shadow-md/50 bg-quinario-tailwind p-5">
                        <p class="text-sm font-semibold tracking-wider">ANALÍTICAS Y DE RENDIMIENTO</p>
                        <p class="text-base font-normal leading-normal mt-2">
                            Nos ayudan a entender cómo se utiliza el sitio web y la eficacia
                            de nuestras campañas de marketing.
                        </p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Control de cookies -->
        <div class="flex flex-col gap-4">
            <div>
                <p class="text-sm font-semibold tracking-wider">CONTROL DE COOKIES</p>
                <h4 class="text-xl text-secundario-tailwind font-bold leading-normal mt-1">
                    Tú decides qué cookies aceptas
                </h4>
                <p class="text-base font-normal leading-normal mt-2">
                    Tienes derecho a decidir si aceptas o rechazas las cookies. Puedes
                    configurar tu navegador para aceptar o rechazarlas. Si decides
                    rechazarlas, algunas funcionalidades podrían verse limitadas.
                </p>               
            </div>
        </div>

        <!-- Contacto -->
        <div class="flex flex-col gap-4">
            <div>
                <p class="text-sm font-semibold tracking-wider">CONTACTO</p>
                <h4 class="text-xl text-secundario-tailwind font-bold leading-normal mt-1">
                    ¿Preguntas sobre esta política?
                </h4>
                <p class="text-base font-normal leading-normal mt-2">
                    Si tienes alguna pregunta sobre nuestro uso de cookies u otras
                    tecnologías, puedes escribirnos a
                    <a class="font-semibold tw-enlace-animado" href="mailto:privacidad@optaphotos.com">
                        privacidad@optaphotos.com</a>.
                </p>
            </div>
        </div>

    </div>
</section>

@endsection