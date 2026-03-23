@extends('layouts.app')
@section('title', 'Añadir nuevo soporte')
@section('content')

<div class="formulario-estandar">
    <h2>Crear nuevo soporte</h2>

    @if ($errors->any())
    <div class="listaerrores">
        <ul>
            @foreach ($errors->all() as $error)
            <li class="errorlinea">{{ $error }}</li>
            @endforeach
        </ul>
    </div>
    @endif

    <form method="POST" action="{{ route('nuevosoporte') }}">
        @csrf
        <label for="nombre_soport">Nombre</label>
        <input type="text" id="nombre_soport" name="nombre_soport" value="{{ old('nombre_soport') }}" required>

        <label for="disponibilidad">Disponibilidad</label>
        <select id="disponibilidad" name="disponibilidad" required>
            <option value="">-- Seleccione --</option>
            <option value="1" {{ old('disponibilidad') == 1 ? 'selected' : '' }}>Disponible</option>
            <option value="0" {{ old('disponibilidad') == 0 ? 'selected' : '' }}>No disponible</option>
        </select>

        <label for="precio">Precio</label>
        <input type="number" id="precio" name="precio" value="{{ old('precio') }}" step="0.01" min="0" required>

        <input class="btn-estandar" type="submit" value="Crear soporte">
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