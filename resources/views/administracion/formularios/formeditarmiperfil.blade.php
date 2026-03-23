@extends('layouts.app')

@section('title', 'Editar Mi Perfil')

@section('content')

<div class="formulario-estandar">
    <h2>{{ isset($usuario) ? 'Editar miperfil' : '' }}</h2>

    @if ($errors->any())
    <div class="listaerrores">
        <ul>
            @foreach ($errors->all() as $error)
            <li class="errorlinea">{{ $error }}</li>
            @endforeach
        </ul>
    </div>
    @endif

    <form method="POST" action="{{ route('editarmiperfil', ['id' => $usuario->id]) }}">
        @csrf
        @method('PATCH')

        <label for="password_previa">Introduzca su contraseña actual</label>
        <input type="password" id="password_previa" name="password_previa">
      
        <label for="nombre">Nombre</label>
        <input type="text" id="nombre" name="nombre" value="{{ old('nombre', $usuario->nombre ?? '') }}">
      
        <label for="apellidos">Apellidos</label>
        <input type="text" id="apellidos" name="apellidos" value="{{ old('apellidos', $usuario->apellidos ?? '') }}">
      
        <label for="direccion">Dirección</label>
        <input type="text" id="direccion" name="direccion" value="{{ old('direccion', $usuario->direccion ?? '') }}">
      
        <label for="email">Email / Nombre de usuario</label>
        <input type="email" id="email" name="email" value="{{ old('email', $usuario->email ?? '') }}">
      
        <label for="telefono">Teléfono</label>
        <input type="text" id="telefono" name="telefono" value="{{ old('telefono', $usuario->telefono ?? '') }}">
       
        <label for="password">Nueva Contraseña (opcional)</label>
        <input type="password" id="password" name="password">
       
        <label for="password_confirmation">Confirmar nueva contraseña</label>
        <input type="password" id="password_confirmation" name="password_confirmation">
       
        <input class="btn-estandar" type="submit" value="Editar mi perfil">
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