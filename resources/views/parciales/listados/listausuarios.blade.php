{{-- resources/views/parciales/listados/listausuarios.blade.php
    Vista para mostrar el listado de usuarios.
--}}

@extends('layouts.app')

@push('styles')
@vite(['resources/css/paginas/listados.css'])
@endpush

@section('title', 'Lista de Usuarios')

@section('content')
<div class="listado-contenedor">
    <h2 class="titular-listado">Lista de Usuarios</h2>

    @if (isset($mensaje))
    <p class="mensaje-cuenta">{{$mensaje}}</p>
    @endif

    @if($usuarios->count() > 0)
    <div class="contenedor-tabla-scroll">
        <table class="tabla-estandar">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Email</th>
                    <th>Nombre</th>
                    <th>Apellidos</th>
                    <th>DNI</th>
                    <th>Role</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                @foreach($usuarios as $usuario)
                <tr>
                    <td>{{ $usuario->id }}</td>
                    <td>{{ $usuario->email }}</td>
                    <td>{{ $usuario->nombre }}</td>
                    <td>{{ $usuario->apellidos }}</td>
                    <td>{{ $usuario->dni }}</td>
                    <td>{{ $usuario->role->nombre_role ?? 'Sin role' }}</td>
                    <td class="acciones-listado">
                        <a class="btn-estandar" href="{{ route('formeditarusuario', $usuario->id) }}">Modificar</a>

                        <form class="form-listado" method="POST" action="{{ route('borrarusuario', $usuario->id) }}" onsubmit="return confirm('¿Desea eliminar este usuario?\nEsta acción no se puede deshacer.')">
                            @csrf
                            @method('DELETE')
                            <button class="btn-borrar" type="submit">Borrar</button>
                        </form>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    {{-- Enlaces de paginación --}}
    <!-- Incluimos la vista parcial para los botones de paginación personalizados-->
    @include('parciales.botonespaginas', ['objetos' => $usuarios])

    @else
    <p class="mensaje-cuenta">No hay usuarios registrados.</p>
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