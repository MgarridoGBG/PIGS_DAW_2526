{{-- resources/views/parciales/listados/listarfotografiasfantasma.blade.php
    Vista para mostrar el listado de fotografías fantasma, esta vez en modo texto no como galería
    pues estas fotos no tiene imagen asociada.
    Se usa lazy-loading de la lista, ya que en producción esa consulta puede ser pesada,
    al tener que revisar decenas de miles de fotos y sus carpetas asociadas en storage.
    se ha creado una ruta específica para cargar esta vista, y se llama a esa ruta desde la
    vista principal de listados. De esta forma, la vista principal carga rápido
    y luego se carga el listado completo de fotos fantasma de forma asíncrona.
--}}

@extends('layouts.app')

@push('styles')
@vite(['resources/css/paginas/galeria.css'])
@vite(['resources/css/paginas/listados.css'])
@endpush

@push('scripts')
@vite(['resources/js/paginas/reporfantasma.js'])
@endpush

@section('title', 'Lista de Fotografías Fantasma')

@section('content')
<div class="listado-contenedor">
    <h2 class="titular-listado">Lista de Fotografías Fantasma</h2>

    <div style="width:100%" id="lista-lazyload" data-url="{{ route('filtrarfotosfantasma') }}"> {{-- Contenedor donde se cargará la tabla de fotos fantasma vía AJAX --}}
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