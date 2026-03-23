{{-- resources/views/parciales/itemanadidocarrito.blade.php
    Vista para mostrar al añadir un item al carrito.
--}}

@extends('layouts.app')

@push('styles')
@vite(['resources/css/paginas/listados.css'])
@endpush

@section('title', 'Carrito - Pedido en Curso')

@section('content')

@if(count($items) > 0)

@php $ultimo = end($items); @endphp
<h2 class="titular-listado">Fotografia<i> {{ $ultimo['nombre_fotografia'] }}</i> del reportaje <i>{{ $ultimo['reportaje_codigo'] }}</i> añadida al pedido. PRECIO: {{ $ultimo['precio'] }} €</h2>

@include('parciales.listados.listaritemscarrito', ['items' => $items])

@else
<p>Vaya, tu carrito está triste y vacío.</p>
@endif
@endsection