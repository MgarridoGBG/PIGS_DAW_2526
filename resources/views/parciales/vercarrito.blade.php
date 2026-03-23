 {{-- resources/views/parciales/vercarrito.blade.php
    Vista para mostrar el carrito del usuario, incluyendo la vista parcial
    de items presentes en la sesion.
--}}

@extends('layouts.app')

@push('styles')
@vite(['resources/css/paginas/listados.css'])
@endpush

@section('title', 'Carrito - Pedido en Curso')

@section('content')

<h2 class="titular-listado">Tu pedido en curso</h2>

@include('parciales.listados.listaritemscarrito', ['items' => $items])

@endsection

