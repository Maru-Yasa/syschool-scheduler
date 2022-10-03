@extends('adminlte::page')

@section('title', 'Dashboard')

@section('content_header')
    @vite(['resources/js/app.js', 'resources/css/app.css'])
    <meta name="csrf-token" content="{{ csrf_token() }}" />
    @yield('header')
@stop

@section('content')
    @yield('content')
@stop

@section('css')
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&display=swap');

        body {
            font-family: 'Poppins', sans-serif !important;
        }
    </style>
@stop

@section('js')

    @yield('js')

@stop