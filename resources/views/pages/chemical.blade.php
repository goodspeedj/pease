@extends('layouts.master')

@section('title', 'PFC Chemicals')

@section('custom_css')
    <link rel="stylesheet" href="https://cdn.datatables.net/1.10.10/css/dataTables.bootstrap.min.css">
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

    <script src="https://cdn.datatables.net/1.10.10/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.10.10/js/dataTables.bootstrap.min.js"></script>

    <script>
    $(document).ready(function() {
        $('#table').DataTable( {
            "order": [[ 0, 'desc' ], [1, 'asc']],
            "pageLength": 20
        } );
    } );
    </script>

@stop