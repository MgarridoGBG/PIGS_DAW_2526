@extends('layouts.app')
@section('title', 'Editar Usuario')
@section('content')

<div class="formulario-estandar">
    <h2>{{ isset($usuario) ? 'Editar usuario' : 'Crear nuevo usuario' }}</h2>

    @if ($errors->any())
    <div class="listaerrores">
        <ul>
            @foreach ($errors->all() as $error)
            <li class="errorlinea">{{ $error }}</li>
            @endforeach
        </ul>
    </div>
    @endif

    <form method="POST" action="{{ route('editarusuario', ['id' => $usuario->id]) }}">
        @csrf
        @method('PUT')
        <label for="nombre">Nombre</label>
        <input type="text" id="nombre" name="nombre" value="{{ old('nombre', $usuario->nombre ?? '') }}">


        <label for="email">Email</label>
        <input type="email" id="email" name="email" value="{{ old('email', $usuario->email ?? '') }}">


        <label for="apellidos">Apellidos</label>
        <input type="text" id="apellidos" name="apellidos" value="{{ old('apellidos', $usuario->apellidos ?? '') }}">


        <label for="telefono">Teléfono</label>
        <input type="text" id="telefono" name="telefono" value="{{ old('telefono', $usuario->telefono ?? '') }}">


        <label for="direccion">Dirección</label>
        <input type="text" id="direccion" name="direccion" value="{{ old('direccion', $usuario->direccion ?? '') }}">


        <label for="dni">DNI</label>
        <input type="text" id="dni" name="dni" value="{{ old('dni', $usuario->dni ?? '') }}">


        <label for="password">Nueva Contraseña (opcional)</label>
        <input type="password" id="password" name="password">


        <label for="password_confirmation">Confirmar nueva contraseña</label>
        <input type="password" id="password_confirmation" name="password_confirmation">

        <label for="role">Rol</label>
        <select name="role" id="role">
            @foreach($roles as $role)
            <option value="{{ $role }}" {{ (old('role', optional($usuario->role)->nombre_role ?? '') == $role) ? 'selected' : '' }}>{{ ucfirst($role) }}</option>
            @endforeach
        </select>


        <input class="btn-estandar" type="submit" value="Editar usuario">
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