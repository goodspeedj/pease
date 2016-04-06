@extends('layouts.master')

@section('title', 'Well Sample')

@section('custom_css')
    @include('partials.datatablesCSS')
    <style>
      .axis path,
      .axis line {
        fill: none;
        stroke: #000;
        shape-rendering: crispEdges;
      }

      text {
        font-size: 75%;
      }

      .line {
        fill: none;
        stroke-width: 1.5px;
      }

      div.tooltipDetail {
        position: absolute;
        height: 50px;
        padding: 4px;
        font-size: 11px;
        background: rgba(192, 192, 192, 0.6);
        pointer-events: none;
        border-radius: 5px;
      }

      div.tooltipSummary {
        position: absolute;
        height: 20px;
        padding: 4px;
        font-size: 11px;
        background: rgba(192, 192, 192, 0.6);
        pointer-events: none;
        border-radius: 5px;
      }
    </style>

    @include('partials.d3JS')
@stop

@section('content')
    <h3>Well Testing Samples</h3>

    <div id="chart"></div>

    <script>

      //Raw data
      var data = <?php echo $wellSamples ?>;
      var dimensions = [
            { key: 'PFOA', value: 1 },
            { key: 'PFOS', value: 1 },
            { key: 'PFHxS', value: 1 },
            { key: 'PFHxA', value: 1 },
            { key: 'PFOSA', value: 1 },
            { key: 'PFBA', value: 1 },
            { key: 'PFBS', value: 0 },
            { key: 'PFDA', value: 0 },
            { key: 'PFDS', value: 1 },
            { key: 'PFDeA', value: 0 },
            { key: 'PFDoA', value: 0 },
            { key: 'PFHpS', value: 0 },
            { key: 'PFHpA', value: 0 },
            { key: 'PFNA', value: 0 },
            { key: 'PFPeA', value: 1 },
            { key: 'PFTeDA', value: 0 },
            { key: 'PFTrDA', value: 0 },
            { key: 'PFUnA', value: 0 },
            { key: '6:2 FTS', value: 0 },
            { key: '8:2 FTS', value: 0 },
            { key: 'EtFOSA', value: 0 },
            { key: 'EtFOSE', value: 0 },
            { key: 'MEFOSA', value: 0 },
            { key: 'MEFOSE', value: 0 }
          ];

      @include('partials.chartCommon')
      @include('partials.multiline')

      var chart = multilineChart()
            .dimKey(function(d) { return d.shortName; })
            .longDesc(function(d) {return d.longName; })
            .sortBy("chemID");

      d3.select("#chart").datum(data).call(chart);

    </script>  
@stop

@section('custom_js')

@stop