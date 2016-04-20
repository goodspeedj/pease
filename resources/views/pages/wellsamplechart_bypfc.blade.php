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

    <h3 id="title">{{ $chem[0]->shortName }} Samples by Well</h3>
    <h4>{{ $chem[0]->longName }}</h4>


    <div id="chart"></div>

    <script>

       //Raw data
       var data = <?php echo $wellSamples ?>;

       var dimensions = [
          { key: 'Haven', value: 1 },
          { key: 'Smith', value: 1 },
          { key: 'Harrison', value: 1 },
          { key: 'Collins', value: 1 },
          { key: 'Portsmouth', value: 1 },
          { key: 'WWTP', value: 1 },
          { key: 'DES', value: 1 },
          { key: 'GBK_PRE', value: 1 },
          { key: 'GBK_POST1', value: 1 },
          { key: 'GBK_POST2', value: 1 },
          { key: 'DSC_PRE', value: 1 },
          { key: 'DSC_POST', value: 1 },
          { key: 'FIRESTATION', value: 0 },
          { key: 'CSW-1D', value: 0 },
          { key: 'CSW-1S', value: 0 },
          { key: 'CSW-2R', value: 0 },
          { key: 'HMW-3', value: 1 },
          { key: 'HMW-8R', value: 0 },
          { key: 'HMW-14', value: 1 },
          { key: 'HMW-15', value: 0 },
          { key: 'SMW-A', value: 0 },
          { key: 'SMW-1', value: 0 },
          { key: 'SMW-13', value: 0 },
          { key: 'PSW-1', value: 0 },
          { key: 'PSW-2', value: 0 }
        ];

       @include('partials.chartCommon')
       @include('partials.multiline')

       

       var chart = multilineChart()
        .dimKey(function(d) { return d.wellName; })
        .longDesc(function(d) {return d.wellDesc; })
        .sortBy("wellID")
        .dimensions(dimensions)
        .seriesVal(function(d) {return d.shortName; });

       d3.select("#chart").datum(data).call(chart);

    </script>  
@stop

@section('custom_js')

@stop