 {{-- resources/views/zonaprivada/privada.blade.php
    Vista para mostrar la zona privada del usuario, incluyendo el panel de administración
    correspondiente según el rol y privilegios del usuario.
--}}

@extends('layouts.app')

@push('styles')
@vite(['resources/css/paginas/privada.css'])
@endpush

@section('title', 'Zona Privada')
@push('scripts')
@vite(['resources/css/paginas/privada.css'])
@vite(['resources/js/paginas/privada.js'])
@endpush

@section('content')

@auth
<div class="privada-contenedor">

    <div class="privada-bienvenida">
        <p class="mb-[10px] mt-[5px] text-sm font-semibold uppercase tracking-wider text-secundario-tailwind">
            ¡Hola, {{ $usuario->nombre }}! Has accedido a tu zona privada con el rol de: {{ $usuario->role->nombre_role }}</p>
        <p class="mb-[10px] mt-[5px] text-sm font-semibold uppercase tracking-wider text-secundario-tailwind">
            Puedes ir a la <span><a class="tw-enlace-animado" href=" {{ route('zonapublica') }}">Zona Pública</a></span>, o <span><a class="tw-enlace-animado" href=" {{ route('logout') }}">Cerrar Sesión</a></span></span>.</p>
    </div>

    {{-- RECURSOS PROTEGIDOS SEGÚN ROL Y PRIVILEGIOS --}}
    <h2 class="privada-panel-titulo">Panel de administración</h2>

    @if($usuario->role->nombre_role == 'cliente')
    @include('parciales.dashcliente')

    @elseif($usuario->role->nombre_role == 'empleado')
    @include('parciales.dashempleado')

    @elseif($usuario->role->nombre_role == 'admin')
    @include('parciales.dashadmin')

    @endif

</div>
@endauth

@endsection