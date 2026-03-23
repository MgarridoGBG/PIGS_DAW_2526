@extends('layouts.app')
@section('title', 'Editar Reportaje')
@section('content')

<div class="formulario-estandar">
    <h2>Editar Reportaje</h2>

    @if ($errors->any())
    <div class="listaerrores">
        <ul>
            @foreach ($errors->all() as $error)
            <li class="errorlinea">{{ $error }}</li>
            @endforeach
        </ul>
    </div>
    @endif

    <form method="POST" action="{{ route('editarreportaje', ['id' => $reportaje->id ?? 0]) }}">
        @csrf
        @method('PUT')
        
        <label for="email_usuario">Email del usuario propietario</label>
        <input type="email" id="email_usuario" name="email_usuario" value="{{ old('email_usuario', $pedido->user->email ?? '') }}">
      
        <label for="tipo">Tipo de reportaje</label>
        <select id="tipo" name="tipo">
            <option value="">-- Seleccione un tipo --</option>
            @foreach($tipos as $tipo)
            <option value="{{ $tipo }}" {{ old('tipo', $reportaje->tipo) == $tipo ? 'selected' : '' }}>{{ ucfirst($tipo) }}</option>
            @endforeach
        </select>
      
        <label for="codigo">Código</label>
        <input type="text" id="codigo" name="codigo" value="{{ old('codigo', $reportaje->codigo) }}">
       
        <label for="descripcion">Descripción</label>
        <textarea id="descripcion" name="descripcion" rows="3">{{ old('descripcion', $reportaje->descripcion) }}</textarea>
       
        <label for="fecha_report">Fecha del reportaje</label>
        <input type="date" id="fecha_report" name="fecha_report" value="{{ old('fecha_report', $reportaje->fecha_report) }}">
            

        <label for="publico">Público</label>
        <select id="publico" name="publico">
            <option value="0" {{ old('publico', $reportaje->publico) == 0 ? 'selected' : '' }}>No</option>
            <option value="1" {{ old('publico', $reportaje->publico) == 1 ? 'selected' : '' }}>Sí</option>
        </select>
     
        <input class="btn-estandar" type="submit" value="Editar reportaje">
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
