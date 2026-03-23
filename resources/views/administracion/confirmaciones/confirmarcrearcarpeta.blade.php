@extends('layouts.app')
@push('styles')
@vite(['resources/css/paginas/confirmaciones.css'])
@endpush
@section('title', 'Confirmar creación de carpeta')
@section('content')

<div class="container pagina-confirmacion">
    <h2>Confirmación requerida</h2>

    <div class="listaerrores">        
        <p class="textoerror"><strong>Atención:</strong></p>
        <p class="textoerror"><strong>No existe la carpeta "{{ $codigo }}" en el almacenamiento.</strong></p>
        <p class="textoerror">¿Desea crearla?</p>
    </div>

    <div class="opciones-confirmacion">
        <div>
            <form method="POST" action="{{ route('nuevoreportaje') }}">
                @csrf
                <input type="hidden" name="tipo" value="{{ $datosReportaje['tipo'] }}">
                <input type="hidden" name="codigo" value="{{ $datosReportaje['codigo'] }}">
                <input type="hidden" name="descripcion" value="{{ $datosReportaje['descripcion'] ?? '' }}">
                <input type="hidden" name="fecha_report" value="{{ $datosReportaje['fecha_report'] }}">
                <input type="hidden" name="email_usuario" value="{{ $datosReportaje['email_usuario'] }}">
                <input type="hidden" name="publico" value="{{ $datosReportaje['publico'] ?? 0 }}">
                <input type="hidden" name="accion_carpeta" value="crear">
                <button type="submit" class="btn-estandar">Sí, crear carpeta</button>
            </form>

            <form method="POST" action="{{ route('nuevoreportaje') }}">
                @csrf
                <input type="hidden" name="tipo" value="{{ $datosReportaje['tipo'] }}">
                <input type="hidden" name="codigo" value="{{ $datosReportaje['codigo'] }}">
                <input type="hidden" name="descripcion" value="{{ $datosReportaje['descripcion'] ?? '' }}">
                <input type="hidden" name="fecha_report" value="{{ $datosReportaje['fecha_report'] }}">
                <input type="hidden" name="email_usuario" value="{{ $datosReportaje['email_usuario'] }}">
                <input type="hidden" name="publico" value="{{ $datosReportaje['publico'] ?? 0 }}">
                <input type="hidden" name="accion_carpeta" value="no_crear">
                <button type="submit" class="btn-estandar">No, solo reportaje</button>
            </form>

            <form method="POST" action="{{ route('nuevoreportaje') }}">
                @csrf
                <input type="hidden" name="tipo" value="{{ $datosReportaje['tipo'] }}">
                <input type="hidden" name="codigo" value="{{ $datosReportaje['codigo'] }}">
                <input type="hidden" name="descripcion" value="{{ $datosReportaje['descripcion'] ?? '' }}">
                <input type="hidden" name="fecha_report" value="{{ $datosReportaje['fecha_report'] }}">
                <input type="hidden" name="email_usuario" value="{{ $datosReportaje['email_usuario'] }}">
                <input type="hidden" name="publico" value="{{ $datosReportaje['publico'] ?? 0 }}">
                <input type="hidden" name="accion_carpeta" value="cancelar">
                <button type="submit" class="btn-estandar">Cancelar</button>
            </form>
        </div>
    </div>

    <p class="enlace_confirmaciones"><a class="tw-enlace" href="{{ route('formnuevoreportaje') }}">Volver al formulario</a></p>
</div>

@endsection