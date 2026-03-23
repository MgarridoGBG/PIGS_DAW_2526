{{-- resources/views/parciales/dashempleado.blade.php
    Vista para mostrar el dashboard del empleado con opciones de gestión.
--}}

<div class="container centrado">
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

    </div>
</div>
<!-- Reportajes -->
<section>
    <div class="dash-pulsador-desplegar">
        <img aria-label="Flecha desplegable" class="flecha" src="{{ Vite::asset('resources/images/flechaoscura.png') }}">
        <h3>Gestión de reportajes:</h3>
    </div>


    <div class="contenedor-dash-desplegable">
        <div class="botonera-formulario">
            <a href="{{ route('listarreportajes') }}" target="_blank" rel="noopener noreferrer" class="btn-estandar">Ver todos los reportajes</a>
            <a href="{{ route('formnuevoreportaje') }}" class="btn-estandar">Nuevo reportaje</a>
        </div>

        <div class="formulario-estandar">
            <h2>Buscar Reportajes</h2>
            <form action="{{ route('filtrarreportajes') }}" method="POST">
                @csrf
                <label for="identificacion">ID Reportaje:</label>
                <input type="text" name="identificacion" id="identificacion">

                <label for="user_id">ID Usuario:</label>
                <input type="text" name="user_id" id="user_id">

                <label for="email_usuario">Email Usuario:</label>
                <input type="text" name="email_usuario" id="email_usuario">

                <label for="tipo">Tipo:</label>
                <select name="tipo" id="tipo">
                    <option value="">-- Seleccione un tipo --</option>
                    @foreach($tiposRepor as $tipoRepor)
                    <option value="{{ $tipoRepor }}">{{ ucfirst($tipoRepor) }}</option>
                    @endforeach
                </select>

                <label for="codigo">Código:</label>
                <input type="text" name="codigo" id="codigo">

                <label for="descripcion">Descripción:</label>
                <input type="text" name="descripcion" id="descripcion">

                <label for="fecha_report">Fecha:</label>
                <input type="date" name="fecha_report" id="fecha_report">

                <label for="publico">Público:</label>
                <select name="publico" id="publico">
                    <option value="">-- Seleccione --</option>
                    <option value="1">Sí</option>
                    <option value="0">No</option>
                </select>
                <div class="botonera-formulario">
                    <button type="submit" class="bt-estandar">Buscar</button>
                    <button type="reset" class="bt-estandar">Limpiar</button>
                </div>
            </form>
        </div>
    </div>
</section>

<!-- Fotografías -->
<section>
    <div class="dash-pulsador-desplegar">
        <img aria-label="Flecha desplegable" class="flecha" src="{{ Vite::asset('resources/images/flechaoscura.png') }}">
        <h3>Gestión de fotografías:</h3>
    </div>

    <div class="contenedor-dash-desplegable">
        <div class="botonera-formulario">
            <p><a href="{{ route('listarfotografias') }}" target="_blank" rel="noopener noreferrer" class="btn-estandar">Ver todas las fotografías</a></p>
            <p><a href="{{ route('formnuevafotografia') }}" class="btn-estandar">Nueva fotografía</a></p>
        </div>

        <div class="formulario-estandar">
            <h2>Buscar Fotografías</h2>
            <form action="{{ route('filtrarfotografias') }}" method="POST">
                @csrf
                <label for="foto_id">ID Fotografía:</label>
                <input type="text" name="foto_id" id="foto_id">

                <label for="nombre_foto">Nombre de la foto:</label>
                <input type="text" name="nombre_foto" id="nombre_foto">

                <label for="reportaje_id">ID Reportaje:</label>
                <input type="text" name="reportaje_id" id="reportaje_id">

                <label for="user_id">ID Usuario:</label>
                <input type="text" name="user_id" id="user_id">

                <label for="reportaje_codigo">Código del Reportaje:</label>
                <input type="text" name="reportaje_codigo" id="reportaje_codigo">

                <label for="propietario_email">Email del propietario:</label>
                <input type="text" name="propietario_email" id="propietario_email">
                <div class="botonera-formulario">
                    <button type="submit" class="bt-estandar">Buscar</button>
                    <button type="reset" class="bt-estandar">Limpiar</button>
                </div>
            </form>
        </div>
    </div>
</section>

<!-- Pedidos -->
<section>

    <div class="dash-pulsador-desplegar">
        <img aria-label="Flecha desplegable" class="flecha" src="{{ Vite::asset('resources/images/flechaoscura.png') }}">
        <h3>Gestión de pedidos:</h3>
    </div>
    <div class="contenedor-dash-desplegable">
        <div>
            <p><a class="btn-estandar" href="{{ route('listarpedidos') }}" target="_blank" rel="noopener noreferrer">Ver todos los pedidos</a></p>
            <br>
            <p class="privada-info"><strong>Para crear un nuevo pedido para un cliente:<br>Añada items a través del carrito y una vez registrado el pedido asigne el nuevo cliente.</strong></p>
        </div>

        <div class="formulario-estandar">
            <h4>Buscar Pedidos</h4>
            <form action="{{ route('filtrarpedidos') }}" method="POST">
                @csrf
                <label for="identificacion">ID Pedido:</label>
                <input type="text" name="identificacion" id="identificacion">

                <label for="user_id">ID Usuario:</label>
                <input type="text" name="user_id" id="user_id">

                <label for="email_usuario">Email Usuario:</label>
                <input type="text" name="email_usuario" id="email_usuario">

                <label for="estadoPedido">Estado:</label>
                <select name="estadoPedido" id="estadoPedido">
                    <option value="">-- Seleccione un estado --</option>
                    @foreach($estadosPedido as $estadoPedido)
                    <option value="{{ $estadoPedido }}">{{ ucfirst($estadoPedido) }}</option>
                    @endforeach
                </select>

                <label for="fecha_pedido">Fecha:</label>
                <input type="date" name="fecha_pedido" id="fecha_pedido">

                <div class="botonera-formulario">
                    <button type="submit" class="btn-estandar">Buscar</button>
                    <button type="reset" class="btn-estandar">Limpiar</button>
                </div>
            </form>
        </div>
    </div>
</section>

<!-- Citas -->
<section>
    <div class="dash-pulsador-desplegar">
        <img aria-label="Flecha desplegable" class="flecha" src="{{ Vite::asset('resources/images/flechaoscura.png') }}">
        <h3>Gestión de citas:</h3>
    </div>
    <div class="contenedor-dash-desplegable">
        <div>
            <p><a class="btn-estandar" href="{{ route('listarcitas') }}" target="_blank" rel="noopener noreferrer">ver todos</a></p>
            <br>
            <p class="privada-info"><strong>Para crear una nueva cita como admin:<br>Utilice el calendario y luego asigne el cliente.</strong></p>
        </div>

        <div class="formulario-estandar">
            <h4>Buscar Citas</h4>
            <form action="{{ route('filtrarcitas') }}" method="POST">
                @csrf
                <label for="identificacion">ID Cita:</label>
                <input type="text" name="identificacion" id="identificacion">

                <label for="user_id">ID Usuario:</label>
                <input type="text" name="user_id" id="user_id">

                <label for="email_usuario">Email Usuario:</label>
                <input type="text" name="email_usuario" id="email_usuario">

                <label for="estadoCita">Estado:</label>
                <select name="estadoCita" id="estadoCita">
                    <option value="">-- Seleccione un estado --</option>
                    @foreach($estadosCita as $estadoCita)
                    <option value="{{ $estadoCita }}">{{ ucfirst($estadoCita) }}</option>
                    @endforeach
                </select>

                <label for="fecha_cita">Fecha:</label>
                <input type="date" name="fecha_cita" id="fecha_cita">

                <label for="turno">Turno:</label>
                <select name="turno" id="turno">
                    <option value="">-- Seleccione un turno --</option>
                    @foreach($turnosCita as $turno)
                    <option value="{{ $turno }}">{{ ucfirst($turno) }}</option>
                    @endforeach
                </select>

                <div class="botonera-formulario">
                    <button type="submit" class="btn-estandar">Buscar</button>
                    <button type="reset" class="btn-estandar">Limpiar</button>
                </div>
            </form>
        </div>
    </div>
</section>
<!-- Etiquetas -->
<section>
    <div class="dash-pulsador-desplegar">
        <img aria-label="Flecha desplegable" class="flecha" src="{{ Vite::asset('resources/images/flechaoscura.png') }}">
        <h3>Gestión de etiquetas:</h3>
    </div>

    <div class="contenedor-dash-desplegable">
        <div>
            <ol class="lista-etiquetas">
                @foreach($nombresEtiquetas as $nombreEtiqueta)
                <li>{{ $nombreEtiqueta }}</li>
                @endforeach
            </ol>
        </div>
        <div class="formulario-estandar">
            <form method="POST">
                @csrf
                <p class="privada-info"><strong>Añada o elimine etiquetas de fotografías.</strong><br> Para crear una nueva etiqueta, introduzca un nombre único y haga clic en <strong>"Crear etiqueta"</strong>. Para eliminar una etiqueta existente, introduzca el nombre de la etiqueta que desea eliminar y haga clic en <strong>"Borrar etiqueta"</strong>.</p>
               
                <input type="text" name="nombre_etiqueta" placeholder="nombre_etiqueta" required>
                <div class="botonera-formulario">
                    <button class="btn-estandar" type="submit" formaction="{{ route('crearEtiqueta') }}">Crear etiqueta</button>
                    <button class="btn-borrar" type="submit" formaction="{{ route('borrarEtiqueta') }}" onclick="return confirm('¿Desea borrar esta etiqueta?')">Borrar etiqueta</button>
                </div>
            </form>
        </div>
    </div>
</section>