{{-- resources/views/parciales/listados/listarreportajes.blade.php
    Vista para mostrar el listado de reportajes.
--}}

@extends('layouts.app')

@push('styles')
@vite(['resources/css/paginas/listados.css'])
@endpush

@section('title', 'Lista de Reportajes')

@section('content')
<div class="listado-contenedor">
    <h2 class="titular-listado">Listado de Reportajes</h2>

    @if (isset($mensaje))
    <p class="mensaje-cuenta">{{$mensaje}}</p>
    @endif

    @if($reportajes->count() > 0)
    <div class="contenedor-tabla-scroll">
        <table class="tabla-estandar">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Tipo</th>
                    <th>Código</th>
                    <th>Descripción</th>
                    <th>Fecha</th>
                    <th>User ID</th>
                    <th>User Email</th>
                    <th>Público</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                @foreach($reportajes as $reportaje)
                <tr>
                    <td>{{ $reportaje->id }}</td>
                    <td>{{ $reportaje->tipo }}</td>
                    <td>{{ $reportaje->codigo }}</td>
                    <td>{{ Str::limit($reportaje->descripcion, 20) }}</td>
                    <td>{{ $reportaje->fecha_report }}</td>
                    <td>{{ $reportaje->user_id }}</td>
                    <td>{{ $reportaje->user->email ?? 'N/A' }}</td>
                    <td>{{ $reportaje->publico ? 'Sí' : 'No' }}</td>
                    <td class="acciones-listado">
                        <a class="btn-estandar" href="{{ route('reportajefotos', $reportaje->id) }}">Fotos</a>

                        <a class="btn-estandar" href="{{ route('formeditarreportaje', $reportaje->id) }}">Modificar</a>

                        <form class="form-listado" method="POST" action="{{ route('borrarreportaje', $reportaje->id) }}" onsubmit="return confirm('¿Desea eliminar este reportaje?\nEsta acción no se puede deshacer.')">
                            @csrf
                            @method('DELETE')
                            <button class="btn-borrar" type="submit">borrar</button>
                        </form>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    {{-- Enlaces de paginación --}}
    @include('parciales.botonespaginas', ['objetos' => $reportajes])

    @else
    <p>No hay reportajes registrados.</p>
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