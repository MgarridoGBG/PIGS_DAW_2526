@extends('layouts.app')
@push('styles')
@vite(['resources/css/paginas/confirmaciones.css'])
@endpush
@section('title', 'Confirmar registro de fotografía sin archivo')
@section('content')

<div class="container pagina-confirmacion">
    <h2>Confirmar registro de fotografía</h2>

    <div class="listaerrores">
        <p class="textoerror"><strong>Atención:</strong></p>
        <p class="textoerror">El archivo '<strong>{{ $nombre_foto }}</strong>' no existe en la carpeta de almacenamiento '<strong>{{ $reportaje->codigo }}</strong>'.</p>
        <p class="textoerror">¿Desea agregarla a la base de datos de todas formas?</p>
    </div>

    <div class="opciones-confirmacion">
        <div>
        <form method="POST" action="{{ route('nuevafotografia') }}" style="display: inline;">
            @csrf
            <input type="hidden" name="nombre_foto" value="{{ $datosFotografia['nombre_foto'] }}">
            <input type="hidden" name="reportaje_codigo" value="{{ $datosFotografia['reportaje_codigo'] }}">
            <input type="hidden" name="accion_archivo" value="agregar">
            <button type="submit" class="btn-estandar">Sí, agregar de todas formas</button>
        </form>

        <!-- Formulario para cancelar -->
        <form method="POST" action="{{ route('nuevafotografia') }}" style="display: inline;">
            @csrf
            <input type="hidden" name="nombre_foto" value="{{ $datosFotografia['nombre_foto'] }}">
            <input type="hidden" name="reportaje_codigo" value="{{ $datosFotografia['reportaje_codigo'] }}">
            <input type="hidden" name="accion_archivo" value="cancelar">
            <button type="submit" class="btn-estandar">Cancelar</button>
        </form>
        </div>
    </div>

    <div class="info-adicional">
        <p><em>Ruta esperada: storage/app/private/fotosreportajes/{{ $reportaje->codigo }}/{{ $nombre_foto }}</em></p>
    </div>

     <p class="enlace_confirmaciones"><a class="tw-enlace" href="{{ route('formnuevafotografia') }}">Volver al formulario</a></p>

</div>

@endsection
