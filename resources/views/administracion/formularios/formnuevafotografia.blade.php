@extends('layouts.app')
@section('title', 'Añadir nueva fotografía')
@section('content')

<div class="formulario-estandar">
    <h2>Añadir foto a base de datos</h2>

    @if ($errors->any())
    <div class="listaerrores">
        <ul>
            @foreach ($errors->all() as $error)
            <li class="errorlinea">{{ $error }}</li>
            @endforeach
        </ul>
    </div>
    @endif

    <form method="POST" action="{{ route('nuevafotografia') }}">
        @csrf

        <label for="nombre_foto">Nombre de la fotografía</label>
        <input type="text" id="nombre_foto" name="nombre_foto" value="{{ old('nombre_foto') }}" required placeholder="MGB0000.jpeg">

        <label for="reportaje_codigo">Código del reportaje</label>
        <input type="text" id="reportaje_codigo" name="reportaje_codigo" value="{{ old('reportaje_codigo') }}" required placeholder="00000000REPOR5000">

        <input class="btn-estandar" type="submit" value="Crear fotografía">
        <a class="btn-estandar" href="{{ route('zonaprivada') }}">Cancelar</a>
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