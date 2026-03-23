@extends('layouts.app')

@section('title', 'Iniciar Sesión')


@section('content')
@auth
<br>
<h2>Ya has iniciado sesión</h2>
<div class="redirector-publi-priv">
    @auth
    <a class="tw-enlace" href="{{ route('zonaprivada') }}">Ir a <b>Zona Privada</b></a>
    <p> | </p>
    @endauth
    <a class="tw-enlace" href="{{ route('zonapublica') }}">Ir a <b>Zona Pública</b></a>
</div>

@endauth

@guest

<div class="formulario-estandar">
    <h2>Iniciar Sesión</h2>
    @if ($errors->any())
    <div class="listaerrores">
        <h2>ERROR:</h2>
        <ul>
            @foreach ($errors->all() as $error)
            <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
    @endif

    <!-- Formulario de inicio de sesión -->
    <form method="POST" action="{{ route('login') }}">
        @csrf
        <label for="email">Correo Electrónico:</label>
        <input type="email" id="email" name="email" value="{{ old('email') }}">
        <label for="password">Contraseña:</label>
        <input type="password" id="password" name="password">
        <input class="btn-carrito" type="submit" value="Login">
    </form>
    <br>

    <p class="tw-enlace"> <a href="../public">Volver a la zona pública</a></p>
</div>
@endguest

@endsection