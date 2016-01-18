@extends('layouts.master')

@section('title', 'Well Sample')

@section('custom_css', '<link rel="stylesheet" href="https://cdn.datatables.net/1.10.10/css/jquery.dataTables.min.css">')

@section('content')
    <p>Well Sample</p>

    <table id="table" class="display" cellspacing="0" width="100%">
      <thead>
        <th>Chemical</th>
        <th>Date</th>
        <th>PFC Level</th>
      </thead>
      <tbody>
        @foreach ($wellSamples as $wellSample)
          <tr>
            <td>{{ $wellSample->chemID }}</td>
            <td>{{ $wellSample->pfcLevel }}</td>
            <td>{{ $wellSample->sampleDate }}</td>
          </tr>
        @endforeach
      </tbody>
    </table>

    
@stop

@section('custom_js')

    <script src="https://cdn.datatables.net/1.10.10/js/jquery.dataTables.min.js"></script>
    
    <script>
    $(document).ready(function() {
        $('#table').DataTable();
    } );
    </script>

@stop