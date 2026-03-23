 {{-- resources/views/parciales/pedidodetallado.blade.php
    Vista para mostrar el detalle de un pedido, incluyendo los items del pedido,
    el precio total y las acciones disponibles según los privilegios del usuario.
--}}

 @extends('layouts.app')

 @push('styles')
 @vite(['resources/css/paginas/listados.css'])
 @endpush

 @section('title', 'Detalle del Pedido')

 @section('content')

 <div class="listado-contenedor">
     <H2 class="titular-listado">Detalle del Pedido {{ $pedido->id }}</H2>
     @if($items->count())
     <div class="contenedor-tabla-scroll">
         <table class="tabla-estandar">
             <thead>
                 <tr>
                     <th>Fotografía</th>
                     <th>Reportaje</th>
                     <th>Formato</th>
                     <th>Soporte</th>
                     <th>Precio</th>
                     <th>Cantidad</th>
                     <th>Precio Total</th>
                     @if($pedido->estado_pedido === 'emitido' || $pedido->estado_pedido === 'presupuestado' || Auth::user()->privilegios()->where('nombre_priv', 'admin_basico')->exists())
                     <th>Acciones</th>
                     @endif
                 </tr>
             </thead>
             <tbody>
                 @foreach($items as $item)
                 <tr>
                     <td>{{ $item->fotografia->nombre_foto }}</td>
                     <td>{{ $item->fotografia->reportaje->codigo }}</td>
                     <td>{{ ucfirst($item->formato->nombre_format) }}</td>
                     <td>{{ ucfirst($item->soporte->nombre_soport) }}</td>
                     <td>{{ $item->precio }} €</td>
                     <td>{{ $item->cantidad }}</td>
                     <td>{{ $item->precio_total }} €</td>
                     @if($pedido->estado_pedido === 'emitido' || $pedido->estado_pedido === 'presupuestado' || Auth::user()->privilegios()->where('nombre_priv', 'admin_basico')->exists())
                     <td>
                         <form method="POST" action="{{ route('borraritempedido', $item->id) }}" style="display:inline;" onsubmit="return confirm('¿Desea eliminar este item del pedido?')">
                             @csrf
                             @method('DELETE')
                             <button class="btn-borrar" type="submit">Eliminar Item</button>
                         </form>
                     </td>
                     @endif

                 </tr>
                 @endforeach
             </tbody>
         </table>
     </div>
 </div>
 <br>
 <h3>Precio Total del Pedido: {{ $precioPedido }} €</h3>
 <br>
 <p><strong>Estado del Pedido:</strong> {{ ucfirst($pedido->estado_pedido) }}</p>
 <p><strong>Fecha del Pedido:</strong> {{ $pedido->created_at->format('d/m/Y H:i') }}</p>
 @if(Auth::user()->privilegios()->where('nombre_priv', 'admin_basico')->exists() )
 <p><strong>Cliente:</strong> {{ $pedido->user->name }} ({{ $pedido->user->email }})</p>
 @endif
 @if(($pedido->estado_pedido === 'emitido') || ($pedido->estado_pedido === 'presupuestado' || Auth::user()->privilegios()->where('nombre_priv', 'admin_basico')->exists()))
 <br>
 <form method="POST" action="{{ route('borrarpedido', $pedido->id) }}">
     @csrf
     @method('DELETE')
     <button class="btn-borrar" type="submit" onclick="return confirm('¿Desea cancelar este pedido?')">Cancelar Pedido</button>
 </form>
 @endif
 @else
 <p>No hay items en este pedido.</p>
 @endif

 <div class="redirector-publi-priv">
     @auth
     <a class="tw-enlace" href="{{ route('zonaprivada') }}">Ir a <b>Zona Privada</b></a>
     <p> | </p>
     @endauth
     <a class="tw-enlace" href="{{ route('zonapublica') }}">Ir a <b>Zona Pública</b></a>
 </div>
 @endsection