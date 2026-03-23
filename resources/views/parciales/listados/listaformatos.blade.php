{{-- resources/views/parciales/listados/listaformatos.blade.php
    Vista para mostrar el listado de formatos.
--}}

@extends('layouts.app')

@push('styles')
@vite(['resources/css/paginas/listados.css'])
@endpush

@section('title', 'Lista de Formatos')

@section('content')
<div class="listado-contenedor">
    <h2 class="titular-listado">Listado de Formatos</h2>

    @if (isset($mensaje))
    <p class="mensaje-cuenta">{{$mensaje}}</p>
    @endif

    @if($formatos->count() > 0)
    <div class="contenedor-tabla-scroll">
        <table class="tabla-estandar">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Nombre Formato</th>
                    <th>Ancho</th>
                    <th>Alto</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                @foreach($formatos as $formato)
                <tr>
                    <td>{{ $formato->id }}</td>
                    <td>{{ $formato->nombre_format }}</td>
                    <td>{{ $formato->ancho }}</td>
                    <td>{{ $formato->alto }}</td>
                    <td class="acciones-listado">
                        <a class="btn-estandar" href="{{ route('formeditarformato', $formato->id) }}">Modificar</a>

                        <form class="form-listado" method="POST" action="{{ route('borrarformato', $formato->id) }}" onsubmit="return confirm('¿Desea eliminar este formato?\nEsta acción no se puede deshacer.')">
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

    @include('parciales.botonespaginas', ['objetos' => $formatos])

    @else
    <p class="mensaje-cuenta">No hay formatos registrados.</p>
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