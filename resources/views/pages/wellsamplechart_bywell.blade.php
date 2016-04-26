@extends('layouts.master')

@section('title', 'Well Sample')

@section('custom_css')
    @include('partials.d3JS')
    <link rel="stylesheet" href="{{ URL::asset('css/main.css') }}">
@stop

@section('content')
    <h3 id="title">{{ $well[0]->wellDesc }} Samples by PFC</h3>
    @if ($well[0]->wellActive == 'Y')
      <h5>Status: Active</h5>
    @endif

    @if ($well[0]->wellActive == 'N')
      <h5>Status: Inactive</h5>
    @endif

    <h5>Type: {{ $well[0]->wellType }}</h5>

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
            { key: 'PFBS', value: 1 },
            { key: 'PFDA', value: 0 },
            { key: 'PFDS', value: 0 },
            { key: 'PFDeA', value: 0 },
            { key: 'PFDoA', value: 0 },
            { key: 'PFHpS', value: 1 },
            { key: 'PFHpA', value: 1 },
            { key: 'PFNA', value: 1 },
            { key: 'PFPeA', value: 1 },
            { key: 'PFTeDA', value: 1 },
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
            .sortBy("chemID")
            .seriesVal(function(d) {return d.shortName; });

      d3.select("#chart").datum(data).call(chart);

    </script>  
@stop

@section('custom_js')

@stop