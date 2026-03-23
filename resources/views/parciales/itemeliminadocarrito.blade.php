{{-- resources/views/parciales/itemeliminadocarrito.blade.php
    Vista para mostrar al eliminar un item del carrito.
--}}

@extends('layouts.app')

@push('styles')
@vite(['resources/css/paginas/listados.css'])
@endpush

@section('title', 'Carrito - Pedido en Curso')

@section('content')

<h2 class="titular-listado"> Item Eliminado del carrito</h2>

@include('parciales.listados.listaritemscarrito', ['items' => $items])

@if(count($items) > 0)
@php $ultimo = end($items); @endphp
@else
<p>Vaya, tu carrito está triste y vacío.</p>
@endif
@endsection