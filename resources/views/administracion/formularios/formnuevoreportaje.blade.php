@extends('layouts.app')
@section('title', 'Añadir nuevo reportaje')
@section('content')

<div class="formulario-estandar">
    <h2>Añadir reportaje a base de datos</h2>

    @if ($errors->any())
    <div class="listaerrores">
        <ul>
            @foreach ($errors->all() as $error)
            <li class="errorlinea">{{ $error }}</li>
            @endforeach
        </ul>
    </div>
    @endif

    <form method="POST" action="{{ route('nuevoreportaje') }}">
        @csrf
        <label for="tipo">Tipo de reportaje</label>
        <select id="tipo" name="tipo" required>
            <option value="">-- Seleccione un tipo --</option>
            @foreach($tipos as $tipo)
            <option value="{{ $tipo }}" {{ old('tipo') == $tipo ? 'selected' : '' }}>{{ ucfirst($tipo) }}</option>
            @endforeach
        </select>

        <label for="codigo">Código</label>
        <input type="text" id="codigo" name="codigo" value="{{ old('codigo') }}" required>

        <label for="descripcion">Descripción</label>
        <textarea id="descripcion" name="descripcion" rows="3">{{ old('descripcion') }} </textarea>

        <label for="fecha_report">Fecha del reportaje</label>
        <input type="date" id="fecha_report" name="fecha_report" value="{{ old('fecha_report') }}" required>

        <label for="email_usuario">Email del usuario propietario</label>
        <input type="email" id="email_usuario" name="email_usuario" value="{{ old('email_usuario', $pedido->user->email ?? '') }}" required>

        <label for="publico">Público</label>
        <select id="publico" name="publico" required>
            <option value="0" {{ old('publico') == 0 ? 'selected' : '' }}>No</option>
            <option value="1" {{ old('publico') == 1 ? 'selected' : '' }}>Sí</option>
        </select>

        <input class="btn-estandar" type="submit" value="Crear reportaje">
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