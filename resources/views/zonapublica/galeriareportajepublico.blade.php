 {{-- resources/views/zonapublica/galeriareportajepublico.blade.php
    Vista para mostrar el listado de fotografías de un reportaje público en una galería
    incluyendo la vista parcial de la galería correspondiente.
--}}


@extends('layouts.app')

@push('styles')
@vite(['resources/css/paginas/galeria.css'])
@vite(['resources/css/paginas/vistafotosreportajes.css'])
@endpush

@section('content')


<div id="contenedor-principal-galeria">

    <h2 class="cabecera-repor">Coleccion: {{ $reportaje->descripcion ?? 'Sin descripción' }} (Código: {{ $reportaje->codigo }})</h2>
     
    <div id="contenedor-galeria-fotos">
        @include('parciales.paginadofotospublicas', ['fotografias' => $fotografias])
    </div>
</div>

<div class="redirector-publi-priv">
    @auth
    <a class="tw-enlace" href="{{ route('zonaprivada') }}">Ir a <b>Zona Privada</b></a>
    <p> | </p>
    @endauth
    <a class="tw-enlace" href="{{ route('zonapublica') }}">Ir a <b>Zona Pública</b></a>
</div>
@endsection