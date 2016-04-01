@extends('layouts.master')

@section('title', 'Map')

@section('content')
    <div class="row">

        <div class="col-md-4">
          <h4 class="center">Pease International Tradeport</h4>
          <h5 class="center">Portsmouth, NH</h5>
          <p>This map shows the locations of the wells on the Pease Tradeport as well as
             the people who were exposed (and tested) to the contaminated water.</p>
          <p>The active wells are shown with a blue circle and the inactive well, Haven, 
             is shown with a red circle.  The size of the circle represents the well's
             yeild in millions of gallons from 2002 to 2008.</p>
          <p>The orage circles represent the individuals that were exposed to the PFCs from the
             Pease wells.  The location of the circle represents where the individual was exposed 
             and the size of the circle represents the relative amount of PFCs in their blood - 
             the larger the circle the higher the levels of PFCs present.</p>
        </div>
        <div class="col-md-8">

          <div id="map"></div>

        </div>

      </div>
    </div><!-- /.container -->

    <script type="text/javascript">
      
      var map;
      var styles = [
        {
          stylers: [
            { saturation: -80 }
          ]
        },{
          featureType: "road",
          elementType: "geometry",
          stylers: [
            { lightness: 0 },
            { visibility: "simplified" }
          ]
        }
      ];
      function initMap() {
        map = new google.maps.Map(document.getElementById('map'), {
          center: {lat: 43.073809, lng: -70.806720},
          zoom: 14
        });
        map.setOptions({ styles: styles });

        var haven = new google.maps.Marker({
          position: {lat: Number(havenWellLat), lng: Number(havenWellLong)},
          map: map,
          label: 'W',
          icon: getMarker(havenWellYeild, 'red'),
          title: 'Haven Well'
        });
        var smith = new google.maps.Marker({
          position: {lat: Number(smithWellLat), lng: Number(smithWellLong)},
          map: map,
          label: 'W',
          icon: getMarker(smithWellYeild, 'blue'),
          title: 'Smith Well'
        });
        var harrison = new google.maps.Marker({
          position: {lat: Number(harrisonWellLat), lng: Number(harrisonWellLong)},
          map: map,
          label: 'W',
          icon: getMarker(harrisonWellYeild, 'blue'),
          title: 'Harrison Well'
        });
        function addrToLatLong(address, pfcLevel, shortName) {
          var geocoder = new google.maps.Geocoder();
          geocoder.geocode( { 'address': address}, function(results, status) {
            if (status == google.maps.GeocoderStatus.OK) {
              // Introduce some variability into the lat & long to avoid bullseye effect
              var jitter = Math.random() / 1000;
              var latitude;
              var longitude;
              if (jitter % 2 === 0) {
                latitude = results[0].geometry.location.lat() + jitter;
                longitude = results[0].geometry.location.lng() - jitter;
              }
              else {
                latitude = results[0].geometry.location.lat() - jitter;
                longitude = results[0].geometry.location.lng() + jitter;  
              }
              //console.log(latitude + ", " + longitude)
              var loc = new google.maps.Marker({
                position: {lat: latitude, lng: longitude},
                map: map,
                icon: getMarker(pfcLevel, 'orange'),
                title: shortName + ": " + pfcLevel
              });
            } 
          }); 
        }
        function getMarker(size, color) {
          var diameter;
          // Is the size for a well yield or a person exposed?
          if (size < 50) {
            diameter = size; 
          }
          else {
            diameter = size / 20;
          }
          var circle = {
            path: google.maps.SymbolPath.CIRCLE,
            fillColor: color,
            fillOpacity: .4,
            scale: diameter,
            strokeColor: 'white',
            strokeWeight: .5
          };
          return circle;
        }
        // Allows map to re-size when not 100% height and width
        $(window).resize(function () {
          var h = $(window).height(),
            offsetTop = 120;
          $('#map').css('height', (h - offsetTop));
        }).resize();
        /*
        map.controls[google.maps.ControlPosition.RIGHT_BOTTOM].push(
          document.getElementById('legend'));
        var legend = document.createElement('div');
        legend.id = 'legend';
        var content = [];
        content.push('<h4>Legend</h4>');
        content.push('<p>Well Shutdown<div class="circle red"></div></p>');
        content.push('<p><div class="circle blue"></div>Well Operational</p>');;
        legend.innerHTML = content.join('');
        legend.index = 1;
        map.controls[google.maps.ControlPosition.RIGHT_TOP].push(legend);
        */
      }
    </script>

    <script async defer
      src="https://maps.googleapis.com/maps/api/js?key=AIzaSyCPQ8HbMqX86au-RHRw5pUQF2sq28v7d2g&callback=initMap">
    </script>
@stop