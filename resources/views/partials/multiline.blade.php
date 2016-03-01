function multilineChart() {

    //var dimKey;

    // Set the dimensions of the canvas / graph
    var margin = {top: 30, right: 200, bottom: 70, left: 50},
        width = 1200 - margin.left - margin.right,
        height = 500 - margin.top - margin.bottom;

    // Parse the date / time
    var parseDate = d3.time.format("%Y-%m-%d").parse;
    var hoverDate = d3.time.format("%m-%d-%y");

    // Set the ranges
    var x = d3.time.scale().range([0, width]);
    var y = d3.scale.linear().range([height, 0]);
    var color = d3.scale.category20();

    var xAxis = d3.svg.axis()
      .scale(x)
      .tickFormat(d3.time.format("%m-%d"))
      .orient("bottom");

    var yAxis = d3.svg.axis()
      .scale(y)
      .orient("left");

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

    function chart(selection) {
        selection.each(function(data) {

            // clean up data
            data.forEach(function(d) {
                d.sampleDate = parseDate(d.sampleDate);
                d.pfcLevel = +d.pfcLevel;
                //d.visible = 1;
            });

            // nest data
            var nested_data = d3.nest()
                .key(function(d) { return dimKey(d); })
                .entries(data);

            nested_data.forEach(function(d) {
                d.visible = 1;
            });

            console.log(nested_data);

            color.domain(data.map(function(d) {
                return dimKey(d);
            }));



            var line = d3.svg.line()
              .interpolate("linear")
              .x(function(d) { return x(d.sampleDate); })
              .y(function(d) { return y(d.pfcLevel); });


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
                      .style("stroke", function(d) { return color(dimKey(d)); });

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
                .attr("stroke", function(d) { return color(dimKey(d)); })
                .attr("fill", function(d, i) { return "white" })
                .attr("cx", function(d, i) { return x(d.sampleDate) })
                .attr("cy", function(d, i) { return y(d.pfcLevel) })
                .attr("r", function(d, i) { return 2 })
                .attr("class", function(d) { return dimKey(d); })
                .on("mouseover", function(d) {

                    d3.select(this).transition().duration(200)
                        .attr("r", function(d, i) { return 4 })

                    // Show tooltips
                    tooltipDetail.transition().duration(200)
                        .style("opacity", 0.8);
                    tooltipDetail
                        .html("<strong>" + dimKey(d) + "</strong><br />" + d.pfcLevel + "<br />" + hoverDate(new Date(d.sampleDate)))
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
                .attr("fill", function(d) { return color(d.key); })
                .on("click", function(d) {

                    var selectedPath = svg.select("path." + d.key);
                    //var totalLength = selectedPath.node().getTotalLength();

                    if (d.visible === 1) {
                        d.visible = 0;
                    } else {
                        d.visible = 1;
                    }

                    rescaleY();
                    updateLines();
                    updateCircles();

                    svg.select("rect." + d.key).transition().duration(500)
                        .attr("fill", function(d) {
                            if (d.visible === 1) {
                                return color(d.key);
                            } else {
                                return "white";
                            }
                        })

                    svg.select("path." + d.key).transition().duration(500)
                        .delay(150)
                        .style("display", function(d) {
                            if(d.visible === 1) {
                                return "inline";
                            }
                            else return "none";
                        })
                        .attr("d", function(d) {
                            return line(d.values);
                        });

                    svg.selectAll("circle." + d.key).transition().duration(500)
                        //.delay(function(d, i) { return i * 10; })
                        .style("display", function(a) {
                            if(d.visible === 1) {
                                return "inline";
                            }
                            else return "none";
                        });


                });

            legend.append("text")
                .attr("class", "legendLabel")
                .attr("x", function(d) { return width + 65; })
                .attr("y", function(d,i) { return height - 342 + (i*30); })
                .text( function(d) { return d.key; })
                .attr("font-size", "11px")
                .attr("fill", "black");


            // Get the maximum Y value
            function getMaxY() {
                var maxY = -1;
                nested_data.forEach(function(d) { 
                    if (d.visible === 1) {
                        d.values.forEach(function(d) {
                            if (d.pfcLevel > maxY) {
                                maxY = d.pfcLevel;
                            }
                        });
                    }
                });
                return maxY;
            }

            // re-scale the Y axis
            function rescaleY() {
                y.domain([0, getMaxY()]);
                svg.select(".y").transition()
                    .duration(1000).ease("sin-in-out")
                    .call(yAxis);
            }

             // Helper function for updating the lines after selecting/deselecting on the legend
            function updateLines() {
                svg.selectAll(".line")
                    .transition().duration(500)
                    .delay(function(d, i) { return i * 20; })
                    .attr("d", function(d) {
                        return line(d.values);
                    });
            }

            function updateCircles() {
                svg.selectAll(".circle circle")
                    .transition().duration(500)
                    .delay(function(d, i) { return i; })
                    .attr("cy", function(d, i) { return y(d.pfcLevel) });
            }

        });

    }

    // Get/set the dimension key
    chart.dimKey = function(value) {
        if (!arguments.length) return dimKey;
        dimKey = value;
        return chart;
    };

    return chart;

}