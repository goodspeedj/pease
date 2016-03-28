// Set the dimensions of the canvas / graph
var margin = {top: 30, right: 200, bottom: 70, left: 50},
    width = 1200 - margin.left - margin.right,
    height = 600 - margin.top - margin.bottom;

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

var transitionTimeDuration = 200,
    transitionTimeDelay = 100;
