@extends('layouts.app')
@push('styles')
@vite(['resources/css/paginas/confirmaciones.css'])
@endpush
@section('title', 'Confirmar Añadir Fotografías')
@section('content')

<div class="container pagina-confirmacion">
    <h2>Confirmar Añadir Fotografías</h2>

    <div class="listaerrores">
        <p class="textoerror"><strong>Atención:</strong></p>
        <p class="textoerror">Se ha encontrado una carpeta existente para el código '<strong>{{ $codigo }}</strong>' con <strong>{{ $cantidadFotos }}</strong> fotografía(s).</p>
        <p class="textoerror">¿Desea añadir todas estas fotografías al nuevo reportaje?</p>
    </div>

    <div class="fotos-encontradas">
        <h4 style="font-weight: bold;">Fotografías encontradas:</h4>
        <ul>
            @foreach($fotos as $foto)
            <li>{{ $foto }}</li>
            @endforeach
        </ul>
    </div>

    <div class="opciones-confirmacion">
        <div>
            <form method="POST" action="{{ route('nuevoreportaje') }}" style="display: inline;">
                @csrf
                <input type="hidden" name="tipo" value="{{ $datosReportaje['tipo'] }}">
                <input type="hidden" name="codigo" value="{{ $datosReportaje['codigo'] }}">
                <input type="hidden" name="descripcion" value="{{ $datosReportaje['descripcion'] ?? '' }}">
                <input type="hidden" name="fecha_report" value="{{ $datosReportaje['fecha_report'] }}">
                <input type="hidden" name="email_usuario" value="{{ $datosReportaje['email_usuario'] }}">
                <input type="hidden" name="publico" value="{{ $datosReportaje['publico'] ?? 0 }}">
                <input type="hidden" name="accion_fotos" value="si">
                <button type="submit" class="btn-estandar">Sí, añadir todas las fotos</button>
            </form>

            <!-- Formulario para no añadir las fotos -->
            <form method="POST" action="{{ route('nuevoreportaje') }}" style="display: inline;">
                @csrf
                <input type="hidden" name="tipo" value="{{ $datosReportaje['tipo'] }}">
                <input type="hidden" name="codigo" value="{{ $datosReportaje['codigo'] }}">
                <input type="hidden" name="descripcion" value="{{ $datosReportaje['descripcion'] ?? '' }}">
                <input type="hidden" name="fecha_report" value="{{ $datosReportaje['fecha_report'] }}">
                <input type="hidden" name="email_usuario" value="{{ $datosReportaje['email_usuario'] }}">
                <input type="hidden" name="publico" value="{{ $datosReportaje['publico'] ?? 0 }}">
                <input type="hidden" name="accion_fotos" value="no">
                <button type="submit" class="btn-estandar">No, crear reportaje sin fotos</button>
            </form>

            <!-- Formulario para cancelar -->
            <form method="POST" action="{{ route('nuevoreportaje') }}" style="display: inline;">
                @csrf
                <input type="hidden" name="tipo" value="{{ $datosReportaje['tipo'] }}">
                <input type="hidden" name="codigo" value="{{ $datosReportaje['codigo'] }}">
                <input type="hidden" name="descripcion" value="{{ $datosReportaje['descripcion'] ?? '' }}">
                <input type="hidden" name="fecha_report" value="{{ $datosReportaje['fecha_report'] }}">
                <input type="hidden" name="email_usuario" value="{{ $datosReportaje['email_usuario'] }}">
                <input type="hidden" name="publico" value="{{ $datosReportaje['publico'] ?? 0 }}">
                <input type="hidden" name="accion_fotos" value="cancelar">
                <button type="submit" class="btn-estandar">Cancelar</button>
            </form>
        </div>
    </div>

    <div class="info-adicional">
        <p><em>Ruta de la carpeta: storage/app/private/fotosreportajes/{{ $codigo }}</em></p>
    </div>

    <p class="enlace_confirmaciones"><a class="tw-enlace" href="{{ route('formnuevoreportaje') }}">Volver al formulario</a></p>

</div>

@endsection