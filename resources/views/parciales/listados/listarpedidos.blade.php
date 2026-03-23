{{-- resources/views/parciales/listados/listarpedidos.blade.php
    Vista para mostrar el listado de pedidos.
--}}

@extends('layouts.app')

@push('styles')
@vite(['resources/css/paginas/listados.css'])
@endpush

@section('title', 'Lista de Pedidos')

@section('content')
<div class="listado-contenedor">
    <h2 class="titular-listado">Lista de Pedidos</h2>

    @if (isset($mensaje))
    <p class="mensaje-cuenta">{{$mensaje}}</p>
    @endif

    @if($pedidos->count() > 0)

    <div class="contenedor-tabla-scroll">
        <table class="tabla-estandar">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Estado</th>
                    <th>Fecha</th>
                    <th>Usuario ID</th>
                    <th>Usuario Email</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                @foreach($pedidos as $pedido)
                <tr>
                    <td>{{ $pedido->id }}</td>
                    <td>{{ $pedido->estado_pedido }}</td>
                    <td>{{ $pedido->fecha_pedido }}</td>
                    <td>{{ $pedido->user_id }}</td>
                    <td>{{ $pedido->user->email ?? 'N/A' }}</td>
                    <td class="acciones-listado">
                        <a class="btn-estandar" href="{{ route('verdetallepedido', $pedido->id) }}">Detalles</a>

                        <a class="btn-estandar" href="{{ route('formeditarpedido', $pedido->id) }}">Modificar</a>

                        <form class="form-listado" method="POST" action="{{ route('borrarpedido', $pedido->id) }}" onsubmit="return confirm('¿Desea eliminar este pedido?\nEsta acción no se puede deshacer.')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn-borrar">Borrar</button>
                        </form>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    {{-- Enlaces de paginación --}}
    @include('parciales.botonespaginas', ['objetos' => $pedidos])

    @else
    <p class="mensaje-cuenta">No hay pedidos registrados.</p>
    @endif

    <div class="redirector-publi-priv">
        @auth
        <a class="tw-enlace" href="{{ route('zonaprivada') }}">Ir a <b>Zona Privada</b></a>
        <p> | </p>
        @endauth
        <a class="tw-enlace" href="{{ route('zonapublica') }}">Ir a <b>Zona Pública</b></a>
    </div>

</div>
@endsection