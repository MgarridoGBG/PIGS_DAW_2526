@extends('layouts.app')
@section('title', 'Confirmar eliminación de carpeta')
@push('styles')
@vite(['resources/css/paginas/confirmaciones.css'])
@endpush
@section('content')

<div class="container pagina-confirmacion">
    <h2>Confirmación requerida</h2>

    <div class="listaerrores">
        <p class="textoerror"><strong>Atención:</strong></p>
        <p class="textoerror"><strong>¿Desea eliminar también la carpeta de almacenamiento del reportaje "{{ $codigo }}" y todas sus fotos en el servidor?</strong></p>
        <p class="textoerror">Esta opción no se puede deshacer.</p>
    </div>

    <div class="info-adicional">
        <p><strong>Reportaje ID:</strong> {{ $reportaje->id }}</p>
        <p><strong>Código:</strong> {{ $reportaje->codigo }}</p>
        <p><strong>Ruta:</strong> storage/app/private/fotosreportajes/{{ $reportaje->codigo }}/</p>
    </div>

    <div class="opciones-confirmacion">
        <div>
        <form method="POST" action="{{ route('borrarreportaje', ['id' => $reportaje->id]) }}" >
            @csrf
            @method('DELETE')
            <input type="hidden" name="accion_carpeta" value="eliminar_carpeta">
            <button type="submit" class="btn-estandar" onclick="return confirm('¿Está completamente seguro? Esta acción eliminará todas las fotos del reportaje de forma permanente.')">Sí, eliminar carpeta y reportaje</button>
        </form>

        <form method="POST" action="{{ route('borrarreportaje', ['id' => $reportaje->id]) }}" >
            @csrf
            @method('DELETE')
            <input type="hidden" name="accion_carpeta" value="no_eliminar">
            <button type="submit" class="btn-estandar">No, solo eliminar reportaje</button>
        </form>

        <form method="POST" action="{{ route('borrarreportaje', ['id' => $reportaje->id]) }}">
            @csrf
            @method('DELETE')
            <input type="hidden" name="accion_carpeta" value="cancelar">
            <button type="submit" class="btn-estandar">Cancelar</button>
        </form>
        </div>
    </div>
    
    <p class="enlace_confirmaciones"><a class="tw-enlace" href="{{ route('listarreportajes') }}">Volver a la lista de reportajes</a></p>
</div>

@endsection