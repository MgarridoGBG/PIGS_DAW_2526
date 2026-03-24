{{-- resources/views/parciales/listados/tablafotosfantasma.blade.php
    Vista parcial con la tabla de fotografías fantasma.
    Devuelta exclusivamente como respuesta AJAX desde buscarFotosFantasma().
--}}

@if (isset($mensaje))
<p class="mensaje-cuenta">{{ $mensaje }}</p>
@endif

@if($fotografias->count() > 0)
<div class="contenedor-tabla-scroll">
    <table class="tabla-estandar">
        <thead>
            <tr>
                <th>ID</th>
                <th>Nombre</th>
                <th>Reportaje</th>
                <th>Usuario Email</th>
                <th>Acción</th>
            </tr>
        </thead>
        <tbody>
            @foreach($fotografias as $fotografia)
            <tr>
                <td>{{ $fotografia->id }}</td>
                <td>{{ $fotografia->nombre_foto }}</td>
                <td>{{ $fotografia->reportaje->codigo }}</td>
                <td>{{ $fotografia->reportaje->user->email ?? 'N/A' }}</td>
                <td class="acciones-listado">
                    <form class="form-listado" method="POST" action="{{ route('borrarfotografia', $fotografia->id) }}" onsubmit="return confirm('¿Borrar esta foto?\nEsta acción no se puede deshacer.')">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn-borrar">Borrar</button>
                    </form>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>

{{-- Enlaces de paginación --}}
@include('parciales.botonespaginas', ['objetos' => $fotografias])

@else
<p class="mensaje-cuenta">No hay fotografías fantasma registradas.</p>
@endif
