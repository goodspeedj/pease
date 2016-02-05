@extends('layouts.master')

@section('title', 'Well Sample')

@section('custom_css')
    @include('partials.datatablesCSS')
@stop

@section('content')
    <h3>Well Testing Samples</h3>

    <p>{{ $wellSamples }}</p>    
@stop

@section('custom_js')

    <script src="//d3js.org/d3.v3.min.js" charset="utf-8"></script>

@stop