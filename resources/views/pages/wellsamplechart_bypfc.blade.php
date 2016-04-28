@extends('layouts.master')

@section('title', 'Well Sample')

@section('custom_css')
    @include('partials.d3JS')
    <link rel="stylesheet" href="{{ URL::asset('css/main.css') }}">
@stop

@section('content')

    <h4 id="title">{{ $chem[0]->shortName }} Samples by Well</h4>
    <h5>{{ $chem[0]->longName }}</h5>
    <hr>
    <p>The chart below shows the PFC levels for {{ $chem[0]->shortName }} from the well samples taken since
       April 2014.</p>
    <p>To use the chart click on the legend rectangles to enable or disable different wells.  Hovering over
       a data point will display details about that specific sample.</p>


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
          { key: 'SMW-A', value: 1 },
          { key: 'SMW-1', value: 1 },
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