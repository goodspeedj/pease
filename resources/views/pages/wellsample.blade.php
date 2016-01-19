@extends('layouts.master')

@section('title', 'Well Sample')

@section('custom_css')
    @include('partials.datatablesCSS')
@stop

@section('content')
    <h3>Well Testing Samples</h3>

    <table id="table" class="table table-striped table-bordered" cellspacing="0" width="100%">
      <thead>
        <th>Date</th>
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
        @foreach ($wellSamples as $wellSample)
          <tr>
            <td>{{ $wellSample->sampleDate }}</td>
            <td>{{ $wellSample->PFOA }} <sub>{{ $wellSample->PFOANote }}</sub></td>
            <td>{{ $wellSample->PFOS }} <sub>{{ $wellSample->PFOSNote }}</sub></td>
            <td>{{ $wellSample->PFHxS }} <sub>{{ $wellSample->PFHxSNote }}</sub></td>
            <td>{{ $wellSample->PFOSA }} <sub>{{ $wellSample->PFOSANote }}</sub></td>
            <td>{{ $wellSample->PFNA }} <sub>{{ $wellSample->PFNANote }}</sub></td>
            <td>{{ $wellSample->PFPeA }} <sub>{{ $wellSample->PFPeANote }}</sub></td>
            <td>{{ $wellSample->PFHxA }} <sub>{{ $wellSample->PFHxANote }}</sub></td>
            <td>{{ $wellSample->PFBA }} <sub>{{ $wellSample->PFBANote }}</sub></td>
          </tr>
        @endforeach
      </tbody>
    </table>

    <small>
      Notes: <br />
      All concentrations in &#181;g/L - micrograms per liter<br />
      All values in micrograms per liter<br />
      D - duplicate sample<br />
      J - The result is an estimated value<br />
      B - Detected in Blank<br />
    </small>
    
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