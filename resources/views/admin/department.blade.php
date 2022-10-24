@extends('adminlte::page')

@section('title', 'HRIS - Web')


@section('content')
    <div id="app">
        <department-component></department-component>
    </div>
@stop


@section('js')
    <script src="{{ asset('js/app.js') }}"></script>
@stop   