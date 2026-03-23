@extends('layouts.app')
@push('styles')
@vite(['resources/css/paginas/confirmaciones.css'])
@endpush
@section('title', 'Confirmar eliminación de fotografía')
@section('content')

<div class="container pagina-confirmacion">
    <h2>Confirmar eliminación de fotografía</h2>

    <div class="listaerrores">
        <p class="textoerror"><strong>Atención:</strong></p>
        <p class="textoerror">¿Quiere eliminar el archivo físico '<strong>{{ $fotografia->nombre_foto }}</strong>' de la carpeta '<strong>{{ $reportaje->codigo }}</strong>'?</p>
    </div>

    <div class="info-adicional">
        <p><strong>Fotografía ID:</strong> {{ $fotografia->id }}</p>
        <p><strong>Nombre:</strong> {{ $fotografia->nombre_foto }}</p>
        <p><strong>Reportaje:</strong> {{ $reportaje->codigo }}</p>
        <p><strong>Ruta:</strong> storage/app/private/fotosreportajes/{{ $reportaje->codigo }}/{{ $fotografia->nombre_foto }}</p>
    </div>

    <div class="opciones-confirmacion">
        <div>
            <form method="POST" action="{{ route('borrarfotografia', $fotografia->id) }}">
                @csrf
                @method('DELETE')
                <input type="hidden" name="accion_archivo" value="borrar_archivo">
                <button type="submit" class="btn-estandar">Borrar Archivo y Foto</button>
            </form>

            <form method="POST" action="{{ route('borrarfotografia', $fotografia->id) }}">
                @csrf
                @method('DELETE')
                <input type="hidden" name="accion_archivo" value="conservar_archivo">
                <button type="submit" class="btn-estandar">Conservar Archivo y Borrar Foto</button>
            </form>

            <form method="POST" action="{{ route('borrarfotografia', $fotografia->id) }}">
                @csrf
                @method('DELETE')
                <input type="hidden" name="accion_archivo" value="cancelar">
                <button type="submit" class="btn-estandar">Cancelar</button>
            </form>
        </div>
    </div>

    <p class="enlace_confirmaciones"><a class="tw-enlace" href="{{ route('zonaprivada') }}">Volver a su zona privada</a></p>

</div>

@endsection