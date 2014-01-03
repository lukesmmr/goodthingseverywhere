// Modified http://paulirish.com/2009/markup-based-unobtrusive-comprehensive-dom-ready-execution/
// Only fires on body class (working off strictly WordPress body_class)
var posLocated = false,
    userPos,
    posDenied = false,
    mapOpen = false,
    mapResized = false,
    mapHeight,
    infowindow = null,
    infowinWidth,
    captiontimer,
    currentPos = new google.maps.LatLng($('#getloc').data('lat'), $('#getloc').data('lng')),
    websitePath = "http://localhost/goodthingseverywhere/"
    markerPath = websitePath + "wp-content/themes/goodthings/assets/img/maps-marker.svg",
    userMarkerPath = websitePath + "wp-content/themes/goodthings/assets/img/maps-marker-user.svg", 
    currentLocMsg = "This is my current location", 
    userLocMsg = "This is where you are (detected by your Browser)",
    posMatchMsg = "Nice, our locations match. Maybe we should meet";
    var randomTips = function () {
      var tipArray = randomFrom(['Explore the world based on my journal entries. Click on the markers to see places i visited', 'You can navigate with your cursor keys and zoom with +/-', 'With geolocation enabled you can see your position on the map', 'Drag the border bottom of the map to resize it on your screen']);
      return tipArray; 
    }
    if( /Android|webOS|iPhone|iPod|BlackBerry/i.test(navigator.userAgent) ) {
      mapHeight = $(window).height() - 80;
      infowinWidth = 220;
    } else if( /iPad/i.test(navigator.userAgent) ) {
      mapHeight = $(window).height() - 250;
      infowinWidth = 400;
    } else {
      mapHeight = $(window).height() - 40;
      infowinWidth = 400;  
    }


var GoodThingsSite = {
  // All pages
  common: {
    init: function() {
      initMap();
    },
    // finalize: function() {
  
    // }
  },
  // Home page
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

// check distances
function initMap() {
  // locate pos
  if ("geolocation" in navigator) {
    if(posLocated) return;
    posLocated = true;
    if (navigator.geolocation) {
        navigator.geolocation.getCurrentPosition(goodThingsMap, error);
    } else {
        error('Geolocation not supported!');
    }
  } else {
    // set map
    goodThingsMap();    
  }
}

function error(err) {
    console.log(err.code);
    if (err.code == 1) { // user denied
      console.log("Geolocation was denied!");
      posDenied = true;
      goodThingsMap();   
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
      $('.map-icon').after( '<span class="distance-to-me">You are ' + distanceToMe.toFixed(2) + ' Kms away</span');
      $('.distance-to-me').fadeTo( "slow" , 1);
  }
  
  // set map styles
  var styles = [
    {
      stylers: [
        { hue: "#129adb"},
        { saturation: "-10"}/*,
        { invert_lightness: true} think about adding time based inversion
        var currentTime = new Date().getHours();
        if (0 <= currentTime&&currentTime < 5) {
        document.write("<link rel='stylesheet' href='night.css' type='text/css'>");
      }*/
      ]
    }, {
      featureType: "road", 
      elementType: "geometry", 
      stylers : [
        {visibility: "off"}
      ]
    }, {
      featureType: "road", 
      elementType: "geometry", 
      stylers : [
        {visibility: "off"}
      ]
    }, {
      featureType: "transit",
      elementType: "all",
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

  var customStyles = new google.maps.StyledMapType(styles, {name: "Good Things Map"});
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

  // build array from journal locations
  var journalLocs = $("select.marker-coords > option").map(function() {
      return [$.map($(this).data(), function(v) {
          return v;
      })];
  }).get();

  // build array for polylines
  var journalCoords = []
  for(var y = 1; y < journalLocs.length; y++) {
      journalCoords.push(new google.maps.LatLng(journalLocs[y][1], journalLocs[y][2]));
  }
  console.log(journalCoords);

  // polyline test
  var travelItinerary = new google.maps.Polyline({
    path: journalCoords,
    geodesic: true,
    strokeColor: '#129adb',
    strokeOpacity: 1.0,
    strokeWeight: 2
  });

  var map = new google.maps.Map(document.getElementById("map-canvas"),
      mapOptions);
  
  // Add the markers and infowindows to the map
  for (var i = 1; i < journalLocs.length; i++) {  

    var journalLocLabel = "<label class='loclabel'><span class='locnr'>" + (i) +"</span></label>";
    var marker = new MarkerWithLabel({
      url: journalLocs[i][3],
      position: new google.maps.LatLng(journalLocs[i][1], journalLocs[i][2]),
      map: map,
      icon : new google.maps.MarkerImage(markerPath,
    null, null, null, null),
      draggable: false,
      labelContent: journalLocLabel,
      labelAnchor: new google.maps.Point(4, 40),
      labelClass: "marker-label", // the CSS class for the label
      labelZIndex: i+1,
      html: "<span class='locpost-location'>" + journalLocs[i][0] + ' - ' + journalLocs[i][7] + "</span><a class='locpost-link' href='" + journalLocs[i][3] + "'><h4 class='locpost-title'>" + journalLocs[i][6] + "</h4></a><div class='locpost-preview'><img style='width: 250px; height: auto !important;' src='" + journalLocs[i][5] + "'' /><p>'" + journalLocs[i][4] + "</p><a class='locpost-link' href='" + journalLocs[i][3] + "'>Continue reading</a></div>",
      zIndex: i
    });

    infowindow = new google.maps.InfoWindow({
        content: "loading...",
        maxWidth: infowinWidth
    });

    google.maps.event.addListener(marker, 'click', function() {      
      infowindow.setContent(this.html);
      infowindow.open(map, this);
      map.panBy(-50, 0);
    });
  }

  if ("geolocation" in navigator && posDenied == false) {
    
    if (distanceToMe < 20) {
      console.log("Cool, you're really close to where i am!");
      var currentPosMarker = new MarkerWithLabel({
        position: currentPos,
        icon: new google.maps.MarkerImage(userMarkerPath,
          null, null, null, null),
        draggable: false,
        map: map,
        labelContent: "You're only " + distanceToMe.toFixed(2) + "km away!",
        labelAnchor: new google.maps.Point(-20, 22),
        labelClass: "marker-label-user", // the CSS class for the label
        labelInBackground: false
      });

      } else {

        console.log("Yay, you are aren't close to me");

        var currentPosMarker = new MarkerWithLabel({
          position: currentPos,
          icon: new google.maps.MarkerImage(markerPath,
            null, null, null, null),
          draggable: false,
          map: map,
          labelContent: "My position",
          labelAnchor: new google.maps.Point(-20, 32),
          labelClass: "marker-label", // the CSS class for the label
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
          labelClass: "marker-label-user", // the CSS class for the label
          labelInBackground: false
        });

      }
    
  } else {

    var currentPosMarker = new MarkerWithLabel({
        position: currentPos,
        icon: new google.maps.MarkerImage(markerPath,
          null, null, null, null),
        draggable: false,
        map: map,
        labelContent: "Current",
        labelAnchor: new google.maps.Point(-20, 22),
        labelClass: "marker-label", // the CSS class for the label
        labelInBackground: false
      });

  }

  map.mapTypes.set('map_style', customStyles);
  map.setMapTypeId('map_style');
  map.panBy(-50, -50);

  // add polyline
  travelItinerary.setMap(map);

  // quick jump
  $( "select.marker-coords" ).change(function() {
      var selected_lat = $(this).find(':selected').data('post-loc-lat'),
          selected_lng = $(this).find(':selected').data('post-loc-lng');
      map.setZoom(8);
      map.panTo(new google.maps.LatLng(selected_lat, selected_lng));
      infowindow.close();
      //infowindow.open(map, new google.maps.LatLng(selected_lat, selected_lng));
  });

  $('#current-loc-btn').on('click', function() {
    posMsg(currentPos, currentLocMsg);
  });

  $('#user-loc-btn').on('click', function() {
    posMsg(userPos, userLocMsg);
  });


  if (!"geolocation" in navigator && posDenied == true) {
    $('#user-loc-btn').prop('disabled', true);
  }

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

  // make map resizable
  if (mapOpen) {
    $( "#map-canvas" ).resizable({
      handles: 's',
      stop: function(event, ui) {
          $(this).css("width", '');
      },
      maxHeight: 700,
      minHeight: 200
    });
  }

  $( "#map-canvas").resize(function() {
    console.log('map resized');
    mapResized = true;
    var newHeight = $(this).height();
    if (mapOpen) {
      console.log('map resized');
      console.log(newHeight - 40);
      $('#journal-map').css({'height': newHeight, 'top' : -(newHeight)});
      google.maps.event.trigger(map, 'resize');
    }
    $('.site-head').css('marginTop', newHeight); 
  });

}

$(document).ready(function() {
  // bootstrap select
  $('.selectpicker').selectpicker();
  $('span.map-msg').text(randomTips());
  // if( /Android|webOS|iPhone|iPad|iPod|BlackBerry/i.test(navigator.userAgent) ) {
  //     $('.selectpicker').selectpicker('mobile');
  // }
  // map toggle
  $('#journal-map, #map-canvas').css('height', mapHeight);
  $('#journal-map').css({'z-index': 123456, 'top' : -(mapHeight)});
  $("#journal-map-toggle").on('click', function() {
    toggleMap();
  }); 
  $('a.maps-link').on('click', function(e) {
    e.preventDefault();
    console.log('map link clicked');
  });
});

$(window).resize(function() {
    // resizedMapHeight = $(window).height();
    // $('#journal-map, #map-canvas').css('height', resizedMapHeight);
    // $('#journal-map').css('top', -(resizedMapHeight) );
    // $('.site-head').animate({marginTop: mapHeight}, 500);
}); 

function toggleMap() {
  console.log(mapOpen);
  if(mapOpen) {
    $('span.map-msg').text(randomTips());
    $('.site-head').animate({marginTop: "0px"}, 300);
    $('#map-arrow').removeClass('arrow-up').addClass('arrow-down');
    $('#arrow-pos').removeClass('close-btn');
    $('#journal-map-caption').slideUp(300);
    $('#journal-map-toggle').text('Map');
    mapOpen = false
  } else {
    clearTimeout(captiontimer);
    if(mapResized) {
      $('.site-head').animate({ marginTop: $( "#map-canvas").height() }, 300);
    } else {
      $('.site-head').animate({marginTop: mapHeight}, 300);      
    }
    $('#map-arrow').removeClass('arrow-down').addClass('arrow-up');
    $('#arrow-pos').addClass('close-btn');
    $('#journal-map-caption').delay(200).slideDown(300);
    captiontimer = setTimeout(function() {
          $('#journal-map-caption').slideUp(300);  
    }, 6000);
    $('#journal-map-toggle').text('Close');
    mapOpen = true;
  }
}