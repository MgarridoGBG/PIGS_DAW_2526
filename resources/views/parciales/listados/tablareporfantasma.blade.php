{{-- resources/views/parciales/listados/tablareporfantasma.blade.php
    Vista parcial con la tabla de reportajes fantasma.
    Devuelta exclusivamente como respuesta AJAX desde buscarReportajesFantasma().
--}}

@if (isset($mensaje))
<p class="mensaje-cuenta">{{ $mensaje }}</p>
@endif

@if($reportajes->count() > 0)
<div class="contenedor-tabla-scroll">
    <table class="tabla-estandar">
        <thead>
            <tr>
                <th>ID</th>
                <th>Tipo</th>
                <th>Código</th>
                <th>Descripción</th>
                <th>Fecha</th>
                <th>User ID</th>
                <th>User Email</th>
                <th>Público</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody>
            @foreach($reportajes as $reportaje)
            <tr>
                <td>{{ $reportaje->id }}</td>
                <td>{{ $reportaje->tipo }}</td>
                <td>{{ $reportaje->codigo }}</td>
                <td>{{ Str::limit($reportaje->descripcion, 20) }}</td>
                <td>{{ $reportaje->fecha_report }}</td>
                <td>{{ $reportaje->user_id }}</td>
                <td>{{ $reportaje->user->email ?? 'N/A' }}</td>
                <td>{{ $reportaje->publico ? 'Sí' : 'No' }}</td>
                <td class="acciones-listado">
                    <a class="btn-estandar" href="{{ route('reportajefotos', $reportaje->id) }}">Fotos</a>

                    <a class="btn-estandar" href="{{ route('formeditarreportaje', $reportaje->id) }}">Modificar</a>

                    <form class="form-listado" method="POST" action="{{ route('borrarreportaje', $reportaje->id) }}" onsubmit="return confirm('¿Desea eliminar este reportaje?\nEsta acción no se puede deshacer.')">
                        @csrf
                        @method('DELETE')
                        <button class="btn-borrar" type="submit">Borrar</button>
                    </form>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>

{{-- Enlaces de paginación --}}
@include('parciales.botonespaginas', ['objetos' => $reportajes])

@else
<p>No hay reportajes fantasma en la base de datos.</p>
@endif
