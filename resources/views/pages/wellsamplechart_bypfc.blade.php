@extends('layouts.master')

@section('title', 'Well Sample')

@section('custom_css')
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

      @include('partials.chartCommon')
      @include('partials.multiline')

      var chart = multilineChart()
            .dimKey(function(d) { return d.wellName; })
            .sortBy("wellID");

      d3.select("#chart").datum(data).call(chart);

    </script>  
@stop

@section('custom_js')

@stop