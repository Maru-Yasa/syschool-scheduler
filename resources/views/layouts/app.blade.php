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
    <script>
        toastr.options.closeButton = true;
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
    </script>
    @yield('js')
@stop