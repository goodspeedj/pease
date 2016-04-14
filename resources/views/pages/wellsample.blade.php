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
        <th>6:2 FTS</th>
        <th>8:2 FTS</th>
        <th>EtFOSA</th>
        <th>EtFOSE</th>
        <th>MeFOSA</th>
        <th>MeFOSE</th>
        <th>PFBS</th>
        <th>PFBA</th>
        <th>PFDS</th>
        <th>PFDA</th>
        <th>PFDoA</th>
        <th>PFHpS</th>
        <th>PFHpA</th>
        <th>PFHxS</th>
        <th>PFHxA</th>
        <th>PFNA</th>
        <th>PFOSA</th>
        <th>PFOS</th>
        <th>PFOA</th>
        <th>PFPeA</th>
        <th>PFTeDA</th>
        <th>PFTrDA</th>
        <th>PFUnA</th>
      </thead>
      <tbody>
        <tr style="background-color: #FFF5EE">
          <td class="bold">Provisional Health Advisory</td>
          <td align="center">-</td>
          <td align="center">-</td>
          <td align="center">-</td>
          <td align="center">-</td>
          <td align="center">-</td>
          <td align="center">-</td>
          <td align="center">-</td>
          <td align="center">-</td>
          <td align="center">-</td>
          <td align="center">-</td>
          <td align="center">-</td>
          <td align="center">-</td>
          <td align="center">-</td>
          <td align="center">-</td>
          <td align="center">-</td>
          <td align="center">-</td>
          <td align="center">-</td>
          <td class="bold" align="center">0.2</td>
          <td class="bold" align="center">0.4</td>
          <td align="center">-</td>
          <td align="center">-</td>
          <td align="center">-</td>
          <td align="center">-</td>
        </tr>
        {{{ $wellSamples[20]->PFDoA or 'Default'  }}}
        @foreach ($wellSamples as $wellSample)


          
          
          <tr>
            <td width="400px" align="center">{{ $wellSample->sampleDate }}</td>
            <td align="center">{{ checkForZeroAndBlank($wellSample->FTS62) }} <sub>{{ $wellSample->FTS62Note }}</sub></td>
            <td align="center">{{ checkForZeroAndBlank($wellSample->FTS82) }} <sub>{{ $wellSample->FTS82Note }}</sub></td>
            <td align="center">{{ checkForZeroAndBlank($wellSample->EtFOSA) }} <sub>{{ $wellSample->EtFOSANote }}</sub></td>
            <td align="center">{{ checkForZeroAndBlank($wellSample->EtFOSE) }} <sub>{{ $wellSample->EtFOSENote }}</sub></td>
            <td align="center">{{ checkForZeroAndBlank($wellSample->MeFOSA) }} <sub>{{ $wellSample->MeFOSANote }}</sub></td>
            <td align="center">{{ checkForZeroAndBlank($wellSample->MeFOSE) }} <sub>{{ $wellSample->MeFOSENote }}</sub></td>
            <td align="center">{{ checkForZeroAndBlank($wellSample->PFBS) }} <sub>{{ $wellSample->PFBSNote }}</sub></td>
            <td align="center">{{ checkForZeroAndBlank($wellSample->PFBA) }} <sub>{{ $wellSample->PFBANote }}</sub></td>
            <td align="center">{{ checkForZeroAndBlank($wellSample->PFDS) }} <sub>{{ $wellSample->PFDSNote }}</sub></td>
            <td align="center">{{ checkForZeroAndBlank($wellSample->PFDA) }} <sub>{{ $wellSample->PFDANote }}</sub></td>
            <td align="center">{{ checkForZeroAndBlank($wellSample->PFDoA) }}<sub>{{ $wellSample->PFDoANote }}</sub></td>
            <td align="center">{{ checkForZeroAndBlank($wellSample->PFHpS) }} <sub>{{ $wellSample->PFHpSNote }}</sub></td>
            <td align="center">{{ checkForZeroAndBlank($wellSample->PFHpA) }} <sub>{{ $wellSample->PFHpANote }}</sub></td>
            <td align="center">{{ checkForZeroAndBlank($wellSample->PFHxS) }} <sub>{{ $wellSample->PFHxSNote }}</sub></td>
            <td align="center">{{ checkForZeroAndBlank($wellSample->PFHxA) }} <sub>{{ $wellSample->PFHxANote }}</sub></td>
            <td align="center">{{ checkForZeroAndBlank($wellSample->PFNA) }} <sub>{{ $wellSample->PFNANote }}</sub></td>
            <td align="center">{{ checkForZeroAndBlank($wellSample->PFOSA) }} <sub>{{ $wellSample->PFOSANote }}</sub></td>
            <td align="center">{{ checkForZeroAndBlank($wellSample->PFOS) }} <sub>{{ $wellSample->PFOSNote }}</sub></td>
            <td align="center">{{ checkForZeroAndBlank($wellSample->PFOA) }} <sub>{{ $wellSample->PFOANote }}</sub></td>
            <td align="center">{{ checkForZeroAndBlank($wellSample->PFPeA) }} <sub>{{ $wellSample->PFPeANote }}</sub></td>
            <td align="center">{{ checkForZeroAndBlank($wellSample->PFTeDA) }} <sub>{{ $wellSample->PFTeDANote }}</sub></td>
            <td align="center">{{ checkForZeroAndBlank($wellSample->PFTrDA) }} <sub>{{ $wellSample->PFTrDANote }}</sub></td>
            <td align="center">{{ checkForZeroAndBlank($wellSample->PFUnA) }} <sub>{{ $wellSample->PFUnANote }}</sub></td>
          </tr>
        @endforeach
      </tbody>
    </table>

    <small>
      Notes: <br />
      All concentrations in &#181;g/L - micrograms per liter.<br /> 
      0.0001 micrograms per liter equate to 100 parts per trillion<br />
      D - duplicate sample<br />
      J - The result is an estimated value<br />
      B - Detected in Blank<br />
      NA - Not Analyzed<br />
      ND - Not Detected
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

        $('td:contains("NA"),td:contains("ND")').css('color', 'gray');

        $(document).ready(function() {
            $('#table').DataTable( {
                rowCallback: function(row, data, index) {
                  data.forEach(function(d, i) {
                    if (index > 0) {
                        if (parseFloat(d) > 1 && i > 0 && d != null) { 
                          $(row).find('td:contains(' + parseFloat(d) + ')').css({'background-color': '#67000d', 'color': 'white'})  
                        } 
                        else if (parseFloat(d) > 0.2 && i > 0 && d != null) { 
                          $(row).find('td:contains(' + parseFloat(d) + ')').css({'background-color': '#a50f15', 'color': 'white'})  
                        }
                        else if (parseFloat(d) > 0.1 && i > 0 && d != null) { 
                          $(row).find('td:contains(' + parseFloat(d) + ')').css('background-color', '#cb181d')  
                        }
                        else if (parseFloat(d) > 0.05 && i > 0 && d != null) { 
                          $(row).find('td:contains(' + parseFloat(d) + ')').css('background-color', '#ef3b2c')  
                        }
                        else if (parseFloat(d) > 0.009 && i > 0 && d != null) { 
                          $(row).find('td:contains(' + parseFloat(d) + ')').css('background-color', '#fb6a4a')  
                        }
                        else if (parseFloat(d) > 0.005 && i > 0 && d != null) { 
                          $(row).find('td:contains(' + parseFloat(d) + ')').css('background-color', '#fc9272')  
                        }
                        else if (parseFloat(d) > 0.001 && i > 0 && d != null) { 
                          $(row).find('td:contains(' + parseFloat(d) + ')').css('background-color', '#fcbba1')  
                        }
                        else if (parseFloat(d) > 0.0005 && i > 0 && d != null) { 
                          $(row).find('td:contains(' + parseFloat(d) + ')').css('background-color', '#fee0d2')  
                        }
                        else {
                          console.log("ba");
                        }
                    }
                    
                  })  
                },
                "columnDefs": [
                  {
                    "targets": [ 1 ],
                    "visible": false
                  },
                  {
                    "targets": [ 2 ],
                    "visible": false
                  },
                  {
                    "targets": [ 3 ],
                    "visible": false
                  },  
                  {
                    "targets": [ 4 ],
                    "visible": false
                  },              
                  {
                    "targets": [ 5 ],
                    "visible": false
                  },
                  {
                    "targets": [ 6 ],
                    "visible": false
                  },
                  {
                    "targets": [ 21 ],
                    "visible": false
                  },
                  {
                    "targets": [ 22 ],
                    "visible": false
                  }       
                ],
                "order": [[ 0, 'desc' ], [1, 'asc']],
                "pageLength": 20,
                "lengthMenu": [ [20, 50, 100, -1], [20, 50, 100, "All"] ]
            } );



            $('#wells-button').text(selected).append(' <span class="caret"></span>');
        } );

    </script>

@stop