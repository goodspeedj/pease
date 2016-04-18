@extends('layouts.master')

@section('title', 'Map')

@section('content')
    <div class="row">

        <div class="col-md-4">
          <h4 class="center">Pease International Tradeport</h4>
          <h5 class="center">Portsmouth, NH</h5>
          <p>This map shows the locations of the production wells, sentry wells and distribution 
             points on the Pease Tradeport.</p>
          <p>The production wells are shown with a blue circle and the inactive well, Haven, 
             is shown with a red circle.  The sentry wells are shown in green and the distribution
             points that have been measured are shown in orange.  The size of the circle represents 
             the level of PFC contamination in that well.</p>
        </div>
        <div class="col-md-8">

          <div id="map"></div>

        </div>

      </div>
    </div><!-- /.container -->

    <script type="text/javascript">

      var wellData = <?php echo $wellData ?>;

      var map;
      var styles = [
        {
          stylers: [
            { saturation: -50 }
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
          center: {lat: 43.070704, lng: -70.806700},
          zoom: 15
        });
        map.setOptions({ styles: styles });

        wellData.forEach(function(entry) {
            var color;
            var size;
            var pfcAverage = Math.round(entry.pfcAvg * 1000) / 1000;

            if (pfcAverage > 1) { 
              color = '#67000d';
            } 
            else if (pfcAverage > 0.2) { 
              color = '#a50f15';
            }
            else if (pfcAverage > 0.1) { 
              color = '#cb181d';
            }
            else if (pfcAverage > 0.05) { 
              color = '#ef3b2c';  
            }
            else if (pfcAverage > 0.009) { 
              color = '#fb6a4a';  
            }
            else if (pfcAverage > 0.005) { 
              color = '#fc9272';  
            }
            else if (pfcAverage > 0.001) { 
              color = '#fcbba1';  
            }
            else if (pfcAverage > 0.0005) { 
              color = '#fee0d2';  
            }
            else {
              color = '#B5EAAA';
            }

            if(entry.wellType === 'Production Well') {
                //color = '#1F45FC';
                size = 30;
            } else if(entry.wellType === 'Distribution Point') {
                //color = '#FBB917';
                size = 15;
            } else {
                //color = '#4CC417';
                size = 7;
            }
            
            var marker = new google.maps.Marker({
              position: {lat: Number(entry.wellLat), lng: Number(entry.wellLong)},
              map: map,
              icon: getMarker(size, color),
              title: entry.wellDesc + "\n" + "PFC Average: " + pfcAverage
            });

        });


        /*
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
        */
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
            fillOpacity: .9,
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