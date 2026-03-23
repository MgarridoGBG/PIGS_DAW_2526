@extends('layouts.app')
@section('title', 'Editar Pedido')
@section('content')

<div class="formulario-estandar">
    <h2>Editar Pedido</h2>

    @if ($errors->any())
    <div class="listaerrores">
        <ul>
            @foreach ($errors->all() as $error)
            <li class="errorlinea">{{ $error }}</li>
            @endforeach
        </ul>
    </div>
    @endif

    <form method="POST" action="{{ route('editarpedido', ['id' => $pedido->id ?? 0]) }}">
        @csrf
        @method('PUT')       

        <label for="email_usuario">Email del usuario propietario</label>
        <input type="email" id="email_usuario" name="email_usuario" value="{{ old('email_usuario', $pedido->user->email ?? '') }}">
    
        <label for="estado_pedido">Estado de pedido</label>
        <select id="estado_pedido" name="estado_pedido">
            <option value="">-- Seleccione un estado --</option>
            @foreach($estados as $estado)
            <option value="{{ $estado }}" {{ old('estado_pedido', $pedido->estado_pedido) == $estado ? 'selected' : '' }}>{{ ucfirst($estado) }}</option>
            @endforeach
        </select>
       
        <label for="fecha_pedido">Fecha del pedido</label>
        <input type="date" id="fecha_pedido" name="fecha_pedido" value="{{ old('fecha_pedido', $pedido->fecha_pedido) }}">
         
        <p style="margin-top: 10px">Para añadir items al pedido, hágalo a traves del carrito y pulse <strong> 'Editar pedido y añadir items'</strong> en esta página.</p>

        <input class="btn-estandar" type="submit" value="Editar pedido y añadir items">
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