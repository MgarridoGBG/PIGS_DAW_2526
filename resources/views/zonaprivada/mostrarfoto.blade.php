 {{-- resources/views/zonaprivada/mostrarfoto.blade.php
    Vista para mostrar el detalle de una fotografía privada, incluyendo la imagen en tamaño completo,
    la información del reportaje al que pertenece y las opciones de gestión según los privilegios del usuario.
--}}

@extends('layouts.app')

@push('styles')
@vite(['resources/css/paginas/vistafotosreportajes.css'])
@endpush

@section('content')
<div class="contenedor-foto-publica">

<h2 class="cabecera-repor">Foto: {{ $foto->nombre_foto }}</h2>

@if($foto)
<img aria-label="Foto de {{ $foto->nombre_foto }}" class="foto-principal" src="{{ route('fotostorage', $foto->reportaje->codigo . '/' . $foto->nombre_foto) }}">
<p class="foto-meta"><strong>Reportaje:</strong> {{ $foto->reportaje->descripcion }}</p>
<p class="foto-meta"><strong>Código:</strong> {{ $foto->reportaje->codigo }}</p>

@auth
@if(Auth::user()->privilegios()->where('nombre_priv', 'admin_basico')->exists())
<div class="panel-admin">
    <form  class="form-renombrar"  method="POST" action="{{ route('editarfotografia', $foto->id) }}" style="display:inline;">
        @csrf
        @method('PUT')
        <input type="text" name="nombre_foto" value="{{ $foto->nombre_foto }}" style="width:150px;" required>
        <button type="submit" class="btn-estandar">Renombrar</button>
    </form>   
    <form method="POST" action="{{ route('borrarfotografia', $foto->id) }}" style="display:inline;" onsubmit="return confirm('¿Borrar esta foto?\nEsta acción no se puede deshacer.')">
        @csrf
        @method('DELETE')
        <button type="submit" class="btn-borrar">Borrar</button>
    </form>
</div>


@endif
@if(Auth::user()->privilegios()->where('nombre_priv', 'hacer_pedido')->exists())
<div class="panel-carrito">
<form method="POST" action="{{ route('mostrarformcarrito', $foto->id) }}" style="display:inline;"  target="_blank" rel="noopener noreferrer">
    @csrf
    <button type="submit" class="btn-carrito">Añadir al carrito</button>
</form>
</div>
@endif
@endauth


@else
<p>Foto no encontrada.</p>
@endif
</div>
@endsection