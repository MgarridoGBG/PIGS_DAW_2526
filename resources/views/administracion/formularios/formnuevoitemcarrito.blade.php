@extends('layouts.app')
@section('title', 'Añadir Item al Carrito')

@section('content')

<div class="formulario-estandar">
<h2>Añadir Item al Carrito</h2>

<p>
    Fotografía: <strong>{{ $nombre_fotografia ?? 'ID ' . $fotografia_id }}</strong>  
        - Reportaje: <strong>{{ $reportaje->codigo }}</strong>   
</p>
<p>Seleccione el formato, soporte y cantidad para añadir esta fotografía al carrito:</p>
<br>

<form method="POST" action="{{ route('procesaritemcarrito') }}">
    @csrf
    
    <!-- Campo oculto con el ID de la fotografía -->
    <input type="hidden" name="fotografia_id" value="{{ $fotografia_id }}">
    
    <!-- Select para formatos -->
    <div style="margin-bottom: 15px;">
        <label for="formato">Formato:</label>
        <select name="formato" id="formato" required>
            <option value="">-- Seleccione un formato --</option>
            @foreach($formatos as $formato)
                <option value="{{ $formato }}">{{ $formato }}</option>
            @endforeach
        </select>
    </div>
    
    <!-- Select para soportes -->
    <div style="margin-bottom: 15px;">
        <label for="soporte">Soporte:</label>
        <select name="soporte" id="soporte" required>
            <option value="">-- Seleccione un soporte --</option>
            @foreach($soportes as $soporte)
                <option value="{{ $soporte->nombre_soport }}">{{ $soporte->nombre_soport }}</option>
            @endforeach
        </select>
    </div>
    
    
    <!-- Campo para cantidad -->
    <div style="margin-bottom: 15px;">
        <label for="cantidad">Cantidad:</label>
        <input type="number" name="cantidad" id="cantidad" min="1" value="1" step="1" required>
    </div>

    <!-- Botones -->
    <div>
        <button class="btn-estandar" type="submit">Añadir al Carrito</button>
        <button class="btn-estandar" type="button" onclick="window.close();">Cancelar</button>
    </div>
</form>
</div>

@endsection
