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

      div.tooltip {
        position: absolute;
        height: 50px;
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

      // Set the dimensions of the canvas / graph
      var margin = {top: 30, right: 20, bottom: 70, left: 50},
          width = 1000 - margin.left - margin.right,
          height = 500 - margin.top - margin.bottom;

      // Parse the date / time
      var parseDate = d3.time.format("%Y-%m-%d").parse;
      var hoverDate = d3.time.format("%m-%d-%y");

      // clean up data
      data.forEach(function(d){
        d.sampleDate = parseDate(d.sampleDate);
        d.pfcLevel = +d.pfcLevel;
      });

      // nest data
      var nested_data = d3.nest()
        .key(function(d) { return d.shortName; })
        .entries(data);

      // Set the ranges
      var x = d3.time.scale().range([0, width]);
      var y = d3.scale.linear().range([height, 0]);
      var color = d3.scale.category20();

      color.domain(data.map(function(d) {
          return d.shortName;
      }));

      var xAxis = d3.svg.axis()
          .scale(x)
          .tickFormat(d3.time.format("%m-%d"))
          .orient("bottom");

      var yAxis = d3.svg.axis()
          .scale(y)
          .orient("left");

      var line = d3.svg.line()
          .interpolate("linear")
          .x(function(d) { return x(d.sampleDate); })
          .y(function(d) { return y(d.pfcLevel); });

      // Create the tooltip div
      var tooltip = d3.select("#chart").append("div")
          .attr("class", "tooltip")
          .style("opacity", 0);

      var svg = d3.select("#chart").append("svg")
            .attr("width", width + margin.left + margin.right)
            .attr("height", height + margin.top + margin.bottom)
          .append("g")
            .attr("transform", "translate(" + margin.left + "," + margin.top + ")");

      x.domain(d3.extent(data, function(d) { return d.sampleDate; }));
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

      
      // now we bind to nested_data, an array of arrays
      var pfc = svg.selectAll(".pfc")
          .data(nested_data)
        .enter().append("g")
          .attr("class", "pfc");

      pfc.append("path")
          .attr("class", function(d) {
              return "line " + d.key;
          })
          .attr("d", function(d){
              // our inner array is d.values from the nesting
              return line(d.values);
          })
          .style("stroke", function(d) { return color(d.key); })
          .on("mouseover", function(d) {
              // Make the line bold
              d3.select(this).transition().duration(200)
                  .style("stroke-width", "4px");
          })
          .on("mouseout", function(d) {
              // Make the line normal again
              d3.select(this).transition().duration(100)
                  .style("stroke-width", "1.5px");
          });

      var circles = svg.selectAll(".circle")
          .data(data)
        .enter().append("g")
          .attr("class", "circle");

      circles.append("circle")
          .attr("stroke", function(d) { return color(d.shortName); })
          .attr("fill", function(d, i) { return "white" })
          .attr("cx", function(d, i) { return x(d.sampleDate) })
          .attr("cy", function(d, i) { return y(d.pfcLevel) })
          .attr("r", function(d, i) { return 2 })
          .on("mouseover", function(d) {
              // Show tooltips
              tooltip.transition().duration(200)
                  .style("opacity", 0.8);
              tooltip
                  .html("<strong>" + d.shortName + "</strong><br />" + d.pfcLevel + "<br />" + hoverDate(new Date(d.sampleDate)))
                    .style("left", (d3.event.pageX + 10) + "px")
                    .style("top", (d3.event.pageY - 25) + "px");
          })
          .on("mouseout", function(d) {
              // Hide the tooltip
              tooltip.transition().duration(500).style("opacity", 0);
          });




    </script>  
@stop

@section('custom_js')

@stop