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
        stroke: steelblue;
        stroke-width: 1.5px;
      }
    </style>

    @include('partials.d3JS')
@stop

@section('content')
    <h3>Well Testing Samples</h3>

    <div id="chart"></div>

    <script>

      var data = <?php echo $wellSamples ?>;

      console.log(data);

      var uniqDates = d3.map(data, function(d) { return d.sampleDate; }).size();

      // Set the dimensions of the canvas / graph
      var margin = {top: 30, right: 20, bottom: 70, left: 50},
          width = 900 - margin.left - margin.right,
          height = 400 - margin.top - margin.bottom;

      // Parse the date / time
      var parseDate = d3.time.format("%Y-%m-%d").parse;

      // Set the ranges
      var x = d3.time.scale().range([0, width]);
      var y = d3.scale.linear().range([height, 0]);

      var xAxis = d3.svg.axis()
          .scale(x)
          .tickFormat(d3.time.format("%m-%d"))
          .orient("bottom");

      var yAxis = d3.svg.axis()
          .scale(y)
          .orient("left");

      var line = d3.svg.line()
          .interpolate("basis")
          .x(function(d) { console.log(d.sampleDate); return x(d.sampleDate); })
          .y(function(d) { console.log(d.pfcLevel); return y(d.pfcLevel); });

      var svg = d3.select("#chart").append("svg")
            .attr("width", width + margin.left + margin.right)
            .attr("height", height + margin.top + margin.bottom)
          .append("g")
            .attr("transform", "translate(" + margin.left + "," + margin.top + ")");

      x.domain(d3.extent(data, function(d) { return parseDate(d.sampleDate); }));
      y.domain(d3.extent(data, function(d) { return d.pfcLevel; }));

      svg.append("g")
          .attr("class", "x axis")
          .attr("transform", "translate(0," + height + ")")
          .call(xAxis)
          .selectAll("text")  
            .style("text-anchor", "end")
            .attr("dx", "-.8em")
            .attr("dy", ".15em")
            .attr("transform", function(d) {
                return "rotate(-65)" 
                });

      svg.append("g")
          .attr("class", "y axis")
          .call(yAxis)
        .append("text")
          .attr("transform", "rotate(-90)")
          .attr("y", 6)
          .attr("dy", ".71em")
          .style("text-anchor", "end")
          .text("PFC Level");

      
      var pfc = svg.selectAll(".pfc")
          .data(data)
        .enter().append("g")
          .attr("class", "pfc");

      pfc.append("path")
          .attr("class", "line")
          .attr("d", line)
          .style("stroke", "green");

    </script>  
@stop

@section('custom_js')

@stop