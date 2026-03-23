{{-- resources/views/parciales/dashadmin.blade.php
    Vista para mostrar el dashboard del admin.
    Incluye el dash de empleado y añade las funciones del administrador.
--}}

@include ('parciales.dashempleado')
<section>
    <div class="dash-pulsador-desplegar">
        <img aria-label="Flecha desplegable" class="flecha" src="{{ Vite::asset('resources/images/flechaoscura.png') }}">
        <h3>Gestión de usuarios:</h3>
    </div>

    <div class="contenedor-dash-desplegable">

        <div class="botonera-formulario">
            <p><a class="btn-estandar" href="{{ route('listarusuarios') }}" target="_blank" rel="noopener noreferrer">Ver todos los usuarios</a></p>
            <p> <a class="btn-estandar" href="{{ route('formnuevousuario') }}">Nuevo usuario</a></p>
        </div>

<!-- Usuarios -->
        <div class="formulario-estandar">
            <h2>Buscar Usuarios</h2>
            <form action="{{ route('filtrarusuarios') }}" method="POST">
                @csrf
                <label for="id">ID:</label>
                <input type="text" name="identificacion" id="identificacion">

                <label for="telefono">Teléfono:</label>
                <input type="text" name="telefono" id="telefono">

                <label for="email">email:</label>
                <input type="text" name="email" id="email">

                <label for="nombre">Nombre:</label>
                <input type="text" name="nombre" id="nombre">

                <label for="apellidos">Apellidos:</label>
                <input type="text" name="apellidos" id="apellidos">

                <label for="role">Role:</label>

                <select name="role" id="role">
                    <option value="">-- Seleccione un rol --</option>
                    @foreach($roles as $role)
                    <option value="{{ $role }}">{{ ucfirst($role) }}</option>
                    @endforeach
                </select>

                <label for="dni">DNI:</label>
                <input type="text" name="dni" id="dni">
                <div class="checkbox-contenedor container">
                    <label for="marcado_eliminar">Marcados para eliminar</label>
                    <input type="checkbox" name="marcado_eliminar" id="marcado_eliminar" value="1">
                </div>
                <div class="botonera-formulario">
                    <button type="submit" class="btn-estandar">Buscar</button>
                    <button type="reset" class="btn-estandar">Limpiar</button>
                </div>
            </form>
        </div>
    </div>
</section>

<!-- Formatos -->
<section>
    <div class="dash-pulsador-desplegar">
        <img aria-label="Flecha desplegable" class="flecha" src="{{ Vite::asset('resources/images/flechaoscura.png') }}">
        <h3>Gestión de formatos:</h3>
    </div>

    <div class="contenedor-dash-desplegable">
        <div class="botonera-formulario">
            <p><a class="btn-estandar" href="{{ route('listarformatos') }}" target="_blank" rel="noopener noreferrer">Ver todos los formatos</a></p>
            <p><a class="btn-estandar" href="{{ route('formnuevoformato') }}">Nuevo formato</a></p>
        </div>

        <div class="formulario-estandar" id="buscar_formatos">
            <h4>Buscar Formatos</h4>
            <form action="{{ route('filtrarformatos') }}" method="POST">
                @csrf
                <label for="id">ID:</label>
                <input type="text" name="identificacion" id="identificacion">

                <label for="ancho">Ancho:</label>
                <input type="number" name="ancho" id="ancho">

                <label for="alto">Alto:</label>
                <input type="number" name="alto" id="alto">

                <label for="nombre">Nombre:</label>
                <input type="text" name="nombre" id="nombre">
                <div class="botonera-formulario">
                    <button type="submit" class="btn-estandar">Buscar</button>
                    <button type="reset" class="btn-estandar">Limpiar</button>
                </div>
            </form>
        </div>
    </div>
</section>

<!-- Soportes -->
<section>

    <div class="dash-pulsador-desplegar">
        <img aria-label="Flecha desplegable" class="flecha" src="{{ Vite::asset('resources/images/flechaoscura.png') }}">
        <h3>Gestión de soportes:</h3>
    </div>

    <div class="contenedor-dash-desplegable">
        <div class="botonera-formulario">
            <p><a class="btn-estandar" href="{{ route('listarsoportes') }}" target="_blank" rel="noopener noreferrer">ver todos</a></p>
            <p><a class="btn-estandar" href="{{ route('formnuevosoporte') }}">Nuevo soporte</a></p>
        </div>
        <div class="formulario-estandar" id="buscar_soportes">
            <h4>Buscar Soportes</h4>
            <form action="{{ route('filtrarsoportes') }}" method="POST">
                @csrf
                <label for="id">ID:</label>
                <input type="text" name="identificacion" id="identificacion">

                <label for="nombre_soport">Nombre:</label>
                <input type="text" name="nombre_soport" id="nombre_soport">

                <label for="disponibilidad">Disponibilidad:</label>
                <select name="disponibilidad" id="disponibilidad">
                    <option value="">-- Seleccione --</option>
                    <option value="1">Disponible</option>
                    <option value="0">No disponible</option>
                </select>

                <label for="precio_minimo">Precio mínimo:</label>
                <input type="number" step="0.01" min="0" name="precio_minimo" id="precio_minimo">

                <label for="precio_maximo">Precio máximo:</label>
                <input type="number" step="0.01" min="0" name="precio_maximo" id="precio_maximo">

                <div class="botonera-formulario">
                    <button type="submit" class="btn-estandar">Buscar</button>
                    <button type="reset" class="btn-estandar">Limpiar</button>
                </div>
            </form>
        </div>
    </div>
   
</section>

<!-- Mantenimiento -->
<section>

    <div class="dash-pulsador-desplegar">
        <img aria-label="Flecha desplegable" class="flecha" src="{{ Vite::asset('resources/images/flechaoscura.png') }}">
        <h3>Mantenimiento base de datos:</h3>
    </div>

    <div class="contenedor-dash-desplegable seccion-mantenimiento">
        <div class="sección-mantenimiento">
            <h4 class="titulo-mantenimiento">Buscar Clientes Fantasma</h4>
            <p class="privada-info-gris">Busca clientes que no han realizado pedidos, no tienen reportajes registrados ni cita agendada.</p>
            <form target="_blank" action="{{ route('filtrarclientesfantasma') }}" method="POST">
                @csrf
                <button type="submit" class="btn-estandar" onclick="return confirm('Esta operación puede llevar un rato\n¿Continuar?')">Buscar</button>
            </form>
        </div>

        <div class="separador-lista"></div>

        <div class="sección-mantenimiento">
            <h4 class="titulo-mantenimiento">Buscar Reportajes Fantasma</h4>
            <p class="privada-info-gris">Localiza reportajes en la base de datos cuyo directorio de fotos asociado no existe en el almacenamiento.</p>
            <form target="_blank" action="{{ route('filtrarreportajesfantasma') }}" method="POST">
                @csrf
                <button type="submit" class="btn-estandar" onclick="return confirm('Esta operación puede llevar un rato\n¿Continuar?')">Buscar</button>
            </form>
        </div>

        <div class="separador-lista"></div>

        <div class="sección-mantenimiento">
            <h4 class="titulo-mantenimiento">Buscar Fotos Fantasma</h4>
            <p class="privada-info-gris">Localiza fotos en la base de datos cuyo archivo físico asociado no existe en el almacenamiento.</p>
            <form target="_blank" action="{{ route('filtrarfotosfantasma') }}" method="POST">
                @csrf
                <button type="submit" class="btn-estandar" onclick="return confirm('Esta operación puede llevar un rato\n¿Continuar?')">Buscar</button>
            </form>
        </div>

        <div class="separador-lista"></div>

        <div class="sección-mantenimiento">
            <h4 class="titulo-mantenimiento">Buscar Pedidos Fantasma</h4>
            <p class="privada-info-gris">Localiza pedidos vacíos (sin items) en la base de datos.</p>
            <form target="_blank" action="{{ route('filtrarpedidosfantasma') }}" method="POST">
                @csrf
                <button type="submit" class="btn-estandar" onclick="return confirm('Esta operación puede llevar un rato\n¿Continuar?')">Buscar</button>
            </form>
        </div>
    </div>
</section>