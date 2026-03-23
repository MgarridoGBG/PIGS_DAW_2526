{{-- resources/views/parciales/botonespaginas.blade.php
    Vista para mostrar los botones de paginación personalizados.
    Se incluye en las vistas de listados (usuarios, productos, etc.)
    para mostrar los enlaces de paginación.
--}}

 <div class="paginacion" style="display: flex; align-items: center; gap: 10px;">
     {{-- Enlace personalizado: Primera página --}}
     @if(!$objetos->onFirstPage())
     <a class="btn-estandar" href="{{ $objetos->url(1) }}">« Primera</a>
     @endif

     {{-- Enlace personalizado: Página anterior --}}
     @if($objetos->previousPageUrl())
     <a class="btn-estandar" href="{{ $objetos->previousPageUrl() }}">‹ Anterior</a>
     @else
     {{-- Opcional: mostrar texto deshabilitado --}}
     <span class="btn-deshabilitado">‹ Anterior</span>
     @endif

     {{-- Enlaces estándar de Laravel (los números de página) --}}
     {{-- $objetos->links()-- }}

     {{-- Enlace personalizado: Página siguiente --}}
     @if($objetos->nextPageUrl())
     <a class="btn-estandar" href="{{ $objetos->nextPageUrl() }}">Siguiente ›</a>
     @else
     {{-- Opcional: mostrar texto deshabilitado --}}
     <span class="btn-deshabilitado">Siguiente ›</span>
     @endif

     {{-- Enlace personalizado: Última página --}}
     @if($objetos->hasMorePages())
     <a class="btn-estandar" href="{{ $objetos->url($objetos->lastPage()) }}">Última »</a>
     @endif
 </div>

 {{-- Información adicional --}}
 <p class="info-pagina">Página {{ $objetos->currentPage() }} de {{ $objetos->lastPage() }}. Total de elementos: {{ $objetos->total() }}</p>
 