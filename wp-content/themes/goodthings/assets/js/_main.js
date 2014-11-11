/* @goodthngs / lukassommer - goodthingseverywhere.com - 2013/2014
I try to keep it tidy and dry here. */
var posLocated = false,
    mapLoaded = false,
    userPos,
    distanceToMe,
    posDenied = false,
    mapOpen = false,
    mapResized = false,
    mapHeight = $(window).height() - 120,
    newMapHeight,
    infowindow = null,
    markers = [],
    infowinWidth,
    infoWinMinHeight,
    dropdownMsg,
    captiontimer,
    maptimer,
    currentPosMarker,
    matchPosMarker,
    userPosMarker,
    currentPos = new google.maps.LatLng($('#loc-settings').data('lat'), $('#loc-settings').data('lng')),
    zoomLevel = $('#loc-settings').data('zoom-level');
    polylineColor = $('#loc-settings').data('polyline-color');
    markerPath = siteurl + "wp-content/themes/goodthings/assets/img/maps-marker.svg",
    userMarkerPath = siteurl + "wp-content/themes/goodthings/assets/img/maps-marker-user.svg",
    currentLocMsg = "This is my current location",
    userLocMsg = "This is where you are (detected by your Browser)",
    posMatchMsg = "Nice, our locations match. Maybe we should meet";
    // add more tips will ya!
    var randomTips = function () {
      var tipArray = randomFrom(['Explore the world based on my journal entries. Click on the markers to see places i visited'/*, 'You can navigate with your cursor keys and zoom with +/-', 'With geolocation enabled you can see your position on the map'*/]);
      return tipArray;
    };
    if( /Android|webOS|iPhone|iPod|BlackBerry/i.test(navigator.userAgent) ) {
      dropdownMsg = "Tap on the Marker to see the article preview";
      infowinWidth = 220;
      infoWinMinHeight = "";
    } else if( /iPad/i.test(navigator.userAgent) ) {
      dropdownMsg = "Tap on the Marker to see the article preview";
      infowinWidth = 400;
      infoWinMinHeight = "170px";
    } else {
      dropdownMsg = "Click on the Marker to see the article preview";
      infowinWidth = 400;
      infoWinMinHeight = "170px";
    }

// init
var GoodThingsSite = {
  // specific pages
  common: {
    init: function() {

      $('#user-loc-btn').prop('disabled', true);
      $('.selectpicker').selectpicker();
      $('.selectpicker').selectpicker('deselectAll');

      // locate
      distanceToUser();

      // bootstrap select
      $('span.map-msg').text(randomTips());
      // map toggle
      $('#journal-map, #map-canvas').css('height', mapHeight);
      $('#journal-map').css({'z-index': 123456, 'margin-top' : -(mapHeight)});
      $("#journal-map-toggle").on('click', function() {
        toggleMap();
      });
      if (window.location.hash === '#map') {
        toggleMap();
      }

    }
  },
  single: {
    init: function() {
        $('button.recommend').on('click', function() {
          $('.share-options').fadeToggle(300);
        });
    }
  }, 
  home: {
    init: function() {
      $('#good-svg').removeClass('hidden');
      // get latest instagram image
       $.getScript(siteurl + "wp-content/themes/goodthings/assets/js/keys.js", function() {
         $.ajax({
            type: "GET",
            dataType: "jsonp",
            cache: false,
            url: "https://api.instagram.com/v1/users/2167719/media/recent/?access_token=" + insta_token,
            success: function(data) {
              $('.insta-pic').html('');
              for (i = 0; i < 2; i++) {
                $('.insta-pic').append('
                  <div class="insta-container">
                    <div class="insta-meta">
                      <i class="glyphicon glyphicon-map-marker"></i> <div class="insta-date">' + timeAgo(data.data[i].caption.created_time) + '</div> <div class="insta-loc"><span>in ' + data.data[i].location.name + '</span></div>
                    </div>
                    <a href="' + data.data[i].link +'" title="Link to latest Instagram Photo" target="_blank" class="insta-link">
                      <div id="instagram-img"><img src="' + data.data[i].images.standard_resolution.url + '" alt="" /></div>
                    </a>
                    <figcaption class="caption insta-caption wp-caption-text">' + data.data[i].caption.text + '</figcaption>
                  </div>');
              }
            }
         });
       });
    }
  }
};

var UTIL = {
  fire: function(func, funcname, args) {
    var namespace = GoodThingsSite;
    funcname = (funcname === undefined) ? 'init' : funcname;
    if (func !== '' && namespace[func] && typeof namespace[func][funcname] === 'function') {
      namespace[func][funcname](args);
    }
  },
  loadEvents: function() {
    UTIL.fire('common');
    $.each(document.body.className.replace(/-/g, '_').split(/\s+/),function(i,classnm) {
      UTIL.fire(classnm);
    });
    UTIL.fire('common', 'finalize');
  }
};

$(document).ready(UTIL.loadEvents);

/* Good Things Journal Map v0.3 - 14.10.2014
Description: Loops through data attributes with geo data located in post meta and set up map, article & user position, connect with polylines
and add infowindows with post previews. Necessary enhancements: 
- open infowindow on dropdown
- add location links on journal entry to open map (panTo)
- asynchronous infowindow content loading
*/

function toggleMap() {
  if(mapOpen) {

    $('#journal-map').delay(200).animate({marginTop: -($('#journal-map').height()) }, 300);
    $('span.map-msg').text(randomTips());
    $('#arrow-pos').addClass('map-btn').removeClass('close-btn');
    $('#map-arrow').removeClass('arrow-up').addClass('arrow-down');
    $('#journal-map-caption').slideUp(300);
    $('#journal-map-toggle').text('Map');
    
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

function error(err) {
    if (err.code === 1) { // user denied
      console.log("Geolocation was denied! " + posDenied);
      $('.distance-to-me').hide();
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
      $('.map-icon').after( '<span class="distance-to-me">Locating...</span>');
      $('.distance-to-me').fadeTo( "slow" , 1);
      getCurrentLocation(function(userPos){
        calculateDistance(userPos);
      });
  } else {
      error('Geolocation not supported!');
  }
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
    path: journalCoords,
    geodesic: true,
    strokeColor: polylineColor,
    strokeOpacity: 1.0,
    strokeWeight: 2
  });

  // set map
  var map = new google.maps.Map(document.getElementById("map-canvas"),
      mapOptions);

  // loop for markers and infowindows
  for (var i = 1; i < journalLocs.length; i++) {
    // make array from key value pairs
    locArray = $.makeArray(journalLocs[i]);
    var journalLocLabel = '<label class="loclabel"><span class="locnr">' + (i) + '</span></label>';
    var infoWindowPreview = '<span class="locpost-location">' + locArray[0] + ' - ' + locArray[7] +
                            '</span><a class="locpost-link" href="' + locArray[3] + '"><h4 class="locpost-title">' +
                            locArray[6] + '</h4></a><div class="locpost-preview" style="min-height:' + infoWinMinHeight +
                            ';"><img style="width: 250px; height: auto !important;" src="' + locArray[5] + '" /><p>' +
                            locArray[4] + '</p><a class="locpost-link" href="' + locArray[3] + '">Continue reading</a></div>';
    if ( i > 9 ) {
      var setLabelAnchor = new google.maps.Point(7, 40);
    } else {
      var setLabelAnchor = new google.maps.Point(4, 40);
    }
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

    // set infowindow
    infowindow = new google.maps.InfoWindow({
        content: "loading...",
        maxWidth: infowinWidth
    });

    // add click event 
    // google.maps.event.addListener(markers[i], 'click', openInfoWindow(infowindow, markers[i].html));

    // add click event 
    google.maps.event.addListener(markers[i], 'click', function() {
      infowindow.setContent(this.html);
      infowindow.open(map, this);
      map.panBy(-50, 0);
    });
    
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
  map.panBy(-50, -50);

  // add polyline
  travelItinerary.setMap(map);

  // dropdown
  $( "select.marker-coords" ).change(function() {
      clearTimeout(captiontimer);
      var selected_lat = $(this).find(':selected').data('post-loc-lat'),
          selected_lng = $(this).find(':selected').data('post-loc-lng');
      map.setZoom(6);
      map.panTo(new google.maps.LatLng(selected_lat, selected_lng));
      $('span.map-msg').text(dropdownMsg);
      $('#journal-map-caption').delay(200).slideDown(300);
      captiontimer = setTimeout(function() {
            $('#journal-map-caption').delay(200).slideUp(300);
      }, 5000);
      infowindow.close();
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
    }, 3000);

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

