 {{-- resources/views/parciales/paginadofotospublicas.blade.php
    Vista para mostrar el listado de fotografías públicas paginadas en una galería
    incluyendo las imágenes en miniatura obtenidas de Storage
    y opciones de gestión según los privilegios del usuario.
--}}

@if($fotografias->count())
 <ul class="galeria-grid">
     {{-- Iteramos sobre la colección paginada --}}
     @foreach($fotografias as $foto)
     <li class="galeria-item">

         <a href="{{ route('zonapublicamostrarfotopublica', $foto->id) }}" target="_blank">
             <img aria-label="Miniatura de {{ $foto->nombre_foto }}" src="{{ route('fotopublicastorage', $foto->reportaje->codigo . '/thumbs/' . $foto->nombre_foto) }}"
                 alt="{{ $foto->nombre }}">
             <span class="galeria-info">{{ $foto->nombre_foto }}</span>
         </a>

         @auth
         @if(Auth::user()->privilegios()->where('nombre_priv', 'admin_basico')->exists())
         <div class="galeria-acciones">
             <form  method="POST" action="{{ route('editarfotografia', $foto->id) }}" >
                 @csrf
                 @method('PUT')
                 <input type="text" name="nombre_foto" value="{{ $foto->nombre_foto }}" required>
                 <button class="btn-estandar" type="submit">Renombrar</button>
             </form>
             <form method="POST" action="{{ route('borrarfotografia', $foto->id) }}" style="display:inline;"
                 onsubmit="return confirm('¿Borrar esta foto?\nEsta acción no se puede deshacer.')">
                 @csrf
                 @method('DELETE')
                 <button type="submit" class="btn-borrar">Borrar</button>
             </form>
         </div>
         @endif
         @if(Auth::user()->privilegios()->where('nombre_priv', 'hacer_pedido')->exists())
         <div class="galeria-acciones">
             <form method="POST" action="{{ route('mostrarformcarrito', $foto->id) }}" target="_blank">
                 @csrf
                 <button class="btn-carrito" type="submit">Añadir al carrito</button>
             </form>
         </div>
         @endif
         @endauth

         @guest
         <div class="galeria-acciones">
             <a class="tw-enlace-animado" href="{{ route('login') }}">Inicie sesión para hacer pedido</a>
         </div>
         @endguest

     </li>
     @endforeach
 </ul>

 <!-- Incluimos la vista parcial para los botones de paginación personalizados-->
 @include('parciales.botonespaginas', ['objetos' => $fotografias])

 @else
 <p>No hay fotografías para mostrar.</p>
 @endif