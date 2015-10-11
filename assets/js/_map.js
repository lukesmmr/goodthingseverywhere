/* Good Things Journal Map v0.5 - Last edit 24.11.2014
Description: Loops through data attributes with geo data located in post meta and set up map, article & user position, connect with polylines
and add infowindows with post previews. Necessary enhancements:
- add location links on journal entry to open map (panTo)
- invert select dropdown order
- read / write marker data from json
*/

var infowinWidth,
    infoWinMinHeight,
    newMapHeight,
    captiontimer,
    maptimer,
    map_id,
    currentPosMarker,
    matchPosMarker,
    userPosMarker,
    userPos,
    distanceToMe,
    marker_array,
    marker,
    markers         = [],
    posLocated      = false,
    mapLoaded       = false,
    posDenied       = false,
    mapOpen         = false,
    mapResized      = false,
    mapHeight       = $(window).height() - 120,
    infowindow      = null,
    currentPos      = new google.maps.LatLng($('#loc-settings').data('lat'), $('#loc-settings').data('lng')),
    zoomLevel       = $('#loc-settings').data('zoom-level'),
    polylineColor   = $('#loc-settings').data('polyline-color'),
    markerPath      = siteurl + 'wp-content/themes/goodthings/assets/img/maps-marker.svg',
    userMarkerPath  = siteurl + 'wp-content/themes/goodthings/assets/img/maps-marker-user.svg',
    currentLocMsg   = 'This is my current location',
    userLocMsg      = 'This is where you are (detected by your Browser)',
    posMatchMsg     = 'Nice, our locations match. Maybe we should meet';

var randomTips = function () {
  var tipArray = randomFrom(['Explore the world based on my journal entries!', 'The world is a pretty big place, right?', 'With geolocation enabled you can see your position on the map']);
  return tipArray;
};
var randomClickMore = function () {
  var moreArray = randomFrom(['Lots of cool photos if you continue reading, trust me!', 'Continue reading to see the whole article!', 'Have you been here yourself? Tell me about it!']);
  return moreArray;
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
      $(".distance-to-me").text( "We're " + distanceToMe.toFixed(2) + " Kms apart");
      return distanceToMe;
  }
}

function getCurrentLocation(callback) {
   if("geolocation" in navigator) {
      navigator.geolocation.getCurrentPosition(function(position) {
         callback(new google.maps.LatLng(position.coords.latitude, position.coords.longitude));
       }, error);
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

  if(mapLoaded) {console.log("map already loaded"); return;}
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

  // build array from data attributes
  var journalLocs = [];
  $('select.marker-coords > option').each(function(){
    var $this = $(this);
    journalLocs.push([ $this.data('post-loc'), $this.data('post-loc-lat'), $this.data('post-loc-lng'), $this.data('post-url'), $this.data('post-excerpt'), $this.data('post-thumb'), $this.data('post-title'), $this.data('post-date') ]);
  });

  // set arrays
  var journalCoords = [],
      coordsArray = [],
      locArray = [];

  // get coords only for polylines
  for(var y = 1; y < journalLocs.length; y++) {
      coordsArray = $.makeArray( journalLocs[y] );
      journalCoords.push(new google.maps.LatLng(coordsArray[1], coordsArray[2]));
  }

  // polylines
  var travelItinerary = new google.maps.Polyline({
    path: $.merge(journalCoords, [currentPos]),
    geodesic: true,
    strokeColor: polylineColor,
    strokeOpacity: 1.0,
    strokeWeight: 2
  });

  // set map
  var map = new google.maps.Map(document.getElementById("map-canvas"),
      mapOptions);

  // set infowindow
  infowindow = new google.maps.InfoWindow({
      content: "loading...",
      maxWidth: infowinWidth
  });

  // loop for markers and infowindows
  for (var i = 1; i < journalLocs.length; i++) {
    // make array from key value pairs
    locArray = $.makeArray(journalLocs[i]);
    var journalLocLabel = '<label class="loclabel"><span class="locnr">' + i + '</span></label>';
    var infoWindowPreview = '<span class="locpost-location">' + locArray[0] + ' - ' + locArray[7] +
                            '</span><a class="locpost-link" href="' + locArray[3] + '"><h4 class="locpost-title">' +
                            locArray[6] + '</h4></a><div class="locpost-preview" style="min-height:' + infoWinMinHeight +
                            ';"><img style="width: 250px; height: auto !important;" src="' + locArray[5] + '" /><p>' +
                            locArray[4] + '</p><a class="locpost-link" href="' + locArray[3] + '">Continue reading</a></div>';
    setLabelAnchor = new google.maps.Point(13, 42);

    // set markers
    markers[i] = new MarkerWithLabel({
      icon : new google.maps.MarkerImage(markerPath, null, null, null, null),
      position: new google.maps.LatLng( locArray[1], locArray[2] ),
      map: map,
      draggable: false,
      labelContent: journalLocLabel,
      labelAnchor: setLabelAnchor,
      labelClass: "marker-label",// the CSS class for the label
      labelZIndex: i+1,
      html: infoWindowPreview,
      url: locArray[3],
      zIndex: i
    });

    bindInfoWindow(markers[i], map, infowindow, markers[i].html);
  }

  // get current loc
  currentPosMarker = new MarkerWithLabel({
    position: currentPos,
    icon: new google.maps.MarkerImage(markerPath,
      null, null, null, null),
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
        $('.selectpicker').selectpicker('deselectAll');
      });
      if (distanceToMe < 20) {

        currentPosMarker.setMap(null);
        //console.log("Cool, you're really close to where i am!");
        matchPosMarker = new MarkerWithLabel({
          position: currentPos,
          icon: new google.maps.MarkerImage(userMarkerPath,
            null, null, null, null),
          draggable: false,
          map: map,
          labelContent: "You're only " + distanceToMe.toFixed(2) + "km away!",
          labelAnchor: new google.maps.Point(-20, 22),
          labelClass: "marker-label-user",// label css class
          labelInBackground: false
        });

      } else {

        currentPosMarker.setMap(null);
        currentPosMarker = new MarkerWithLabel({
          position: currentPos,
          icon: new google.maps.MarkerImage(markerPath,
            null, null, null, null),
          draggable: false,
          map: map,
          labelContent: "My position",
          labelAnchor: new google.maps.Point(-20, 32),
          labelClass: "marker-label",// label css class
          labelInBackground: false
        });

        userPosMarker = new MarkerWithLabel({
          position: userPos,
          icon: new google.maps.MarkerImage(userMarkerPath,
            null, null, null, null),
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

  // add polyline
  travelItinerary.setMap(map);

  // dropdown
  $( "select.marker-coords" ).change(function() {
      clearTimeout(captiontimer);
      // var
      var marker_lat  = $(this).find(':selected').data('post-loc-lat'),
          marker_lng  = $(this).find(':selected').data('post-loc-lng'),
          marker_id     = $(this).find(':selected').data('marker'),
          marker_loc    = $(this).find(':selected').data('post-loc');
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
      $('#journal-map-caption').delay(200).slideDown(300);
      captiontimer = setTimeout(function() {
            $('#journal-map-caption').delay(200).slideUp(300);
      }, 6000);
  });

  // custom btn group click events
  $('#current-loc-btn').on('click', function() {
    yourPos = new google.maps.LatLng($('#loc-settings').data('lat'), $('#loc-settings').data('lng'));
    posMsg(yourPos, currentLocMsg);
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
    $('#journal-map-caption').delay(200).slideDown(300);
    captiontimer = setTimeout(function() {
        $('#journal-map-caption').delay(200).slideUp(300);
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
    $('#journal-map-caption').slideUp(300);
    $('#journal-map-toggle').text('Explore');

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
    $('#journal-map-caption').delay(200).slideDown(300);
    $('#journal-map-toggle').text('Close');

    // map notification
    captiontimer = setTimeout(function() {
          $('#journal-map-caption').slideUp(300);
    }, 6000);

    // set anchor
    window.location.hash = 'map';
    mapOpen = true;
  }
}