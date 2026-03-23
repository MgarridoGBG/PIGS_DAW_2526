{{-- resources/views/zonapublica/galeriapublica.blade.php
    Vista para mostrar la galería de todas las fotos pública,
    Incluye la vista de las colecciones públiscas y la nube de etiquetas para
    filtrar fotos por etiqueta, con actualizaciones dinámicas con.
--}}

@extends ('layouts.app')

@push('styles')
@vite(['resources/css/paginas/galeria.css'])
@vite(['resources/css/paginas/galeriapublica.css'])
@vite(['resources/js/paginas/galeriapublica.js'])
@endpush

@section('content')
<div id="contenedor-principal-galeria">

    <h3>Galería de fotos disponibles para su compra:</h3>

    <section>
        <div id="despliega-tabla-colecciones">
            <img aria-label="Flecha desplegable" id="flecha_1" src="{{ Vite::asset('resources/images/flechaoscura.png') }}">
            <h3>Ver las colecciones:</h3>
        </div>

        <div id="tabla-colecciones-publicas">
            <table id="tabla-desplegable" class="tabla-estandar">
                <thead>
                    <tr>
                        <th>Codigo</th>
                        <th>Colección</th>
                        <th>Acciones</th>
                        @auth
                        @if(Auth::user()->privilegios()->where('nombre_priv', 'admin_basico')->exists())
                        <th>Administrar</th>
                        @endif
                        @endauth
                    </tr>
                </thead>
                <tbody>
                    @foreach ($colecciones as $coleccion)
                    <tr>
                        <td>{{$coleccion->codigo}}</td>
                        <td>{{$coleccion->descripcion}}</td>
                        <td class="botonera-tabla"><a class="btn-estandar" href="{{ route('reportajefotos', $coleccion->id) }}">Ver Fotos</a></td>
                        @auth
                        @if(Auth::user()->privilegios()->where('nombre_priv', 'admin_basico')->exists())
                        <td class="botonera-tabla">
                            <a class="btn-estandar" href="{{ route('formeditarreportaje', $coleccion->id) }}">Editar</a>

                            <form method="POST" action="{{ route('borrarreportaje', $coleccion->id) }}" style="display:inline;" onsubmit="return confirm('¿Desea eliminar este reportaje?\nEsta acción no se puede deshacer.')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn-borrar">Borrar</button>
                            </form>
                        </td>
                        @endif
                        @endauth
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </section>

    <section>

        <div id="despliega-nube-etiquetas">
            <img aria-label="Flecha desplegable" id="flecha_2" src="{{ Vite::asset('resources/images/flechaoscura.png') }}">
            <h3>Etiquetas disponibles:</h3>
        </div>

        <div id="nube-etiquetas">
            <ul id="lista-etiquetas">
                @foreach ($nombresEtiquetas as $nombreEtiqueta)
                <li class="etiqueta"><a class="tw-enlace-etiqueta" href="{{ route('filtrarporetiqueta', ['busqueda' => $nombreEtiqueta]) }}">{{ $nombreEtiqueta }}</a></li>
                @endforeach
            </ul>
        </div>

        <div id="campo-busqueda-etiqueta">
            <form action="{{ route('filtrarporetiqueta') }}" method="GET">
                @csrf
                <input type="text" name="busqueda" required placeholder="Buscar por etiqueta">
                <button type="submit">Buscar</button>
            </form>
        </div>

    </section>
</div>
<br>
<div id="contenedor-galeria-fotos">
    @include('parciales.paginadofotospublicas', ['fotografias' => $fotografias])
</div>
<div class="redirector-publi-priv">
    @auth
    <a class="tw-enlace" href="{{ route('zonaprivada') }}">Ir a <b>Zona Privada</b></a>
    <p> | </p>
    @endauth
    <a class="tw-enlace" href="{{ route('zonapublica') }}">Ir a <b>Zona Pública</b></a>
</div>
@endsection