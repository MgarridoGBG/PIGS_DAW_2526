{{-- resources/views/parciales/listados/listacitas.blade.php
    Vista para mostrar el listado de citas.
--}}

@extends('layouts.app')

@push('styles')
@vite(['resources/css/paginas/listados.css'])
@endpush

@section('title', 'Listado de Citas')

@section('content')
<div class="listado-contenedor">
    <h2 class="titular-listado">Listado de Citas</h2>

    @if (isset($mensaje))
    <p class="mensaje-cuenta">{{$mensaje}}</p>
    @endif

    @if($citas->count() > 0)
    <div class="contenedor-tabla-scroll">
        <table class="tabla-estandar">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Email Usuario</th>
                    <th>Fecha</th>
                    <th>Turno</th>
                    <th>Estado</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                @foreach($citas as $cita)
                <tr>
                    <td>{{ $cita->id }}</td>
                    <td>{{ $cita->user->email }}</td>
                    <td>{{ $cita->fecha_cita }}</td>
                    <td>{{ $cita->turno }}</td>
                    <td>{{ $cita->estado_cita }}</td>
                    <td class="acciones-listado">
                        <a class="btn-estandar" href="{{ route('formeditarcita', $cita->id) }}">Modificar</a>

                        <form class="form-listado" method="POST" action="{{ route('borrarcita', $cita->id) }}" onsubmit="return confirm('¿Desea eliminar esta cita?\nEsta acción no se puede deshacer.')">
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
    @include('parciales.botonespaginas', ['objetos' => $citas])

    @else
    <p class="mensaje-cuenta">No hay citas registradas.</p>
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