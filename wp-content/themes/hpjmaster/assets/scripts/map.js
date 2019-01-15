// Google Map
jQuery(document).ready(function($) {

  'use strict';

  var iframe = $('.map-content');
  var elevator;

  var centerMap = new google.maps.LatLng(centerMapLat , centerMapLng);

  var map = new google.maps.Map(iframe[0], {
    zoom: 16,
    scrollwheel: false,
    center: centerMap,
    mapTypeId: 'roadmap',
    styles: [ { "stylers": [ { "saturation": -100 } ] },{ "featureType": "water", "stylers": [ { "lightness": -11 } ] },{ "featureType": "road.highway", "stylers": [ { "lightness": 26 } ] } ]
  });

  var infowindow = new google.maps.InfoWindow({
    maxWidth: 400
  });

  var lastMarkerClicked;
  var uri = iframe.attr('data-uri');
  var icon = iframe.attr('data-icon');
  var active;
  var holder;
  var esc = {
    '<': '&lt;',
    '>': '&gt;',
    '"': '&quot;'
  };

  for (var i = 0; i < adresses.length; i++) {

    (function(argAdresses) {

      var latlng = new google.maps.LatLng(argAdresses.lat, argAdresses.lng);
      var marker = new google.maps.Marker({
        position: latlng,
        map: map,
        icon: icon
      });

      var stateInfobulle = 'closed';

      var infobulle =
              '<div style="width: 250px;"> ' +
              '<strong>' +
              argAdresses.name +
              '</strong><br>' +
              argAdresses.street + '<br>' +
              argAdresses.city + '<br>' +
              argAdresses.country + ''+
              '</div>';

      google.maps.event.addListener(marker, 'click', function() {

        if (lastMarkerClicked != argAdresses.lat || stateInfobulle != 'open') {
          stateInfobulle = 'open';
          infowindow.setContent(infobulle);
          infowindow.open(map, marker);
        }
        else {
          stateInfobulle = 'closed';
          infowindow.close();
          map.setCenter(centerMap);
        }

        lastMarkerClicked = argAdresses.lat;
      });

      google.maps.event.addListener(infowindow, 'closeclick', function() {
        map.setCenter(centerMap);
        stateInfobulle = 'closed';
      });


    })(adresses[i]);
  }


});
