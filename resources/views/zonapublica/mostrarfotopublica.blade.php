{{-- resources/views/zonapublica/mostrarfotopublica.blade.php
    Vista para mostrar el detalle de una fotografía pública, incluyendo la imagen en tamaño completo,
    la información de la colección al que pertenece, etiquetas y las opciones de gestión según
    los privilegios del usuario.
--}}

@extends('layouts.app')

@push('styles')
@vite(['resources/css/paginas/vistafotosreportajes.css'])
@endpush

@section('content')
<div class="contenedor-foto-publica">

    <h2 class="cabecera-repor">Foto: {{ $foto->nombre_foto }}</h2>

    @if($foto)
    <img aria-label="Foto de {{ $foto->nombre_foto }}" class="foto-principal" src="{{ route('fotopublicastorage', $foto->reportaje->codigo . '/' . $foto->nombre_foto) }}">
    <p class="foto-meta"><strong>Reportaje:</strong> {{ $foto->reportaje->descripcion }}</p>
    <p class="foto-meta"><strong>Código:</strong> {{ $foto->reportaje->codigo }}</p>

    @auth
    @if(Auth::user()->privilegios()->where('nombre_priv', 'admin_basico')->exists())
    <div class="panel-admin">
        <form class="form-renombrar" method="POST" action="{{ route('editarfotografia', $foto->id) }}">
            @csrf
            @method('PUT')
            <input type="text" name="nombre_foto" value="{{ $foto->nombre_foto }}" required>
            <button type="submit" class="btn-estandar">Renombrar</button>
        </form>
        <form method="POST" action="{{ route('borrarfotografia', $foto->id) }}" onsubmit="return confirm('¿Borrar esta foto?\nEsta acción no se puede deshacer.')">
            @csrf
            @method('DELETE')
            <button type="submit" class="btn-borrar">Borrar</button>
        </form>
    </div>

    <div class="panel-etiqueta">
        <form id="formEtiqueta" method="POST">
            @csrf
            @method('PUT')
            <input type="text" name="nombre_etiqueta" placeholder="nombre_etiqueta" required>
            <button type="submit" class="btn-estandar" formaction="{{ route('anadiretiquetafoto', $foto->id) }}" onclick="return confirm('¿Desea añadir esta etiqueta a la foto?')">Añadir etiqueta</button>
            <button type="submit" class="btn-estandar" formaction="{{ route('borraretiquetafoto', $foto->id) }}" onclick="return confirm('¿Desea quitar esta etiqueta de la foto?')">Quitar etiqueta</button>
        </form>
    </div>

    @endif
    @if(Auth::user()->privilegios()->where('nombre_priv', 'hacer_pedido')->exists())
    <div class="panel-carrito">
        <form method="POST" action="{{ route('mostrarformcarrito', $foto->id) }}" target="_blank">
            @csrf
            <button type="submit" class="btn-carrito">Añadir al carrito</button>
        </form>
    </div>
    @endif




    @endauth
    @guest
    <a class="tw-enlace-animado" href="{{ route('login') }}">Inicie sesión para hacer pedido</a>
    @endguest

    <div class="seccion-etiquetas">
        <p><strong>Etiquetas:</strong></p>
        @if($foto->etiquetas->isEmpty())
        <span>No hay etiquetas</span>
        @else
        <ul class="lista-etiquetas-foto">
            @foreach($foto->etiquetas as $etiqueta)
            <li>{{ $etiqueta->nombre_etiqueta }}</li>
            @endforeach
        </ul>
        @endif
    </div>

    @else
    <p>Foto no encontrada.</p>
    @endif

</div>
@endsection