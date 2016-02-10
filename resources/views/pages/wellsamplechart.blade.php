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

      // Set the dimensions of the canvas / graph
      var margin = {top: 30, right: 100, bottom: 70, left: 50},
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
      var tooltipDetail = d3.select("#chart").append("div")
          .attr("class", "tooltipDetail")
          .style("opacity", 0);

      var tooltipSummary = d3.select("#chart").append("div")
          .attr("class", "tooltipSummary")
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

      
      // bind data to the lines
      var lines = svg.selectAll(".lines")
          .data(nested_data)
        .enter().append("g")
          .attr("class", "lines");

      // Add the paths for the lines
      lines.append("path")
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

              d3.selectAll("circle." + d.key).transition().duration(200)
                  .attr("r", function(d, i) { return 4 })

              // Fade out the other lines
              var otherlines = $(".line").not("path." + d.key);
              d3.selectAll(otherlines).transition().duration(200)
                  .style("opacity", 0.3)
                  .style("stroke-width", 1.5)
                  .style("stroke", "gray");

              var othercircles = $("circle").not("circle." + d.key);
              d3.selectAll(othercircles).transition().duration(200)
                  .style("opacity", 0.3)
                  .style("stroke", "gray");

              // Show tooltips
              tooltipSummary.transition().duration(200)
                  .style("opacity", 0.8);
              tooltipSummary
                  .html(d.key)
                    .style("left", (d3.event.pageX + 10) + "px")
                    .style("top", (d3.event.pageY - 25) + "px");
          })
          .on("mouseout", function(d) {
              // Make the line normal again
              d3.select(this).transition().duration(100)
                  .style("stroke-width", "1.5px");

              d3.selectAll("circle." + d.key).transition().duration(100)
                  .attr("r", function(d, i) { return 2 })

              // Make the other lines normal again
              var otherlines = $('.line').not("path." + d.key);
              d3.selectAll(otherlines).transition().duration(100)
                  .style("opacity", 1)
                  .style("stroke-width", 1.5)
                  .style("stroke", function(d) { return color(d.key); });

              var othercircles = $("circle").not("circle." + d.key);
              d3.selectAll(othercircles).transition().duration(200)
                  .style("opacity", 1)
                  .style("stroke", function(d) { return color(d.shortName); });

              // Hide the tooltip
              tooltipSummary.transition().duration(500).style("opacity", 0);
          });

      // bind the data for the circles
      var circles = svg.selectAll(".circle")
          .data(data)
        .enter().append("g")
          .attr("class", "circle");

      // Add the circles to the lines
      circles.append("circle")
          .attr("stroke", function(d) { return color(d.shortName); })
          .attr("fill", function(d, i) { return "white" })
          .attr("cx", function(d, i) { return x(d.sampleDate) })
          .attr("cy", function(d, i) { return y(d.pfcLevel) })
          .attr("r", function(d, i) { return 2 })
          .attr("class", function(d) { return d.shortName; })
          .on("mouseover", function(d) {

              d3.select(this).transition().duration(200)
                  .attr("r", function(d, i) { return 4 })

              // Show tooltips
              tooltipDetail.transition().duration(200)
                  .style("opacity", 0.8);
              tooltipDetail
                  .html("<strong>" + d.shortName + "</strong><br />" + d.pfcLevel + "<br />" + hoverDate(new Date(d.sampleDate)))
                    .style("left", (d3.event.pageX + 10) + "px")
                    .style("top", (d3.event.pageY - 25) + "px");
          })
          .on("mouseout", function(d) {
              d3.select(this).transition().duration(100)
                  .attr("r", function(d, i) { return 2 })

              // Hide the tooltip
              tooltipDetail.transition().duration(500).style("opacity", 0);
          });

      // bind the data for the legend
      var legend = svg.selectAll(".legend")
            .data(nested_data)
          .enter().append("g")
            .attr("class", "legend");

      // Add the colored legend boxes
      legend.append("rect")
            .attr("height",10)
            .attr("width", 25)
            .attr("class", function(d) { return d.key; })
            .attr("x", width + 30)
            .attr("y", function(d,i) { return height - 350 + (i*30); })
            .attr("stroke", function(d) { return color(d.key);})
            .attr("fill", function(d) { return color(d.key); });

      legend.append("text")
            .attr("class", "legendLabel")
            .attr("x", function(d) { return width + 65; })
            .attr("y", function(d,i) { return height - 342 + (i*30); })
            .text( function(d) { return d.key; })
            .attr("font-size", "11px")
            .attr("fill", "black");


    </script>  
@stop

@section('custom_js')

@stop