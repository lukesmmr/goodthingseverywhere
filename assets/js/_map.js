/* Good Things Journal Map v0.6 @lukassommer / @goodthngs
Description: Loops through Wordpress genereated JSON file with marker data written by post meta. Plaeces markers on a styled Google Map map, article & user position, connects markers with colored polylines
and add infowindows with post previews. To-do:
- add location links on journal entry to open map (panTo)
*/
var markerData      = template_dir + '/assets/json/markers.json',
    markerImg       = template_dir + '/assets/img/maps-marker.svg',
    userMarkerImg   = template_dir + '/assets/img/maps-marker-user.svg',
    markers         = [],
    markerPath      = [],
    markerCounter   = 0,
    infowindow      = null,
    posLocated      = false,
    mapLoaded       = false,
    posDenied       = false,
    mapOpen         = false,
    mapResized      = false,
    mapHeight       = $(window).height() - 120,
    currentPos      = new google.maps.LatLng($('#loc-settings').data('lat'), $('#loc-settings').data('lng')),
    zoomLevel       = $('#loc-settings').data('zoom-level'),
    polylineColor   = $('#loc-settings').data('polyline-color'),
    currentLocMsg   = 'This is my current location',
    userLocMsg      = 'This is where you are (detected by your Browser)',
    posMatchMsg     = 'Nice, our locations match. Maybe we should meet',
    tipArray        = ['Explore the world based on my journal entries!', 'The world is a pretty big place, right?', 'With geolocation enabled you can see your position on the map'],
    moreArray       = ['Lots of cool photos if you continue reading, trust me!', 'Continue reading to see the whole article!', 'Have you been here yourself? Tell me about it!'],
    infowinWidth,
    infoWinMinHeight,
    newMapHeight,
    captiontimer,
    maptimer,
    currentPosMarker,
    matchPosMarker,
    userPosMarker,
    userPos,
    distanceToMe;

var randomTips = function () {
  var tipMsg = randomFrom(tipArray);
  return tipMsg;
};
var randomClickMore = function () {
  var moreMsg = randomFrom(moreArray);
  return moreMsg;
};

if( /Android|webOS|iPhone|iPod|BlackBerry/i.test(navigator.userAgent) ) {
  infowinWidth = 200;
  infoWinMinHeight = "";
} else {
  infowinWidth = 400;
  infoWinMinHeight = "170px";
}

function error(err) {
    if (err.code === 1) { // user denied
      console.log("Geolocation was denied! " + posDenied);
      $('.distance-calc').hide();
    } else {
      // other error
      console.log("Geolocation failed!");
    }
}

function calculateDistance(userPos) {
  if (posDenied === false) {
      distanceToMe =  (google.maps.geometry.spherical.computeDistanceBetween (userPos, currentPos)) / 1000;
      $(".distance-to-me").text( "You're " + distanceToMe.toFixed(2) + " Kms away!");
      return distanceToMe;
  }
}

function getCurrentLocation(callback) {
   if("geolocation" in navigator) {
      navigator.geolocation.getCurrentPosition(function(position) {
         callback(new google.maps.LatLng(position.coords.latitude, position.coords.longitude));
       }, error, {enableHighAccuracy:false, timeout:60000, maximumAge:600000}); // 1 min timeout, 10 min cache
    } else {
       error('Geolocation not supported!');
    }
}

function distanceToUser() {
  if ("geolocation" in navigator) {
      if(posLocated) {return;} // execute only once
      posLocated = true;
      $('.map-icon').show().after( '<span class="distance-to-me">Locating...</span>');
      $('.distance-to-me').fadeTo( "slow" , 1);
      getCurrentLocation(function(userPos){
        calculateDistance(userPos);
      });
  } else {
      error('Geolocation not supported!');
  }
}

function bindInfoWindow(marker, map, infowindow, html) {
  google.maps.event.addListener(marker, 'click', function() {
    infowindow.setContent(html);
    infowindow.open(map, marker);
    map.panBy(-50, -150);
  });
}

function goodThingsMap() {

  // don't load the map if it's already loaded
  if(mapLoaded) {return;}
  mapLoaded = true;

  // set map styles
  var styles = [
    {
      stylers: [
        { hue: "#129adb"},
        { saturation: "20"}/*,
        { invert_lightness: true} think about adding time based inversion */
      ]
    }, {
      featureType: "road.local",
      elementType: "goemetry",
      stylers : [
        {visibility: "off"}
      ]
    }, {
      featureType: "road.highway",
      elementType: "goemetry",
      stylers : [
        {visibility: "off"}
      ]
    }, {
      featureType: "road.highway.controlled_access",
      elementType: "goemetry",
      stylers : [
        {visibility: "off"}
      ]
    }, {
      featureType: "transit",
      elementType: "labels.icon",
      stylers: [
        { visibility: "off" }
      ]
    }, {
      featureType: "all",
      elementType: "all",
      styles: [
        {visibiility: "simplified"}
      ]
    }
  ];

  // options
  var customStyles = new google.maps.StyledMapType(styles, {name: "Good Things Journal"});
  var mapOptions = {
    center: currentPos,
    zoom: zoomLevel,
    minZoom: 2,
    maxZoom: 12,
    mapTypeControlOptions: {
        mapTypeIds: [google.maps.MapTypeId.ROADMAP, 'map_style']
    },
    disableDefaultUI: false
  };

  // set map
  var map = new google.maps.Map(document.getElementById("map-canvas"), mapOptions);

  // set infowindow
  infowindow = new google.maps.InfoWindow({
      content: "loading...",
      maxWidth: infowinWidth
  });

  // get marker data
  $.getJSON(markerData, function(data) {

    // loop through JSON project data
    $.each( data, function(i) {

      // check if marker should be placed
      if (data[i].add_marker) {

        //  label numbering
        markerCounter++;

        // make path array for polylines
        markerPath.push(new google.maps.LatLng(data[i].info.latitude, data[i].info.longitude));

        // marker and info window markup
        var journalLocLabel = '<label class="loclabel"><span class="locnr">' + markerCounter + '</span></label>';
        var infoWindowPreview = '<span class="locpost-location">' + data[i].info.post_location + ' - ' + data[i].info.post_date +
                                '</span><a class="locpost-title-link" href="' + data[i].info.post_url + '"><h4 class="locpost-title">' +
                                data[i].info.post_title + '</h4></a><div class="locpost-preview" style="min-height:' + infoWinMinHeight +
                                ';"><img style="width: 250px; height: auto !important;" src="' + data[i].info.post_thumb + '" /><p>' +
                                data[i].info.post_excerpt + '</p><a class="locpost-link" href="' + data[i].info.post_url + '">Read article</a></div>';
        setLabelAnchor = new google.maps.Point(13, 42);

        // create markers on the map
        markers[markerCounter] = new MarkerWithLabel({
          icon : new google.maps.MarkerImage(markerImg, null, null, null, null),
          position: new google.maps.LatLng( data[i].info.latitude, data[i].info.longitude ),
          map: map,
          draggable: false,
          labelContent: journalLocLabel,
          labelAnchor: setLabelAnchor,
          labelClass: "marker-label",// the CSS class for the label
          labelZIndex: markerCounter+1,
          html: infoWindowPreview,
          url: data[i].info.post_url,
          zIndex: markerCounter
        });

        // bind info windows
        bindInfoWindow(markers[markerCounter], map, infowindow, markers[markerCounter].html);

      }
    });

    // set current position marker
    currentPosMarker = new MarkerWithLabel({
      position: currentPos,
      icon: new google.maps.MarkerImage(markerImg, null, null, null, null),
      draggable: false,
      map: map,
      labelContent: "Current",
      labelAnchor: new google.maps.Point(-20, 22),
      labelClass: "marker-label", // label css class
      labelInBackground: false
    });

    if ("geolocation" in navigator) {

      getCurrentLocation(function(userPos)
      {
        $('#user-loc-btn').prop('disabled', false);
        $('#user-loc-btn').on('click', function() {
          posMsg(userPos, userLocMsg);
          map.setZoom(12);
          $('.selectpicker').selectpicker('deselectAll');
        });
        if (distanceToMe < 20) {

          currentPosMarker.setMap(null);
          //console.log("Cool, you're really close to where i am!");
          matchPosMarker = new MarkerWithLabel({
            position: currentPos,
            icon: new google.maps.MarkerImage(userMarkerImg, null, null, null, null),
            draggable: false,
            map: map,
            labelContent: "",
            labelAnchor: new google.maps.Point(-20, 22),
            labelClass: "marker-label-user",// label css class
            labelInBackground: false
         });

        } else {

          currentPosMarker.setMap(null);
          currentPosMarker = new MarkerWithLabel({
            position: currentPos,
            icon: new google.maps.MarkerImage(markerImg, null, null, null, null),
            draggable: false,
            map: map,
            labelContent: "My position",
            labelAnchor: new google.maps.Point(-20, 32),
            labelClass: "marker-label",// label css class
            labelInBackground: false
          });

          userPosMarker = new MarkerWithLabel({
            position: userPos,
            icon: new google.maps.MarkerImage(userMarkerImg, null, null, null, null),
            draggable: false,
            map: map,
            labelContent: "You",
            labelAnchor: new google.maps.Point(-20, 22),
            labelClass: "marker-label-user",// label css class
            labelInBackground: false
          });
        }
      });
    } else {
        error('Geolocation not supported!');
    }

    // add map styles
    map.mapTypes.set('map_style', customStyles);
    map.setMapTypeId('map_style');

    // polylines
    var travelItinerary = new google.maps.Polyline({
      path: $.merge(markerPath, [currentPos]),
      geodesic: true,
      strokeColor: polylineColor,
      strokeOpacity: 1.0,
      strokeWeight: 2
    });

    travelItinerary.setMap(map);

  });

  // dropdown
  $( "select.marker-coords" ).change(function() {
      clearTimeout(captiontimer);
      // var
      var marker_lat  = $(this).find(':selected').data('post-loc-lat'),
          marker_lng  = $(this).find(':selected').data('post-loc-lng'),
          marker_id   = $(this).find(':selected').data('marker'),
          marker_loc  = $(this).find(':selected').data('post-loc');
      // set map
      map.setZoom(8);
      map.panTo( new google.maps.LatLng(marker_lat, marker_lng) );
      // set infowindow
      if ( marker_loc !== "current" ) {
        infowindow.close();
        infowindow.setContent( markers[marker_id].html );
        infowindow.open(map, markers[marker_id] );
        map.panBy(-50, -150);
      }
      // map notifications
      $('span.map-msg').text(randomClickMore());
      $('#journal-map-notification').delay(200).slideDown(300);
      captiontimer = setTimeout(function() {
            $('#journal-map-notification').delay(200).slideUp(300);
      }, 6000);

  });

  // custom btn group click events
  $('#current-loc-btn').on('click', function() {
    yourPos = new google.maps.LatLng($('#loc-settings').data('lat'), $('#loc-settings').data('lng'));
    posMsg(yourPos, currentLocMsg);
    map.setZoom(12);
    $('.selectpicker').selectpicker('deselectAll');
  });

  // msg function
  function posMsg(geodata, posmsg) {
    clearTimeout(captiontimer);
    if ("geolocation" in navigator && posDenied === false) {
      if (distanceToMe < 20) {
        $('span.map-msg').text(posMatchMsg);
      } else {
        $('span.map-msg').text(posmsg);
      }
    } else {
      $('#user-loc-btn').prop('disabled', true);
      $('span.map-msg').text(posmsg);
    }
    $('#journal-map-notification').delay(200).slideDown(300);
    captiontimer = setTimeout(function() {
        $('#journal-map-notification').delay(200).slideUp(300);
    }, 6000);

    map.setZoom(6);
    map.panTo(geodata);

    infowindow.close();
  }

  // scale map according to viewport height
  $(window).resize(function() {
    $('#journal-map, #map-canvas').css('height', $(window).height() - 120);
    if (!mapOpen) {
      $('#journal-map').css({marginTop: -($('#journal-map').height()) });
    }
    google.maps.event.trigger(map, 'resize');
  });

}

function toggleMap() {
  if(mapOpen) {

    $('#journal-map').delay(200).animate({marginTop: -($('#journal-map').height()) }, 300);
    $('span.map-msg').text(randomTips());
    $('#arrow-pos').addClass('map-btn').removeClass('close-btn');
    $('#map-arrow').removeClass('arrow-up').addClass('arrow-down');
    $('#journal-map-notification').slideUp(300);
    $('#journal-map-toggle').text('Explore Map');

    // remove anchor
    window.location.hash = '';
    mapOpen = false;
  } else {
    clearTimeout(captiontimer, maptimer);

    // load map
    maptimer = setTimeout(function() {
      goodThingsMap();
    }, 1500);

    $('#journal-map').animate({ marginTop: "0px" }, 300);
    $('#arrow-pos').removeClass('map-btn').addClass('close-btn');
    $('#map-arrow').removeClass('arrow-down').addClass('arrow-up');
    $('#journal-map-notification').delay(200).slideDown(300);
    $('#journal-map-toggle').text('Close');

    // map notification
    captiontimer = setTimeout(function() {
          $('#journal-map-notification').slideUp(300);
    }, 6000);

    // set anchor
    window.location.hash = 'map';
    mapOpen = true;
  }
}