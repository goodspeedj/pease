@extends('layouts.master')

@section('title', 'PFC Chemicals')

@section('custom_css')
    @include('partials.datatablesCSS')
@stop

@section('content')
    <p>Common PFC Chemicals</p>

    <table id="table" class="table table-striped table-bordered" cellspacing="0" width="100%">
      <thead>
        <th>Abbreviation</th>
        <th>Name</th>
        <th>EPA Provisional Health Advisory Level</th>
      </thead>
      <tbody>
        @foreach ($chemicals as $chemical)
          <tr>
            <td class="dt-center">{{ $chemical->shortName }}</td>
            <td>{{ $chemical->longName }}</td>
            <td>{{ $chemical->epaPHALevel }}</td>
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