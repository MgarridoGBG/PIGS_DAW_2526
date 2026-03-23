{{-- resources/views/parciales/listados/listaritemscarrito.blade.php
    Vista para mostrar el listado de items actuales en el carrito.
    Se importa en varias vistas de gestion del carrito.
--}}

<div class="listado-contenedor">

    <h2 class="titular-listado">Carrito</h2>

    @if(count($items) > 0)
    <div class="contenedor-tabla-scroll">
        <table class="tabla-estandar" id="itemscarrito" border="1" cellpadding="10" cellspacing="0" style="width: 100%; border-collapse: collapse;">
            <thead>
                <tr>
                    <th>Nombre Fotografía</th>
                    <th>Reportaje</th>
                    <th>Nombre Formato</th>
                    <th>Nombre Soporte</th>
                    <th>Precio</th>
                    <th>Cantidad</th>
                    <th>Precio Total</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                @foreach($items as $index => $item)
                <tr>
                    <td>{{ $item['nombre_fotografia'] }}</td>
                    <td>{{ $item['reportaje_codigo'] }}</td>
                    <td>{{ $item['nombre_formato'] }}</td>
                    <td>{{ $item['nombre_soporte'] }}</td>
                    <td>{{ $item['precio'] }} €</td>
                    <td>{{ $item['cantidad'] }}</td>
                    <td>{{ $item['precio_total'] }} €</td>
                    <td class="acciones-listado">
                        <form class="form-listado" method="POST" action="{{ route('borraritemcarrito') }}" style="display:inline;" onsubmit="return confirm('¿Desea eliminar este item?')">
                            @csrf
                            <input type="hidden" name="item_index" value="{{ $index }}">
                            <button class="btn-borrar" type="submit">Eliminar</button>
                        </form>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>

<div class="info-carrito">
    <p>Total de items:<strong> {{ count($items) }}</strong></p>
    <p>Precio Total: <strong>{{ $precioTotal }} €</strong></p>
</div>
<form method="POST" action="{{ route('vaciarcarrito') }}" style="display:inline;" onsubmit="return confirm('¿Desea vaciar el carrito?')">
    @csrf
    <input type="hidden" name="item_index" value="{{ $index }}">
    <button class="btn-borrar" type="submit">VACIAR CARRITO</button>
</form>
<form method="POST" action="{{ route('procesarcarrito') }}" style="display:inline;" onsubmit="return confirm('¿Desea enviar el pedido?')">
    @csrf
    <input type="hidden" name="item_index" value="{{ $index }}">
    <button class="btn-estandar" type="submit">ENVIAR PEDIDO</button>
</form>

@else

<p class="info-carrito">Vaya, tu carrito está triste y vacío.</p>

@endif


<div class="redirector-publi-priv">
    @auth
    <a class="tw-enlace" href="{{ route('zonaprivada') }}">Ir a <b>Zona Privada</b></a>
    <p> | </p>
    @endauth
    <a class="tw-enlace" href="{{ route('zonapublica') }}">Ir a <b>Zona Pública</b></a>
</div>