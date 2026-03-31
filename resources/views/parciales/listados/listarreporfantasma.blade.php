{{-- resources/views/parciales/listados/listarreporfantasma.blade.php
    Vista para mostrar el listado de reportajes fantasma.
    Para usar lazy-loading de la lista, ya que en producción esa consulta puede ser pesada,
    al tener que revisar literalmente cientos de reportajes y sus carpetas asociadas en storage.
    se ha creado una ruta específica para cargar esta vista, y se llama a esa ruta desde la
    vista principal de listados. De esta forma, la vista principal carga rápido
    y luego se carga el listado completo de reportajes fantasma de forma asíncrona.
--}}

@extends('layouts.app')

@push('styles')
@vite(['resources/css/paginas/listados.css'])
@endpush

@push('scripts')
@vite(['resources/js/paginas/reporfantasma.js'])
@endpush

@section('title', 'Lista de Reportajes Fantasma')

@section('content')
<div class="listado-contenedor">
    <h2 class="titular-listado">Listado de Reportajes Fantasma</h2>

<div id="lista-lazyload" style="width:100%"  data-url="{{ route('filtrarreportajesfantasma') }}"> {{-- Contenedor donde se cargará la tabla de reportajes fantasma vía AJAX --}}
    <div class="spinner-contenedor">
        <img src="{{ Vite::asset('resources/images/spinner.gif') }}" alt="Cargando...">
    </div>
</div>
    <div class="redirector-publi-priv">
        @auth
        <a class="tw-enlace" href="{{ route('zonaprivada') }}">Ir a <b>Zona Privada</b></a>
        <p> | </p>
        @endauth
        <a class="tw-enlace" href="{{ route('zonapublica') }}">Ir a <b>Zona Pública</b></a>
    </div>
</div>
@endsection
