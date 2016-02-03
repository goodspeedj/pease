@extends('layouts.master')

@section('title', 'Participants')

@section('custom_css')
    @include('partials.datatablesCSS')
@stop

@section('content')
    <h3>Blood Sample Participants</h3>

    <table id="table" class="table table-striped table-bordered" cellspacing="0" width="100%">
      <thead>
        <th>Participant ID</th>
        <th>PFOA</th>
        <th>PFOS</th>
        <th>PFHxS</th>
        <th>PFOSA</th>
        <th>PFNA</th>
        <th>PFPeA</th>
        <th>PFHxA</th>
        <th>PFBA</th>
      </thead>
      <tbody>
        @foreach ($participants as $participant)
          <tr>
            <td>{{ $participant->participantRecordID }}</td>
            <td>{{ $participant->PFOA }}</td>
            <td>{{ $participant->PFOS }}</td>
            <td>{{ $participant->PFHxS }}</td>
            <td>{{ $participant->PFOSA }}</td>
            <td>{{ $participant->PFNA }}</td>
            <td>{{ $participant->PFPeA }}</td>
            <td>{{ $participant->PFHxA }}</td>
            <td>{{ $participant->PFBA }}</td>
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
                "pageLength": 20,
                "lengthMenu": [ [20, 50, 100, -1], [20, 50, 100, "All"] ]
            } );
        } );

    </script>
@stop