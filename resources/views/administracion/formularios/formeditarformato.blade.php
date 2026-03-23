@extends('layouts.app')

@section('title', 'Editar Formato')
@section('content')


<div class="formulario-estandar">
    <h2>{{ isset($formato) ? 'Editar formato' : 'Crear nuevo formato' }}</h2>

    @if ($errors->any())
    <div class="listaerrores">
        <ul>
            @foreach ($errors->all() as $error)
            <li class="errorlinea">{{ $error }}</li>
            @endforeach
        </ul>
    </div>
    @endif

    <form method="POST" action="{{ route('editarformato', ['id' => $formato->id ?? 0]) }}">
        @csrf
        @method('PUT')

        <label for="nombre_format">Nombre del Formato</label>
        <input type="text" id="nombre_format" name="nombre_format" value="{{ old('nombre_format', $formato->nombre_format ?? '') }}">
      
        <label for="ancho">Ancho (cm)</label>
        <input type="number" step="any" id="ancho" name="ancho" value="{{ old('ancho', $formato->ancho ?? '') }}">
      
        <label for="alto">Alto (cm)</label>
        <input type="number" step="any" id="alto" name="alto" value="{{ old('alto', $formato->alto ?? '') }}">
      
        <input class="btn-estandar" type="submit" value="Editar formato">
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
