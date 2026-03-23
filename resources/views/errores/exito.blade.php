@extends('layouts.app')

@push('scripts')
@vite(['resources/css/paginas/errores.css'])
@endpush

@section('title', 'EXITO')

@section('content')
<div class="pagina-error container">
    <img aria-label="Icono de éxito" class="pagina-error-icono" src="{{ Vite::asset('resources/images/exitoverde.png') }}" alt="Éxito">

    <h1 class="pagina-error-titulo titulo-verde">¡Todo correcto!</h1>
    <p class="pagina-error-mensaje">{!! nl2br(e($mensaje)) !!}</p>
    @if (isset($mensaje2))
    <p class="pagina-error-mensaje">{!! nl2br(e($mensaje2)) !!}</p>
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