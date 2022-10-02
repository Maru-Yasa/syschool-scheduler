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
    @yield('css')
@stop

@section('js')

    @yield('js')

@stop