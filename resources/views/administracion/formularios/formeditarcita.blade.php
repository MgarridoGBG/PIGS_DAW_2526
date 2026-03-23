@extends('layouts.app')

@section('title', 'Editar Cita')
@section('content')

<div class="formulario-estandar">
    <h2>Editar Cita</h2>

    @if ($errors->any())
    <div class="listaerrores">
        <ul>
            @foreach ($errors->all() as $error)
            <li class="errorlinea">{{ $error }}</li>
            @endforeach
        </ul>
    </div>
    @endif

    <form method="POST" action="{{ route('editarcita', ['id' => $cita->id ?? 0]) }}">
        @csrf
        @method('PUT')

        <label for="email_usuario">Email del usuario</label>
        <input type="email" id="email_usuario" name="email_usuario" value="{{ old('email_usuario', $cita->user->email ?? '') }}">
       

        <label for="turno">Turno</label>
        <select id="turno" name="turno">
            <option value="">-- Seleccione un turno --</option>
            @foreach($turnos as $turno)
            <option value="{{ $turno }}" {{ old('turno', $cita->turno) == $turno ? 'selected' : '' }}>{{ ucfirst($turno) }}</option>
            @endforeach
        </select>     
        
        <label for="fecha_cita">Fecha</label>
        <input type="date" id="fecha_cita" name="fecha_cita" value="{{ old('fecha_cita', $cita->fecha_cita) }}">      

        <label for="estado_cita">Estado</label>
        <select id="estado_cita" name="estado_cita">
            <option value="">-- Seleccione un estado --</option>
            @foreach($estados as $estado)
            <option value="{{ $estado }}" {{ old('estado_cita', $cita->estado_cita) == $estado ? 'selected' : '' }}>{{ ucfirst($estado) }}</option>
            @endforeach
        </select>
     
        <input class="btn-estandar" type="submit" value="Editar cita">
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