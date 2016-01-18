@extends('layouts.master')

@section('title', 'PFC Studies')

@section('custom_css')
    @include('partials.datatablesCSS')
@stop

@section('content')
    <p>PFC Studies</p>

    <table id="table" class="table table-striped table-bordered" cellspacing="0" width="100%">
      <thead>
        <th>Name</th>
        <th>Start</th>
        <th>End</th>
        <th>Participants</th>
        <th>Type</th>
      </thead>
      <tbody>
        @foreach ($studies as $study)
          <tr>
            <td>{{ $study->studyName }}</td>
            <td>{{ $study->studyStartDate }}</td>
            <td>{{ $study->studyEndDate }}</td>
            <td>{{ $study->participants }}</td>
            <td>{{ $study->exposureID }}</td>
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
            "order": [[1, 'asc']],
            "pageLength": 20
        } );
    } );
    </script>

@stop