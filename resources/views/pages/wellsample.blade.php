@extends('layouts.master')

@section('title', 'Well Sample')

@section('content')
    <p>Well Sample</p>

    @foreach ($wellSamples as $wellSample)
      <p>{{ $wellSample->pfcLevel }}</p>
    @endforeach
@stop
