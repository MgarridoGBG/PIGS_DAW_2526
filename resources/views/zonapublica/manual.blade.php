{{-- resources/views/zonapublica/manual.blade.php
    Vista del manual de uso de la aplicación para los perfiles
    Cliente, Empleado y Administrador.
--}}

@extends('layouts.app')

@section('title', 'Manual de Uso')

@push('styles')
@vite(['resources/css/paginas/manual.css'])
@endpush

@section('content')

<div class="manual-pagina">

    {{--Cabecera --}}
    <header class="manual-header">
        <p class="text-sm font-semibold tracking-wider">DOCUMENTACIÓN</p>
        <h1>Manual de Uso</h1>
        <p>Guía de acceso y funcionamiento de la aplicación para los perfiles Cliente, Empleado y Administrador.</p>
    </header>

    {{-- Layout: TOC + Contenido --}}
    <div class="manual-contenedor">

        {{-- ÍNDICE --}}
        <aside class="manual-indice" aria-label="Índice del manual">
            <p class="manual-indice-titulo">Índice</p>
            <ul>
                <li><a class="tw-enlace" href="#registro-login"><strong>1</strong> Registro y Login</a></li>
                <li><a class="tw-enlace" href="#login"><strong>1.1</strong> Login</a></li>
                <li><a class="tw-enlace" href="#registro"><strong>1.2</strong> Registro</a></li>

                <li><a class="tw-enlace" href="#dashboard-cliente"><strong>2</strong> Dashboard Cliente</a></li>
                <li><a class="tw-enlace" href="#cliente-general"><strong>2.1</strong> General</a></li>
                <li><a class="tw-enlace" href="#cliente-perfil"><strong>2.2</strong> Editar perfil</a></li>
                <li><a class="tw-enlace" href="#cliente-eliminar"><strong>2.3</strong> Eliminar cuenta</a></li>
                <li><a class="tw-enlace" href="#cliente-reportajes"><strong>2.4</strong> Reportajes</a></li>
                <li><a class="tw-enlace" href="#cliente-cita"><strong>2.5</strong> Cita</a></li>
                <li><a class="tw-enlace" href="#cliente-pedidos"><strong>2.6</strong> Pedidos</a></li>

                <li><a class="tw-enlace" href="#dashboard-empleado"><strong>3</strong> Dashboard Empleado</a></li>
                <li><a class="tw-enlace" href="#empleado-general"><strong>3.1</strong> General</a></li>
                <li><a class="tw-enlace" href="#empleado-reportajes"><strong>3.2</strong> Reportajes</a></li>
                <li><a class="tw-enlace" href="#empleado-fotografias"><strong>3.3</strong> Fotografías</a></li>
                <li><a class="tw-enlace" href="#empleado-pedidos"><strong>3.4</strong> Pedidos</a></li>
                <li><a class="tw-enlace" href="#empleado-citas"><strong>3.5</strong> Citas</a></li>
                <li><a class="tw-enlace" href="#empleado-etiquetas"><strong>3.6</strong> Etiquetas</a></li>

                <li><a class="tw-enlace" href="#dashboard-admin"><strong>4</strong> Dashboard Administrador</a></li>
                <li><a class="tw-enlace" href="#admin-general"><strong>4.1</strong> General</a></li>
                <li><a class="tw-enlace" href="#admin-usuarios"><strong>4.2</strong> Usuarios</a></li>
                <li><a class="tw-enlace" href="#admin-formatos"><strong>4.3</strong> Formatos</a></li>
                <li><a class="tw-enlace" href="#admin-soportes"><strong>4.4</strong> Soportes</a></li>
                <li><a class="tw-enlace" href="#admin-mantenimiento"><strong>4.5</strong> Mantenimiento</a></li>
            </ul>
        </aside>

        {{-- CONTENIDO  --}}
        <div>
            <section id="registro-login">
                <h2>1 Registro y Login</h2>

                {{-- 1.1 --}}
                <div id="login" class="manual-subseccion">
                    <h3>1.1 Login</h3>
                    <p>
                        Los usuarios registrados acceden desde el enlace <strong>Login</strong> en el menú
                        principal de navegación mediante su nombre de usuario (email de registro) y contraseña.
                    </p>
                </div>

                {{-- 1.2 --}}
                <div id="registro" class="manual-subseccion">
                    <h3>1.2 Registro de nuevo usuario</h3>
                    <p>
                        Pueden registrarse en el enlace <strong>Registrarse</strong> en el menú de navegación,
                        respetando las siguientes restricciones:
                    </p>
                    <div class="manual-aviso">
                        <strong>Restricciones</strong><br>

                        Todos los campos son obligatorios.<br><strong>Email:</strong>
                        debe ser un email válido y no debe existir para otro cliente.<br>
                        <strong>DNI:</strong> debe ser un DNI, NIF o NIE válido y no debe existir para otro cliente.<br>
                        <strong>Contraseña:</strong> longitud mínima de 8 caracteres; debe incluir
                        al menos una mayúscula, una minúscula y un número.
                    </div>
                </div>
            </section>

            {{-- 2  DASHBOARD DE CLIENTE --}}
            <section id="dashboard-cliente">
                <h2>2 Dashboard de Cliente</h2>

                {{-- 2.1 --}}
                <div id="cliente-general" class="manual-subseccion">
                    <h3>2.1 General</h3>
                    <p>
                        Los usuarios registrados como clientes acceden al panel de control con las acciones
                        correspondientes a su rol. La vista incluye los datos del perfil, así como los enlaces
                        para editar dichos datos o solicitar la eliminación de su cuenta. También da acceso a
                        la gestión de sus reportajes, citas y pedidos.
                    </p>
                </div>

                {{-- 2.2 --}}
                <div id="cliente-perfil" class="manual-subseccion">
                    <h3>2.2 Edición de datos del perfil</h3>
                    <p>
                        Mediante el botón <strong>Editar mi perfil</strong>, los usuarios pueden acceder a un
                        formulario para modificar los datos de su cuenta, manteniendo las mismas condiciones
                        de seguridad que las requeridas en el registro.
                    </p>
                </div>

                {{-- 2.3 --}}
                <div id="cliente-eliminar" class="manual-subseccion">
                    <h3>2.3 Eliminación de la cuenta</h3>
                    <p>
                        Marcando el checkbox <strong>Solicito eliminar mi perfil</strong>, la cuenta del cliente
                        se marcará para su eliminación, que será llevada a cabo por un administrador.
                        Hasta su eliminación definitiva, el cliente podrá acceder a su cuenta y cancelar
                        la solicitud desmarcando el checkbox.
                    </p>


                </div>

                {{-- 2.4 --}}
                <div id="cliente-reportajes" class="manual-subseccion">
                    <h3>2.4 Gestión de reportajes</h3>
                    <p>
                        Desde la pestaña <strong>Ver mis reportajes</strong>, el cliente puede acceder a un
                        listado de sus reportajes. Pulsando el botón <strong>Ver fotos</strong> se accede a
                        la galería paginada de fotografías. Desde la vista paginada puede accederse a la
                        vista completa de la fotografía pulsando sobre la miniatura.
                    </p>
                    <p>Cada foto incluye el botón <strong>Añadir al carrito</strong>.</p>

                </div>

                {{-- 2.5 --}}
                <div id="cliente-cita" class="manual-subseccion">
                    <h3>2.5 Gestión de cita</h3>
                    <p>
                        Un cliente registrado puede solicitar una cita pulsando el enlace <strong>Cita</strong>
                        del menú principal. Desde esta sección, el cliente accede a la vista del calendario, donde
                        pulsando sobre un día puede reservar o cambiar su cita. Mediante el boton inferior <strong>Cancelar mi cita</strong>, el cliente puede cancelar su cita concertada.
                    </p>

                    <div class="manual-aviso">
                        <strong>Restricciones</strong><br>
                        Solo una cita por cliente.<br> No es posible reservar turnos ya ocupados ni reservar días pasados.
                    </div>


                    <p>
                        La pestaña <strong>Ver mi cita</strong> muestra los datos de la cita si existe.
                    </p>
                </div>

                {{-- 2.6 --}}
                <div id="cliente-pedidos" class="manual-subseccion">
                    <h3>2.6 Realización y gestión de pedidos</h3>
                    <p>
                        Los usuarios registrados pueden realizar pedidos de copias físicas o licencia digital
                        tanto de fotografías privadas como públicas. Las fotografías públicas se acceden desde
                        el botón <strong>Galería</strong>.
                    </p>


                    <p>
                        Al añadir una foto al carrito aparece una ventana emergente para seleccionar formato
                        y soporte. El carrito admite hasta <strong>15 ítems</strong>.
                    </p><br>

                    <h4>Vista del carrito</h4>
                    <p>La vista del carrito muestra:</p>
                    <ul class="manual-lista">
                        <li>Nombre de la foto</li>
                        <li>Reportaje</li>
                        <li>Formato y soporte</li>
                        <li>Precio y cantidad</li>
                        <li>Y las Acciones: <strong>Eliminar, Vaciar carrito, Enviar pedido</strong></li>
                    </ul>

                    <p>
                        La pestaña <strong>Ver mis pedidos</strong> muestra el historial y permite cancelar
                        pedidos en estado <em>Emitido</em> o <em>Presupuestado</em>.
                    </p>

                </div>
            </section>

            {{-- 3  DASHBOARD DE EMPLEADO --}}
            <section id="dashboard-empleado">
                <h2>3 Dashboard de Empleado</h2>

                {{-- 3.1 --}}
                <div id="empleado-general" class="manual-subseccion">
                    <h3>3.1 General</h3>
                    <p>
                        Los usuarios registrados como empleados acceden al panel de control con las acciones
                        correspondientes a su rol. La vista incluye los datos de perfil y los enlaces para
                        editar dichos datos de forma idéntica al perfil Cliente. También da acceso a las
                        opciones de administración básica ligadas a su rol.
                    </p>
                </div>

                {{-- 3.2 --}}
                <div id="empleado-reportajes" class="manual-subseccion">
                    <h3>3.2 Gestión de reportajes</h3>
                    <p>
                        La pestaña desplegable <strong>Gestión de Reportajes</strong> muestra las opciones
                        de administración de estos.
                    </p>

                    <div class="manual-aviso">
                        <strong>Importante</strong><br>
                        Cada reportaje va ligado a una carpeta en el almacenamiento principal de la aplicación:
                        <br><strong>(root)\storage\app\private\fotosreportajes\&lt;CÓDIGO&gt;</strong><br>.
                        El nombre de la carpeta debe coincidir exactamente con el <strong>CÓDIGO</strong> del
                        reportaje. Esto es fundamental para el correcto funcionamiento de la aplicación.
                    </div>

                    {{-- 3.2.1 --}}
                    <h4>3.2.1 Registrar nuevo reportaje</h4>
                    <p>
                        Mediante el botón <strong>Nuevo Reportaje</strong> se muestran las opciones para
                        registrar un reportaje en la base de datos. Al pulsar <strong>Crear Reportaje</strong>,
                        el sistema comprueba el estado de la carpeta asociada y da las opciones:
                    </p>
                    <ul class="manual-lista">
                        <li>
                            Si la <strong>Carpeta EXISTE y está VACÍA:</strong> se registra el reportaje vacío
                            en la base de datos.
                        </li>
                        <li>
                            Si la <strong>Carpeta NO EXISTE:</strong> el sistema pregunta si desea crearla o
                            registrar solo el reportaje.
                        </li>
                        <li>
                            Si la <strong>Carpeta EXISTE con fotografías:</strong> el sistema lista las fotos
                            encontradas y ofrece añadirlas todas o crear el reportaje sin fotos.
                        </li>
                    </ul>
                    {{-- 3.2.2 --}}
                    <h4 id="empleado-listado-reportaje">3.2.2 Ver listado de reportajes</h4>
                    <p>
                        Desde el botón <strong>Ver todos los reportajes</strong> se accede a una lista
                        completa. El formulario de búsqueda permite filtrar por ID, email, tipo, código,
                        descripción, fecha y visibilidad.
                    </p><br>
                    {{-- 3.2.3 --}}
                    <h4 id="empleado-eliminar-reportaje">3.2.3 Eliminar reportajes</h4>
                    <p>
                        Al eliminar un reportaje, el sistema comprueba si existe carpeta asociada al código:
                    </p>
                    <ul class="manual-lista">
                        <li>Si la <strong>Carpeta NO EXISTE:</strong> se elimina el reportaje de la BD directamente.</li>
                        <li>
                            Si la <strong>Carpeta EXISTE:</strong> el sistema pregunta si desea eliminar también
                            la carpeta y las fotos del servidor, o si solo eliminar el registro en la base de datos.
                        </li>
                    </ul>
                    {{-- 3.2.4 --}}
                    <h4 id="empleado-editar-reportaje">3.2.4 Editar reportaje</h4>
                    <p>
                        Desde el botón <strong>Modificar</strong> se accede al formulario de edición.
                        Si se cambia el código del reportaje, el sistema vuelve a comprobar la carpeta:
                    </p>
                    <ul class="manual-lista">
                        <li>Si la <strong>Carpeta NO EXISTE:</strong> se cambia el código en la base de datos.</li>
                        <li>Si la <strong>Carpeta EXISTE:</strong> el sistema ofrece renombrar la carpeta y la
                            entrada en BD, o renombrar solo la entrada sin renombrar la carpeta.</li>
                    </ul>
                </div>
                {{-- 3.3 --}}
                <div id="empleado-fotografias" class="manual-subseccion">
                    <h3>3.3 Gestión de fotografías</h3>
                    <p>
                        La pestaña <strong>Gestión de Fotografías</strong> permite administrar las fotos
                        de los reportajes.
                    </p>

                    <div class="manual-aviso">
                        <strong>Importante</strong><br>
                        Cada fotografía va ligada a un archivo físico dentro de la carpeta del reportaje,
                        cuyo nombre coincide con el nombre de la fotografía en la base de datos, incluido su extensión.
                        Además, existe una carpeta <strong>thumbs</strong> con las miniaturas, con el mismo nombre
                        que el archivo de foto principal.
                    </div>

                    <h4>3.3.1 Registrar nueva fotografía</h4>
                    <p>
                        Desde el botón <strong>Registrar nueva fotografía</strong> se accede al formulario de registro.
                        El formulario solicita el nombre del archivo (con extensión) y el código del
                        reportaje al que pertenece. Y comprueba si existe el archivo en el servidor.
                    <ul class="manual-lista">
                        <li>Si el <strong>archivo EXISTE:</strong> en el servidor, se registra directamente</li>
                        <li>Si el <strong>archivo NO EXISTE:</strong>, el sistema pregunta si desea agregarlo de todas formas.</li>
                    </ul>
                    </p>

                    <h4>3.3.2 Ver listado de fotografías</h4>
                    <p>
                        Desde <strong>Ver todas las fotografías</strong> o mediante el buscador se accede
                        a una galería paginada con miniaturas, desde donde puede visualizar cada fotografía en detalle o ejecutar otras acciones disponibles,
                        <strong>borrar, renombrar y añadir al carrito</strong>.
                    </p>

                </div>

                {{-- 3.4 --}}
                <div id="empleado-pedidos" class="manual-subseccion">
                    <h3>3.4 Gestión de pedidos</h3>
                    <p>
                        Los empleados pueden acceder a la gestión de pedidos desde su panel de control.
                        Pueden acceder a un listado completo de pedidos realizados por los clientes,
                        con la posibilidad de filtrar por parámetros específicos.
                    </p>
                    <p>
                        Desde el listado se puede acceder al detalle de cada pedido, así como a la opciones de edición
                        de los mismos o eliminacion desde los botones <strong>Detalles, Modificar y Borrar</strong>.
                    </p>
                    <p>
                        Para añadir items a un pedido, serealiza añadiendo los items al carrito y pulsando <strong>Editar pedido y añadir Items</strong>
                        desde la vista de edición del mismo.
                    </p>
                    <p>
                        Para crear un pedido nuevo para un cliente, el empleado debe reliazar primero el pedido a su nombre
                        y posteriormente adjudicarlo el usuario final desde la vista de edición del pedido.
                    </p>
                </div>

                {{-- 3.5 --}}
                <div id="empleado-citas" class="manual-subseccion">
                    <h3>3.5 Gestión de citas</h3>
                    <p>
                        Desde la pestaña desplegable <strong>Gestión de citas</strong>, los empleados
                        pueden acceder al listado de todas las citas registradas en el sistema o
                        filtrado por parámetros específicos.
                    </p>
                    <p>
                        Desde el listado correspondiente, pueden <strong>Eliminarse o modificar</strong> las
                        citas registradas en el sistema (cambiar fecha, usuario, estado, etc.).
                    </p>
                    <p>
                        Para crear una nueva cita para un cliente, el empleado debe realizar primero la reserva a su nombre
                        y posteriormente adjudicarla al usuario final desde la vista de edición de la cita.
                    </p>

                </div>

                {{-- 3.6 --}}
                <div id="empleado-etiquetas" class="manual-subseccion">
                    <h3>3.6 Gestión de etiquetas</h3>
                    <p>
                        Las etiquetas se utilizan para clasificar fotografías públicas en la galería.
                        Desde la pestaña <strong>Gestión de etiquetas</strong>, los empleados pueden acceder al listado de etiquetas existentes.
                        El empleado puede crear y eliminar etiquetas. Los nombres de las etiquetas deben ser únicos y son case-insensitive.
                    </p>
                </div>
            </section>
             {{-- 4  DASHBOARD DE ADMINISTRADOR --}}
        <section id="dashboard-admin">
            <h2>4 Dashboard de Administrador</h2>
            {{-- 4.1 --}}
            <div id="admin-general" class="manual-subseccion">
                <h3>4.1 General</h3>
                <p>
                    Los administradores tienen acceso a todas las funciones del sistema, su panel de control incluye las funciones
                    de los empleados, así como las funciones de administración avanzada, incluyendo la
                    gestión completa de usuarios, formatos, soportes y mantenimiento de la base de datos.
                </p>

            </div>

            {{-- 4.2 --}}
            <div id="admin-usuarios" class="manual-subseccion">
                <h3>4.2 Gestión de usuarios</h3>
                <p>
                    Desde la pestaña <strong>Gestión de usuarios</strong>, el administrador puede
                    acceder al listado de todos los usuarios registrados en el sistema,
                    con la posibilidad de filtrar por parámetros específicos. Además de la posibilidad
                    de crear nuevos usuarios.
                </p>
                <p>
                    Desde el listado de usuarios, el administrador puede acceder a la edición o eliminación de los mismos, con las posibilidades de:
                <ul class="manual-lista">
                    <li>Editar los datos del perfil</li>
                    <li>Cambiar contraseña</li>
                    <li>Restaurar cuentas marcadas para eliminación</li>
                    <li>Cambiar roles y privilegios</li>
                </ul>
            </div>

            {{-- 4.3 --}}
            <div id="admin-formatos" class="manual-subseccion">
                <h3>4.3 Gestión de formatos</h3>
                <p>
                    Los formatos representan los tamaños de impresión disponibles (por ejemplo: 10x15,
                    13x18, 50x70). Desde la pestaña <strong>Gestión de formatos</strong>, el administrador
                    puede crear y acceder a los listados totales o filtrados de formatos,
                    y desde estos listados editar y eliminar formatos.
                </p>


            </div>

            {{-- 4.4 --}}
            <div id="admin-soportes" class="manual-subseccion">
                <h3>4.4 Gestión de soportes</h3>
                <p>
                    Los soportes representan el material sobre el que se imprime la fotografía.
                    Desde la pestaña <strong>Gestión de soportes</strong>, el administrador puede
                    acceder a la creación y listado de soportes, y desde estos listados a la edición,
                    política de precios y eliminación de los mismos.
                </p>
            </div>

            {{-- 4.5 --}}
            <div id="admin-mantenimiento" class="manual-subseccion">
                <h3>4.5 Mantenimiento de base de datos</h3>
                <p>En la pestaña <strong>Mantenimiento de base de datos</strong>,
                    el administrador dispone de herramientas para limpiar registros fantasma:</p>
                <ul class="manual-lista">
                    <li><strong>Buscar clientes fantasma:</strong> Busca clientes que no han realizado pedidos, no tienen reportajes registrados ni cita agendada</li>
                    <li><strong>Buscar reportajes fantasma:</strong> Localiza reportajes en la base de datos cuyo directorio de fotos asociado no existe en el almacenamiento</li>
                    <li><strong>Buscar fotos fantasma:</strong> Identifica fotos en la base de datos que no tienen un archivo asociado en el almacenamiento</li>
                    <li><strong>Buscar pedidos fantasma:</strong> Localiza pedidos vacíos (sin items) en la base de datos.</li>
                </ul>
            </div>
        </section>
        </div>
       
        <div class="redirector-publi-priv">
            @auth
            <a class="tw-enlace" href="{{ route('zonaprivada') }}">Ir a <b>Zona Privada</b></a>
            <p> | </p>
            @endauth
            <a class="tw-enlace" href="{{ route('zonapublica') }}">Ir a <b>Zona Pública</b></a>
        </div>
    </div>
</div>

@endsection