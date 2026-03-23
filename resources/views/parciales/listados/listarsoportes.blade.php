{{-- resources/views/parciales/listados/listarsoportes.blade.php
    Vista para mostrar el listado de soportes.
--}}

@extends('layouts.app')

@push('styles')
@vite(['resources/css/paginas/listados.css'])
@endpush

@section('title', 'Lista de Soportes')

@section('content')
<div class="listado-contenedor">
    <h2 class="titular-listado">Lista de Soportes</h2>

    @if (isset($mensaje))
    <p class="mensaje-cuenta">{{$mensaje}}</p>
    @endif

    @if($soportes->count() > 0)
    <div class="contenedor-tabla-scroll">
        <table class="tabla-estandar">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Nombre</th>
                    <th>Disponibilidad</th>
                    <th>Precio</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                @foreach($soportes as $soporte)
                <tr>
                    <td>{{ $soporte->id }}</td>
                    <td>{{ $soporte->nombre_soport }}</td>
                    <td>{{ $soporte->disponibilidad ? 'Disponible' : 'No disponible' }}</td>
                    <td>{{ number_format($soporte->precio, 2) }}</td>
                    <td class="acciones-listado">
                        <a class="btn-estandar" href="{{ route('formeditarsoporte', $soporte->id) }}">Modificar</a>

                        <form class="form-listado" method="POST" action="{{ route('borrarsoporte', $soporte->id) }}" style="display:inline;" onsubmit="return confirm('¿Desea eliminar este soporte?\nEsta acción no se puede deshacer.')">
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
    @include('parciales.botonespaginas', ['objetos' => $soportes])

    @else
    <p class="mensaje-cuenta">No hay soportes registrados.</p>
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