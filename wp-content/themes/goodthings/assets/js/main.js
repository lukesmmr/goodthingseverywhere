/* @goodthngs / lukassommer - goodthingseverywhere.com - 2013/2014
I try to keep it tidy and dry here but don't expect perfection ;-) */
var posLocated = false,
    userPos,
    posDenied = false,
    mapOpen = false,
    mapResized = false,
    mapHeight = $(window).height() - 40,
    newMapHeight,
    infowindow = null,
    markers = new Array(),
    infowinWidth,
    infoWinMinHeight,
    dropdownMsg,
    captiontimer,
    currentPos = new google.maps.LatLng($('#getloc').data('lat'), $('#getloc').data('lng')),
    markerPath = siteurl + "wp-content/themes/goodthings/assets/img/maps-marker.svg",
    userMarkerPath = siteurl + "wp-content/themes/goodthings/assets/img/maps-marker-user.svg", 
    currentLocMsg = "This is my current location", 
    userLocMsg = "This is where you are (detected by your Browser)",
    posMatchMsg = "Nice, our locations match. Maybe we should meet"; 
    // add more tips will ya!
    var randomTips = function () {
      var tipArray = randomFrom(['Explore the world based on my journal entries. Click on the markers to see places i visited'/*, 'You can navigate with your cursor keys and zoom with +/-', 'With geolocation enabled you can see your position on the map'*/]);
      return tipArray; 
    }
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
  // all
  // common: {
  //   init: function() {
      
  //   }
  // },
  // specific pages
  home: {
    init: function() {
      $(".giant-title").fitText();  
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
// other ready events
$(document).ready(function() {
  initMap();
  // bootstrap select
  $('.selectpicker').selectpicker();
  $('.selectpicker').selectpicker('deselectAll');
  $('span.map-msg').text(randomTips());
  // map toggle
  $('#journal-map, #map-canvas').css('height', mapHeight);
  $('#journal-map').css({'z-index': 123456, 'margin-top' : -(mapHeight)});
  $("#journal-map-toggle").on('click', function() {
    toggleMap();
  });
  if (window.location.hash == '#map') {
    toggleMap();
  }
});

/* Good Things Journal Map v0.1 - 01.2014
Description: Loops through data attributes with geo data located in post meta and set up map, article & user position, connect with polylines
and add infowindows with post previews. Necessary enhancements: 
- enable map during geolocation request (rebuild function, externalize geolocation request)
- open infowindow on dropdown
- add location links to each journal entry
- asynchronous infowindow content loading
*/

// check distances
function initMap() {
  // locate pos
  if ("geolocation" in navigator) {
    if(posLocated) return; // execute only once
    posLocated = true;
    $('.map-icon').after( '<span class="distance-to-me">Locating...</span>');
    $('.distance-to-me').fadeTo( "slow" , 1);
    navigator.geolocation.getCurrentPosition(goodThingsMap, error);
  } else {
    // set map
    error('Geolocation not supported!');
    goodThingsMap();    
  }
}

function error(err) {
    if (err.code == 1) { // user denied
      posDenied = true;
      goodThingsMap();
      $('.distance-to-me').hide();
      console.log("Geolocation was denied!");
    } else {
      // other error
      console.log("Geolocation failed!");
    }
}

function goodThingsMap(position) {
  // set user pos and calculate distance to current pos
  if ("geolocation" in navigator && posDenied == false) { 
      userPos =  new google.maps.LatLng(position.coords.latitude, position.coords.longitude);
      var distanceToMe =  (google.maps.geometry.spherical.computeDistanceBetween (userPos, currentPos)) / 1000;
      $(".distance-to-me").text( "You're " + distanceToMe.toFixed(2) + " Kms away");
  }
  // set map styles
  var styles = [
    {
      stylers: [
        { hue: "#129adb"},
        { saturation: "-10"}/*,
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
    zoom: 5,
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
    strokeColor: '#129adb',
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
    // set markers
    markers[i] = new MarkerWithLabel({
      icon : new google.maps.MarkerImage(markerPath, null, null, null, null),
      position: new google.maps.LatLng( locArray[1], locArray[2] ),
      map: map,
      draggable: false,
      labelContent: journalLocLabel,
      labelAnchor: new google.maps.Point(4, 40),
      labelClass: "marker-label", // the CSS class for the label
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
    google.maps.event.addListener(markers[i], 'click', function() {      
      infowindow.setContent(this.html);
      infowindow.open(map, this);
      map.panBy(-50, 0);
    });

  }

  // if location available or detected
  if ("geolocation" in navigator && posDenied == false) {
    
    // display display warning if less then 20km show only current pos marker
    if (distanceToMe < 20) {
      
      //console.log("Cool, you're really close to where i am!");
      var currentPosMarker = new MarkerWithLabel({
        position: currentPos,
        icon: new google.maps.MarkerImage(userMarkerPath,
          null, null, null, null),
        draggable: false,
        map: map,
        labelContent: "You're only " + distanceToMe.toFixed(2) + "km away!",
        labelAnchor: new google.maps.Point(-20, 22),
        labelClass: "marker-label-user", // label css class
        labelInBackground: false
      });

    // if further then 20km show my pos and user pos
    } else {

        //console.log("Yay, you are aren't close to me");
        var currentPosMarker = new MarkerWithLabel({
          position: currentPos,
          icon: new google.maps.MarkerImage(markerPath,
            null, null, null, null),
          draggable: false,
          map: map,
          labelContent: "My position",
          labelAnchor: new google.maps.Point(-20, 32),
          labelClass: "marker-label", // label css class
          labelInBackground: false
        });

        var userPosMaker = new MarkerWithLabel({
          position: userPos,
          icon: new google.maps.MarkerImage(userMarkerPath,
            null, null, null, null),
          draggable: false,
          map: map,
          labelContent: "You",
          labelAnchor: new google.maps.Point(-20, 22),
          labelClass: "marker-label-user", // label css class
          labelInBackground: false
        });

      }
  // if gelocation not available or denied show current pos marker 
  } else {

    var currentPosMarker = new MarkerWithLabel({
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
      map.setZoom(10);
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
    posMsg(currentPos, currentLocMsg);
    $('.selectpicker').selectpicker('deselectAll');
  });

  $('#user-loc-btn').on('click', function() {
    posMsg(userPos, userLocMsg);
    $('.selectpicker').selectpicker('deselectAll');
  });

  // disable user btn 
  if (!"geolocation" in navigator || posDenied == true) {
    $('#user-loc-btn').prop('disabled', true);
  }

  // msg function
  function posMsg(geodata, posMsg) {
    clearTimeout(captiontimer);
    if ("geolocation" in navigator && posDenied == false) {
      if (distanceToMe < 20) {
        $('span.map-msg').text(posMatchMsg);
      } else {
        $('span.map-msg').text(posMsg);
      }
    } else {
      $('#user-loc-btn').prop('disabled', true);
      $('span.map-msg').text(posMsg);
    }
    $('#journal-map-caption').delay(200).slideDown(300);
    captiontimer = setTimeout(function() {
          $('#journal-map-caption').delay(200).slideUp(300);  
    }, 3000);
    map.setZoom(9);
    map.panTo(geodata);
    infowindow.close();
  }

  // scale map according to viewport height
  $(window).resize(function() {
    $('#journal-map, #map-canvas').css('height', $(window).height() - 40);
    if (!mapOpen) {
      $('#journal-map').css({marginTop: -($('#journal-map').height()) });
    } 
    google.maps.event.trigger(map, 'resize');
  });

}

// open / close map
function toggleMap() {
  console.log(mapOpen);
  if(mapOpen) {
    $('#journal-map').animate({marginTop: -($('#journal-map').height()) }, 300);
    $('span.map-msg').text(randomTips());
    $('#arrow-pos').removeClass('close-btn');
    $('#map-arrow').removeClass('arrow-up').addClass('arrow-down');
    $('#journal-map-caption').slideUp(300);
    $('#journal-map-toggle').text('Map');
    // $("#journal-map").resizable('disable');
    window.location.hash = '';
    
    mapOpen = false
  } else {
    clearTimeout(captiontimer);
    $('#journal-map').animate({ marginTop: "0px" }, 300);
    $('#arrow-pos').addClass('close-btn');
    $('#map-arrow').removeClass('arrow-down').addClass('arrow-up');
    $('#journal-map-caption').delay(200).slideDown(300);
    $('#journal-map-toggle').text('Close');
    captiontimer = setTimeout(function() {
          $('#journal-map-caption').slideUp(300);  
    }, 6000);
    // set anchor
    window.location.hash = 'map';
    mapOpen = true;
  }
}