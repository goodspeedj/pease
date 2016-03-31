@extends('layouts.master')

@section('title', 'Well Sample')

@section('custom_css')
    @include('partials.datatablesCSS')
@stop

@section('content')
    <h3>Well Testing Samples</h3>

    <div class="dropdown">
      <strong>Well: </strong>
      <button class="btn btn-default dropdown-toggle" type="button" id="wells-button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="true">
        Wells
        <span class="caret"></span>
      </button>
      <ul id="well-select" class="dropdown-menu" aria-labelledby="wells-button">
        <li><a href="haven" class="active">Haven</a></li>
        <li><a href="smith">Smith</a></li>
        <li><a href="harrison">Harrison</a></li>
        <li><a href="wwtp">WWTP</a></li>
        <li><a href="des">DES</a></li>
      </ul>
    </div>

    <p>&nbsp;</p>

    <table id="table" class="table table-striped table-bordered small" cellspacing="0" width="100%">
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

        // Parse the pull down
        var pathname = window.location.pathname;
        var parts = pathname.split("/");
        var lastRoute = parts[parts.length - 1];
        var selected = $("#well-select li a[href|="+lastRoute+"]").text();

        $(document).ready(function() {
            $('#table').DataTable( {
                "order": [[ 0, 'desc' ], [1, 'asc']],
                "pageLength": 20,
                "lengthMenu": [ [20, 50, 100, -1], [20, 50, 100, "All"] ]
            } );



            $('#wells-button').text(selected).append(' <span class="caret"></span>');
        } );

    </script>

@stop