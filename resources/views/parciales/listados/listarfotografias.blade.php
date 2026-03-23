{{-- resources/views/parciales/listados/listarfotografias.blade.php
    Vista para mostrar el listado de fotografías. Inluye la vista de galería de fotos
    (parciales/paginadofotos.blade.php) para mostrar las fotos en formato galería.
--}}

@extends('layouts.app')

@section('title', 'Lista de Fotografías')

@push('styles') 
@vite(['resources/css/paginas/galeria.css'])
@vite(['resources/css/paginas/listados.css'])
@endpush

@section('content')
<div class="listado-contenedor">
    <h2 class="titular-listado">Lista de Fotografías</h2>
    
    @if (isset($mensaje))
    <p  class="mensaje-cuenta">{{$mensaje}}</p>
    @endif

    @if($fotografias->count() > 0)
    {{-- Incluir la vista de galería de fotos --}}
    @include('parciales.paginadofotos', ['fotografias' => $fotografias])

    <br>

    @else
    <p  class="mensaje-cuenta">No hay fotografías registradas.</p>
    @endif  
    
    <div class="redirector-publi-priv">
        @auth
        <a class="tw-enlace" href="{{ route('zonaprivada') }}">Ir a <b>Zona Privada</b></a>
        <p> | </p>
        @endauth
        <a class="tw-enlace" href="{{ route('zonapublica') }}">Ir a <b>Zona Pública</b></a>
    </div>
</div>
@endsection