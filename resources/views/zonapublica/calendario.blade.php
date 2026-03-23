{{-- resources/views/zonapublica/calendario.blade.php
    Vista para mostrar el calendario de citas.
    Renderiza un calendario interactivo usando FullCalendar (con estilos y scripts cargados desde Vite).
--}}

@extends('layouts.app')

@section('title', 'Citas')

@push('styles')
<!-- 
Lo guardo en caso de tener que usar CDN en vezde VITE para los estilos
<link href="https://cdn.jsdelivr.net/npm/@fullcalendar/core@6.1.20/index.global.min.css" rel="stylesheet">
<link href="https://cdn.jsdelivr.net/npm/@fullcalendar/daygrid@6.1.20/index.global.min.css" rel="stylesheet">
-->
@vite(['resources/js/paginas/calendario.js', 'resources/css/paginas/calendario.css'])
@endpush

@section('content')


<h2>Gestión de Cita</h2>
<p>Haz clic en un día para reservar o modificar su cita.</p>
<div class="separador-lista"></div>


<div id="calendario"></div> <!-- Aquí se renderiza el calendario desde resources/js/paginas/calendario.js-->

@auth
    @if(auth()->user() && auth()->user()->cita)
        <form method="POST" action="{{ route('borrarcitapropia') }}" onsubmit="return confirm('¿Desea cancelar su cita? ')">
            @csrf
            @method('DELETE')
            <button class="btn-borrar" type="submit">Cancelar mi cita</button>
        </form>
    @endif
@endauth

<div class="redirector-publi-priv">
    @auth
    <a class="tw-enlace" href="{{ route('zonaprivada') }}">Ir a <b>Zona Privada</b></a>
    <p> | </p>
    @endauth
    <a class="tw-enlace" href="{{ route('zonapublica') }}">Ir a <b>Zona Pública</b></a>
</div>
@endsection