@extends('layouts.master')

@section('title', 'Well Sample')

@section('custom_css')
    @include('partials.datatablesCSS')
@stop

@section('content')
    <p>Well Sample</p>

    <table id="table" class="table table-striped table-bordered" cellspacing="0" width="100%">
      <thead>
        <th>Date</th>
        <th>Chemical</th>
        <th>PFC Level</th>
      </thead>
      <tbody>
        @foreach ($wellSamples as $wellSample)
          <tr>
            <td>{{ $wellSample->sampleDate }}</td>
            <td>{{ $wellSample->chemID }}</td>
            <td>{{ $wellSample->pfcLevel }}</td>
          </tr>
        @endforeach
      </tbody>
    </table>
    
@stop

@section('custom_js')

    @include('partials.datatablesJS')

    <script>
    $(document).ready(function() {
        $('#table').DataTable( {
            "order": [[ 0, 'desc' ], [1, 'asc']],
            "pageLength": 20
        } );
    } );
    </script>

@stop