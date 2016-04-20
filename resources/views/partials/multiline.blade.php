function multilineChart() {

    function chart(selection) {
        selection.each(function(data) {

            // clean up data
            data.forEach(function(d) {
                d.sampleDate = parseDate(d.sampleDate);
                d.pfcLevel = +d.pfcLevel;
            });

            // nest data
            var nested_data = d3.nest()
                .key(function(d) { return dimKey(d); })
                .entries(data);

                    
            nested_data.forEach(function(d) {
                dimensions.forEach(function(a) {
                    if (a.key === d.key) {
                        d.visible = a.value;
                    }
                });
            });
            
            nested_data.sort(function(a, b) {
                return a.values[0][sortBy] - b.values[0][sortBy];
            });

            var color = d3.scale.ordinal()
              .domain(d3.extent(nested_data, function(d) { return d.key; }))
              .range(["#ffffff", "#ef8ead", "#f28f99", "#f09287", "#e99778",
                      "#de9d6b", "#cfa464", "#beaa62", "#abb065", "#96b56e",
                      "#7fb97c", "#67bc8d", "#4bbea0", "#28beb4", "#00bec7",
                      "#00bcd8", "#13bae5", "#46b5ef", "#6cb0f3", "#8daaf2",
                      "#aaa3ec", "#c39ce0", "#d795d1", "#e691c0"]);

            color.domain(nested_data.map(function(d) {
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
              .text("PFC Level.  Micrograms per liter");

            svg.append("line")
              .attr("class", "pfoaEPAHA")
              .style("stroke", "red")
              .attr("stroke-dasharray","5,5")
              .attr("x1", 0)
              .attr("y1", y(epaPHA_PFOA))
              .attr("x2", width)
              .attr("y2", y(epaPHA_PFOA));

            svg.append("line")
              .attr("class", "pfosEPAHA")
              .style("stroke", "red")
              .attr("stroke-dasharray","10,10")
              .attr("x1", 0)
              .attr("y1", y(epaPHA_PFOS))
              .attr("x2", width)
              .attr("y2", y(epaPHA_PFOS));

            svg.append("text")
              .attr("class", "pfoaEPAHAText")
              .attr("x", width - 170)
              .attr("y", y(epaPHA_PFOA) - 10)
              .style("fill", "red")
              .text("EPA Public Health Advisory - PFOA");

            svg.append("text")
              .attr("class", "pfosEPAHAText")
              .attr("x", width - 170)
              .attr("y", y(epaPHA_PFOS) - 10)
              .style("fill", "red")
              .text("EPA Public Health Advisory - PFOS");


            // bind data to the lines
            var lines = svg.selectAll(".lines")
              .data(nested_data)
            .enter().append("g")
              .attr("class", "lines");

            // Add the paths for the lines
            lines.append("path")
              .attr("class", function(d) {
                  return "line z_" + d.key;
              })
              .attr("d", function(d){
                  // our inner array is d.values from the nesting
                  return line(d.values);
              })
              .style("stroke", function(d) { return color(d.key); })
              .style("display", function(d) {
                  if(d.visible === 1) {
                      return "inline";
                  }
                  else return "none";
              })
              .on("mouseover", function(d) {
                  // Make the line bold
                  d3.select(this).transition().duration(transitionTimeDuration)
                      .style("stroke-width", "4px");

                  d3.selectAll(".circle.z_" + d.key + " circle").transition().duration(transitionTimeDuration)
                      .attr("r", function(d, i) { return 4 })

                  // Fade out the other lines
                  var otherlines = $(".line").not("path.z_" + d.key);
                  d3.selectAll(otherlines).transition().duration(transitionTimeDuration)
                      .style("opacity", 0.3)
                      .style("stroke-width", 1.5)
                      .style("stroke", "gray");

                  var othercircles = $("circle").not(".circle.z_" + d.key + " circle");
                  d3.selectAll(othercircles).transition().duration(transitionTimeDuration)
                      .style("opacity", 0.3)
                      .style("stroke", "gray");

                  // Show tooltips
                  tooltipSummary.transition()
                      .style("opacity", 0.8);
                  tooltipSummary
                      .html(d.key)
                        .style("left", (d3.event.pageX - 350) + "px")
                        .style("top", (d3.event.pageY - 100) + "px");
              })
              .on("mouseout", function(d) {
                  // Make the line normal again
                  d3.select(this).transition().duration(transitionTimeDuration)
                      .style("stroke-width", "1.5px");

                  d3.selectAll(".circle.z_" + d.key + " circle").transition().duration(transitionTimeDuration)
                      //.duration(100)
                      .attr("r", function(d, i) { return 2 })

                  // Make the other lines normal again
                  var otherlines = $('.line').not("path.z_" + d.key);
                  d3.selectAll(otherlines).transition().duration(transitionTimeDuration)
                      .style("opacity", 1)
                      .style("stroke-width", 1.5)
                      .style("stroke", function(d) { return color(d.key); });

                  var othercircles = $("circle").not(".circle.z_" + d.key + " circle");
                  d3.selectAll(othercircles).transition().duration(transitionTimeDuration)
                      .style("opacity", 1)
                      .style("stroke", function(d) { return color(dimKey(d)); });

                  // Hide the tooltip
                  tooltipSummary.transition().duration(transitionTimeDuration)
                      .style("opacity", 0);
              });

            // bind the data for the circles
            var circles = svg.selectAll(".circle")
                .data(nested_data) 
              .enter().append("g")
                .attr("class", function(d) {
                    return "circle z_" + d.key;
                })
                .style("display", function(d) {
                    if(d.visible === 1) {
                        return "inline";
                    }
                    else return "none";
                });

            circles.selectAll("circle")
                .data(function(d) { return d.values })
              .enter().append("circle")
                .attr("stroke", function(d) { return color(dimKey(d)); })
                .attr("fill", "white")
                .attr("cx", function(d, i) { return x(d.sampleDate) })
                .attr("cy", function(d, i) { return y(d.pfcLevel) })
                .attr("r", 2)
                .on("mouseover", function(d) {

                    d3.select(this).transition().duration(transitionTimeDuration)
                        .attr("r", function(d, i) { return 4 })

                    // Show tooltips
                    tooltipDetail.transition().duration(transitionTimeDuration)
                        .style("opacity", 0.8);
                    tooltipDetail
                        .html("<strong>" + longDesc(d) + "</strong><br />" + seriesVal(d) + ": " + d.pfcLevel + "<br />" + hoverDate(new Date(d.sampleDate)))
                        .style("left", (d3.event.pageX - 400) + "px")
                        .style("top", (d3.event.pageY - 100) + "px");
                })
                .on("mouseout", function(d) {
                    d3.select(this).transition().duration(transitionTimeDuration)
                        .attr("r", function(d, i) { return 2 })

                    // Hide the tooltip
                    tooltipDetail.transition().duration(transitionTimeDuration).style("opacity", 0);
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
                .attr("class", function(d) { return "z_" + d.key; })
                .attr("x", width + 30)
                .attr("y", function(d,i) { return height - 495 + (i*25); })
                .attr("stroke", function(d) { return color(d.key);})
                .attr("fill", function(d) {
                    if (d.visible === 1) {
                        return color(d.key);
                    } else {
                        return "white";
                    }
                })
                .attr("style", "cursor: pointer")
                .on("click", function(d) {

                    // Effectively disables the mouseover and mouseout events until this transition is done
                    $('.legend').addClass('clicked');
                    setTimeout(function () { $('.legend').removeClass('clicked') }, 1000);

                    var selectedPath = svg.select("path.z_" + d.key);

                    if (d.visible === 1) {
                        d.visible = 0;
                    } else {
                        d.visible = 1;
                    }

                    rescaleY();

                    svg.select("rect.z_" + d.key).transition().duration(transitionTimeDuration)
                        .attr("fill", function(d) {
                            if (d.visible === 1) {
                                return color(d.key);
                            } else {
                                return "white";
                            }
                        })
                    
                    
                    svg.selectAll(".circle.z_" + d.key).transition().duration(transitionTimeDuration)
                        .style("display", function(a) {
                            if(d.visible === 1) {
                                return "inline";
                            }
                            else return "none";
                        });
                    

                    updateCircles();
                    updateLines();

                });

            legend.append("text")
                .attr("class", "legendLabel")
                .attr("x", function(d) { return width + 65; })
                .attr("y", function(d,i) { return height - 487 + (i*25); })
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
                svg.select(".y").transition().duration(transitionTimeDuration * 2)
                    .ease("sin-in-out")
                    .call(yAxis);
            }

             // Helper function for updating the lines after selecting/deselecting on the legend
            function updateLines() {
                svg.selectAll(".line")
                    .transition().duration(transitionTimeDuration)
                    .style("display", function(d) {
                        if(d.visible === 1) {
                            return "inline";
                        }
                        else return "none";
                    })
                    .attr("d", function(d) {
                        return line(d.values);
                    });

                svg.selectAll(".pfoaEPAHA")
                    .transition().duration(transitionTimeDuration * 4).delay(100)
                    .attr("y1", y(epaPHA_PFOA))
                    .attr("y2", y(epaPHA_PFOA));

                svg.selectAll(".pfosEPAHA")
                    .transition().duration(transitionTimeDuration * 4).delay(100)
                    .attr("y1", y(epaPHA_PFOS))
                    .attr("y2", y(epaPHA_PFOS));

                svg.selectAll(".pfoaEPAHAText")
                    .transition().duration(transitionTimeDuration * 4).delay(100)
                    .attr("y", y(epaPHA_PFOA) - 10);

                svg.selectAll(".pfosEPAHAText")
                    .transition().duration(transitionTimeDuration * 4).delay(100)
                    .attr("y", y(epaPHA_PFOS) - 10);
                  }

            // update the circles on a Y axis update
            function updateCircles() {
                svg.selectAll(".circle circle")
                    .transition().duration(transitionTimeDuration)
                    .attr("cy", function(d, i) { 
                      return y(d.pfcLevel) 
                    });


            }

        });

    }

    // Get/set the dimension key
    chart.dimKey = function(value) {
        if (!arguments.length) return dimKey;
        dimKey = value;
        return chart;
    };

    // Get/set the sort by field
    chart.sortBy = function(value) {
        if (!arguments.length) return sortBy;
        sortBy = value;
        return chart;
    };

    // Get/set the dimensions
    chart.dimensions = function(value) {
        if (!arguments.length) return dimensions;
        dimensions = value;
        return chart;
    };

    // Get/set the description
    chart.longDesc = function(value) {
        if (!arguments.length) return longDesc;
        longDesc = value;
        return chart;
    };

    // Get/set the series
    chart.seriesVal = function(value) {
        if (!arguments.length) return seriesVal;
        seriesVal = value;
        return chart;
    };

    return chart;

}