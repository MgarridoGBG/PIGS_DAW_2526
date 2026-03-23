@extends('layouts.app')
@push('styles')
@vite(['resources/css/paginas/confirmaciones.css'])
@endpush
@section('title', 'Confirmar renombrado de carpeta')
@section('content')

<div class="container pagina-confirmacion">
    <h2>Confirmar renombrado de carpeta</h2>

    <div class="listaerrores">
        <p class="textoerror"><strong>Reportaje:</strong> {{ $reportaje->codigo }}</p>
        <p class="textoerror"><strong>Nuevo codigo reportaje:</strong> {{ $codigoNuevo }}</p>
        <p class="textoerror"><strong>Codigo antiguo reportaje:</strong> {{ $codigoAntiguo }}</p>
        <p class="textoerror">La carpeta física '{{ $codigoAntiguo }}' existe en la carpeta de almacenamiento.</p>
        <p class="textoerror"><strong>¿Quiere renombrar también la carpeta {{ $codigoAntiguo }} a {{ $codigoNuevo }}?</strong></p>
    </div>

    <div class="opciones-confirmacion">
        <div>
            <form method="POST" action="{{ route('editarreportaje', ['id' => $reportaje->id]) }}" style="display: inline-block; margin-right: 10px;">
                @csrf
                @method('PUT')
                <input type="hidden" name="tipo" value="{{ $datosReportaje['tipo'] ?? $reportaje->tipo }}">
                <input type="hidden" name="codigo" value="{{ $datosReportaje['codigo'] }}">
                <input type="hidden" name="descripcion" value="{{ $datosReportaje['descripcion'] ?? $reportaje->descripcion }}">
                <input type="hidden" name="fecha_report" value="{{ $datosReportaje['fecha_report'] ?? $reportaje->fecha_report }}">
                <input type="hidden" name="user_id" value="{{ $datosReportaje['user_id'] ?? $reportaje->user_id }}">
                <input type="hidden" name="publico" value="{{ $datosReportaje['publico'] ?? $reportaje->publico }}">
                <input type="hidden" name="accion_carpeta" value="renombrar">
                <button type="submit" class="btn-estandar">Renombrar Carpeta y Entrada en BD</button>
            </form>
            <br>
            <form method="POST" action="{{ route('editarreportaje', ['id' => $reportaje->id]) }}" style="display: inline-block; margin-right: 10px;">
                @csrf
                @method('PUT')
                <input type="hidden" name="tipo" value="{{ $datosReportaje['tipo'] ?? $reportaje->tipo }}">
                <input type="hidden" name="codigo" value="{{ $datosReportaje['codigo'] }}">
                <input type="hidden" name="descripcion" value="{{ $datosReportaje['descripcion'] ?? $reportaje->descripcion }}">
                <input type="hidden" name="fecha_report" value="{{ $datosReportaje['fecha_report'] ?? $reportaje->fecha_report }}">
                <input type="hidden" name="user_id" value="{{ $datosReportaje['user_id'] ?? $reportaje->user_id }}">
                <input type="hidden" name="publico" value="{{ $datosReportaje['publico'] ?? $reportaje->publico }}">
                <input type="hidden" name="accion_carpeta" value="no_renombrar">
                <button type="submit" class="btn-estandar">Renombrar Entrada en BD sin Renombrar Carpeta</button>
            </form>
            <br>
            <form method="POST" action="{{ route('editarreportaje', ['id' => $reportaje->id]) }}" style="display: inline-block;">
                @csrf
                @method('PUT')
                <input type="hidden" name="tipo" value="{{ $datosReportaje['tipo'] ?? $reportaje->tipo }}">
                <input type="hidden" name="codigo" value="{{ $datosReportaje['codigo'] }}">
                <input type="hidden" name="descripcion" value="{{ $datosReportaje['descripcion'] ?? $reportaje->descripcion }}">
                <input type="hidden" name="fecha_report" value="{{ $datosReportaje['fecha_report'] ?? $reportaje->fecha_report }}">
                <input type="hidden" name="user_id" value="{{ $datosReportaje['user_id'] ?? $reportaje->user_id }}">
                <input type="hidden" name="publico" value="{{ $datosReportaje['publico'] ?? $reportaje->publico }}">
                <input type="hidden" name="accion_carpeta" value="cancelar">
                <button type="submit" class="btn-estandar">Cancelar</button>
            </form>
        </div>
    </div>
    <p class="enlace_confirmaciones"><a class="tw-enlace" href="{{ route('formeditarreportaje', ['id' => $reportaje->id]) }}">Volver al formulario</a></p>
</div>

@endsection