@extends('layouts.app')

@section('title', 'Crear Nuevo Formato')
@section('content')

<div class="formulario-estandar">
    <h2>Crear nuevo formato</h2>

    @if ($errors->any())
    <div class="listaerrores">
        <ul>
            @foreach ($errors->all() as $error)
            <li class="errorlinea">{{ $error }}</li>
            @endforeach
        </ul>
    </div>
    @endif

    <form method="POST" action="{{ route('nuevoformato') }}">
        @csrf
        <label for="nombre_format">Nombre del formato</label>
        <input type="text" id="nombre_format" name="nombre_format" value="{{ old('nombre_format') }}" required>

        <label for="ancho">Ancho (cm)</label>
        <input type="number" id="ancho" name="ancho" value="{{ old('ancho') }}" step="0.01" min="0" required>

        <label for="alto">Alto (cm)</label>
        <input type="number" id="alto" name="alto" value="{{ old('alto') }}" step="0.01" min="0" required>

        <input class="btn-estandar" type="submit" value="Crear formato">
        <a class="btn-estandar" href="{{ url()->previous() }}">Cancelar</a>
    </form>
</div>

<div class="redirector-publi-priv">
    @auth
    <a class="tw-enlace" href="{{ route('zonaprivada') }}">Ir a <b>Zona Privada</b></a>
    <p> | </p>
    @endauth
    <a class="tw-enlace" href="{{ route('zonapublica') }}">Ir a <b>Zona Pública</b></a>
</div>
@endsection