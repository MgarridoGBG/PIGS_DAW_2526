@extends('layouts.app')
@section('title', 'Editar Soporte')
@section('content')

<div class="formulario-estandar">
    <h2>Editar Soporte</h2>

    @if ($errors->any())
    <div class="listaerrores">
        <ul>
            @foreach ($errors->all() as $error)
            <li class="errorlinea">{{ $error }}</li>
            @endforeach
        </ul>
    </div>
    @endif

    <form method="POST" action="{{ route('editarsoporte', ['id' => $soporte->id ?? 0]) }}">
        @csrf
        @method('PUT')

        <label for="nombre_soport">Nombre</label>
        <input type="text" id="nombre_soport" name="nombre_soport" value="{{ old('nombre_soport', $soporte->nombre_soport) }}">

        <label for="disponibilidad">Disponibilidad</label>
        <select id="disponibilidad" name="disponibilidad">
            <option value="">-- Seleccione --</option>
            <option value="1" {{ old('disponibilidad', $soporte->disponibilidad) == 1 ? 'selected' : '' }}>Disponible</option>
            <option value="0" {{ old('disponibilidad', $soporte->disponibilidad) == 0 ? 'selected' : '' }}>No disponible</option>
        </select>

        <label for="precio">Precio</label>
        <input type="number" step="0.01" min="0" id="precio" name="precio" value="{{ old('precio', $soporte->precio) }}">

        <input class="btn-estandar" type="submit" value="Editar soporte">
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