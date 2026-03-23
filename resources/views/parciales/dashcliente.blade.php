{{-- resources/views/parciales/dashcliente.blade.php
    Vista para mostrar el dashboard del cliente
    con opciones de gestión de perfil y recursos.
--}}

@auth
<div class="container">
    <div>
        <!-- AVISO ELIMINAR CUENTA -->
        @if ($usuario->marcado_eliminar)
        <div class="aviso-eliminar container">
            <img aria-label="Icono de advertencia" src="{{ Vite::asset('resources/images/avisoclaro.png') }}" alt="Advertencia">
            <h3>Tu cuenta está marcada para ser eliminada.</h3>
            <p>Si deseas cancelar esta solicitud, desmarca la casilla en tu perfil y pulsa "Enviar".</p>
        </div>
        @endif
    </div>
    <!-- Datos del usuario -->
    <h3 class="dash-datos">Mi Perfil</h3>
    <div class="contenedor-datos">
        <ul>
            <li><strong>Nombre:</strong> {{ $usuario->nombre }}</li>
            <div class="separador-lista"></div>
            <li><strong>Apellidos:</strong> {{ $usuario->apellidos }}</li>
            <div class="separador-lista"></div>
            <li><strong>DNI:</strong> {{ $usuario->dni }}</li>
            <div class="separador-lista"></div>
            <li><strong>Dirección:</strong> {{ $usuario->direccion }}</li>
            <div class="separador-lista"></div>
            <li><strong>Teléfono:</strong> {{ $usuario->telefono }}</li>
            <div class="separador-lista"></div>
            <li><strong>Email:</strong> {{ $usuario->email }}</li>
            <div class="separador-lista"></div>
        </ul>
        <a class="btn-estandar" ; href="{{ route('editarmiperfil', $usuario->id) }}">Editar mis datos</a>
        <div>
            @if ($usuario->marcado_eliminar)
            <p>Desmarca la casilla y pulsa <strong>"Enviar"</strong> si deseas cancelar la solicitud de eliminación de tu cuenta.</p>
            @else
            <p>Marca la casilla y pulsa <strong>"Enviar"</strong> si deseas que se borre tu cuenta y elimine tu perfil de usuario</p>
            @endif
            <form class="formulario-eliminar listaerrores" method="POST" action="{{ url('/administracion/marcadoborrarpropia') }}">
                @csrf
                <label class="textoerror" for="checkborrar">Solicito eliminar mi perfil</label>
                <input type="checkbox" id="checkborrar" name="cambiarmarcado" value="cambiarmarcado" {{ $usuario->marcado_eliminar ? 'checked' : '' }}>
                <button class="btn-estandar" type="submit">Enviar</button>
            </form>
        </div>
    </div>
</div>

<!-- Reportajes -->
<div>
    <div class="dash-pulsador-desplegar">
        <img aria-label="Flecha desplegable" class="flecha" src="{{ Vite::asset('resources/images/flechaoscura.png') }}">
        <h3>Ver mis reportajes:</h3>
    </div>

    <div class="contenedor-dash-desplegable">
        <p class="privada-info">En esta sección puedes ver los reportajes que has creado. Haz clic en <strong>"Ver Fotos"</strong> para ver las fotografías asociadas a cada reportaje y poder realizar pedidos.</p>

        <div class="contenedor-tabla-dash">
            @if($reportajes->count() > 0)
            <table class="tabla-estandar">                
                <thead>
                    <tr>
                        <th>Id</th>
                        <th>Codigo</th>
                        <th>Descripción</th>
                        <th>ID Propietario</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>

                    @foreach ($reportajes as $reportaje)
                    <tr>
                        <td>{{$reportaje->id}}</td>
                        <td>{{$reportaje->codigo}}</td>
                        <td>{{$reportaje->descripcion}}</td>
                        <td>{{$reportaje->user_id}}</td>
                        <td class="botonera-tabla"><a class="btn-estandar" href="{{ route('reportajefotos', $reportaje->id) }}">Ver Fotos</a></td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
            @else
            <p><strong>No tienes reportajes.</strong></p>
            @endif
        </div>
    </div>
</div>

<!-- Cita -->

<div>
    <div class="dash-pulsador-desplegar">
        <img aria-label="Flecha desplegable" class="flecha" src="{{ Vite::asset('resources/images/flechaoscura.png') }}">
        <h3>Ver mi cita:</h3>
    </div>

    <div class="contenedor-dash-desplegable">
        <p class="privada-info">En esta sección puedes ver tu cita concertada. Haz clic en <strong>"Modificar o Cancelar Cita"</strong> para gestionar tu cita.</p>
        <div class="contenedor-tabla-dash">
            @if($citas->count() > 0)
            <table class="tabla-estandar">
                <thead>
                    <tr>
                        <th>Id</th>
                        <th>Fecha</th>
                        <th>Turno</th>
                        <th>Estado</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>

                    @foreach ($citas as $cita)
                    <tr>
                        <td>{{$cita->id}}</td>
                        <td>{{$cita->fecha_cita}}</td>
                        <td>{{$cita->turno}}</td>
                        <td>{{$cita->estado_cita}}</td>
                        <td class="botonera-tabla"><a class="btn-estandar" href="{{ route('calendario') }}">Modificar o Cancelar Cita</a></td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
            @else
            <p><strong>No tienes cita. </strong><a class="tw-enlace" href="{{ route('calendario') }}"> Reserva ahora</a>.</p>
            @endif
        </div>
    </div>
</div>

<!-- Pedidos -->
<div>
    <div class="dash-pulsador-desplegar">
        <img aria-label="Flecha desplegable" class="flecha" src="{{ Vite::asset('resources/images/flechaoscura.png') }}">
        <h3>Ver mis pedidos:</h3>
    </div>

    <div class="contenedor-dash-desplegable">
        <p class="privada-info">En esta sección puedes ver tus pedidos. Haz clic en <strong>"Ver Detalles"</strong> para ver más información sobre cada pedido o en <strong>"Cancelar Pedido"</strong> para cancelar un pedido.</p>
        <div class="contenedor-tabla-dash">
            @if($pedidos->count() > 0)
            <table class="tabla-estandar">
                <thead>
                    <tr>
                        <th>Id</th>
                        <th>Fecha</th>
                        <th>Estado</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>

                    @foreach ($pedidos as $pedido)
                    <tr>
                        <td>{{$pedido->id}}</td>
                        <td>{{$pedido->fecha_pedido}}</td>
                        <td>{{ucfirst($pedido->estado_pedido)}}</td>
                        <td class="botonera-tabla">
                            <a class="btn-estandar" href="{{ route('verdetallepedido', $pedido->id) }}" target="_blank">Ver Detalles</a>
                            @if(($pedido->estado_pedido === 'emitido') || ($pedido->estado_pedido === 'presupuestado'))
                            <form method="POST" action="{{ route('borrarpedido', $pedido->id) }}" style="display:inline;" onsubmit="return confirm('¿Desea cancelar este pedido?')">
                                @csrf
                                @method('DELETE')
                                <button class="btn-borrar" type="submit" onclick="return confirm('¿Desea cancelar este pedido?')">Cancelar Pedido</button>
                            </form>
                            @endif
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
            @else
            <p><strong>No tienes pedidos.</strong></p>
            @endif
        </div>
    </div>
</div>

@else
<p>No hay usuario autenticado.</p>
@endauth
</div>