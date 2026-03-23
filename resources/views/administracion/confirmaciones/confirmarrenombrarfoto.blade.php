@extends('layouts.app')
@push('styles')
@vite(['resources/css/paginas/confirmaciones.css'])
@endpush

@section('title', 'Confirmar renombrado de fotografía')

@section('content')
<div class="container pagina-confirmacion">
    <h2>Confirmar Renombrado de Fotografía</h2>

    <div class="listaerrores">
        <p class="textoerror"><strong>Fotografía:</strong> {{ $fotografia->nombre_foto }}</p>
        <p class="textoerror"><strong>Nuevo nombre:</strong> {{ $nombreNuevo }}</p>
        <p class="textoerror"><strong>Reportaje:</strong> {{ $reportaje->codigo }}</p>
        <p class="textoerror">El archivo físico '{{ $fotografia->nombre_foto }}' existe en la carpeta de almacenamiento.</p>
        <p class="textoerror"><strong>¿Quiere renombrar también el archivo {{ $fotografia->nombre_foto }} de la carpeta {{ $reportaje->codigo }}?</strong></p>
    </div>

    <div class="opciones-confirmacion">
        <div>
            <form method="POST" action="{{ route('editarfotografia', $fotografia->id) }}">
                @csrf
                @method('PUT')
                <input type="hidden" name="nombre_foto" value="{{ $nombreNuevo }}">
                <input type="hidden" name="accion_archivo" value="renombrar">
                <button type="submit" class="btn-estandar">Renombrar Archivo y Entrada en BD</button>
            </form>
            <br>
            <form method="POST" action="{{ route('editarfotografia', $fotografia->id) }}">
                @csrf
                @method('PUT')
                <input type="hidden" name="nombre_foto" value="{{ $nombreNuevo }}">
                <input type="hidden" name="accion_archivo" value="no_renombrar">
                <button type="submit" class="btn-estandar">Renombrar Entrada en BD sin Renombrar Archivo</button>
            </form>
            <br>

            <form method="POST" action="{{ route('editarfotografia', $fotografia->id) }}">
                @csrf
                @method('PUT')
                <input type="hidden" name="nombre_foto" value="{{ $nombreNuevo }}">
                <input type="hidden" name="accion_archivo" value="cancelar">
                <button type="submit" class="btn-estandar">Cancelar</button>
            </form>
        </div>
    </div>

    <p class="enlace_confirmaciones">
        <a class="tw-enlace" href="{{ route('reportajefotos', $reportaje->id) }}">Volver a la galería</a>
    </p>
</div>
@endsection