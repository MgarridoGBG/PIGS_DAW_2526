{{-- resources/views/parciales/listados/listarfotografiasfantasma.blade.php
    Vista para mostrar el listado de fotografías fantasma, esta vez en modo texto no como galería
    pues estas fotos no tiene imagen asociada.
--}}

@extends('layouts.app')

@push('styles')
@vite(['resources/css/paginas/galeria.css'])
@vite(['resources/css/paginas/listados.css'])
@endpush

@section('title', 'Lista de Fotografías Fantasma')

@section('content')
<div class="listado-contenedor">
    <h2 class="titular-listado">Lista de Fotografías Fantasma</h2>

    @if (isset($mensaje))
    <p class="mensaje-cuenta">{{$mensaje}}</p>
    @endif

    @if($fotografias->count() > 0)
    <div class="contenedor-tabla-scroll">
        <table class="tabla-estandar">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Nombre</th>
                    <th>Reportaje</th>
                    <th>Usuario Email</th>
                    <th>Acción</th>
                </tr>
            </thead>
            <tbody>
                @foreach($fotografias as $fotografia)
                <tr>
                    <td>{{ $fotografia->id }}</td>
                    <td>{{ $fotografia->nombre_foto }}</td>
                    <td>{{ $fotografia->reportaje->codigo }}</td>
                    <td>{{ $fotografia->reportaje->user->email ?? 'N/A' }}</td>
                    <td class="acciones-listado">
                        <form class="form-listado" method="POST" action="{{ route('borrarfotografia', $fotografia->id) }}" onsubmit="return confirm('¿Borrar esta foto?\nEsta acción no se puede deshacer.')">
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
    @include('parciales.botonespaginas', ['objetos' => $fotografias])

    @else
    <p class="mensaje-cuenta">No hay fotografías registradas.</p>
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