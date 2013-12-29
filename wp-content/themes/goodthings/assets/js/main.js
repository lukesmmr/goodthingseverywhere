// Modified http://paulirish.com/2009/markup-based-unobtrusive-comprehensive-dom-ready-execution/
// Only fires on body class (working off strictly WordPress body_class)
var posLocated = false;
var GoodThingsSite = {
  // // All pages
  // common: {
  //   init: function() {

  //   },
  //   finalize: function() {
  
  //   }
  // },
  // Home page
  home: {
    init: function() {
      $(".giant-title").fitText();
      calculateDistance();
      google.maps.event.addDomListener(window, 'load', goodThingsMap);
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
function calculateDistance() {
  if(posLocated) return;
  posLocated = true;
  if (navigator.geolocation) {
      navigator.geolocation.getCurrentPosition(success, error);
  } else {
      error('Geolocation not supported!');
  }
}

function success(position) {
    var userPos =  new google.maps.LatLng(position.coords.latitude, position.coords.longitude),
    currentPos =    new google.maps.LatLng($('#getloc').data('lat'), $('#getloc').data('lng')),
    distanceToMe =  (google.maps.geometry.spherical.computeDistanceBetween (userPos, currentPos)) / 1000;
    $('.map-icon').after( '<span class="distance-to-me">You are ' + distanceToMe.toFixed(2) + ' Kms away</span');
    $('.distance-to-me').fadeTo( "slow" , 1);
}

function error(msg) {
   console.log(msg);
}

function goodThingsMap() {

  var styles = [
    {
      stylers: [
        { hue: "#129adb"},
        { saturation: "-10"}
      ]
    }, {
      featureType: "road", 
      elementType: "geometry", 
      stylers : [
        {lightness: 100},
        {visibility: "simplified"}
      ]
    }, {
      featureType: "road",
      elementType: "labels",
      stylers: [
        { visibility: "off" }
      ]
    }
  ];

  var currentPos = new google.maps.LatLng($('#getloc').data('lat'), $('#getloc').data('lng')); 
  var customStyles = new google.maps.StyledMapType(styles, {name: "Good Things Map"});
  var mapOptions = {
    center: currentPos,
    zoom: 5,
    mapTypeControlOptions: {
        mapTypeIds: [google.maps.MapTypeId.ROADMAP, 'map_style']
    },
    disableDefaultUI: false
  };

  // build array from journal locations
  var journalLocs = $(".marker-coords > span").map(function() {
      return [$.map($(this).data(), function(v) {
          return v;
      })];
  }).get();

  var map = new google.maps.Map(document.getElementById("map-canvas"),
      mapOptions);
    
  // Add the markers and infowindows to the map
  for (var i = 0; i < journalLocs.length; i++) {  

    var journalLocLabel = "<label><span class='locnr'>" + (i + 1) +"</span> <a href='" + journalLocs[i][3] + "'>" + journalLocs[i][0] + "</a></label>";
    marker = new MarkerWithLabel({
      position: new google.maps.LatLng(journalLocs[i][1], journalLocs[i][2]),
      map: map,
      icon : new google.maps.MarkerImage('wp-content/themes/goodthings/assets/img/maps-marker.svg',
    null, null, null, null),
      draggable: false,
      labelContent: journalLocLabel,
      labelAnchor: new google.maps.Point(-20, 22),
      labelClass: "marker-label", // the CSS class for the label
      labelInBackground: true
    });
  }

  // external SVG Marker
  var currentPostMarker = new MarkerWithLabel({
    position: currentPos,
    icon: new google.maps.MarkerImage('wp-content/themes/goodthings/assets/img/maps-marker.svg',
      null, null, null, null),
    draggable: false,
    map: map,
    labelContent: "Current",
    labelAnchor: new google.maps.Point(-20, 22),
    labelClass: "marker-label", // the CSS class for the label
    labelInBackground: false
  });

  map.mapTypes.set('map_style', customStyles);
  map.setMapTypeId('map_style');
  map.panBy(-50, -50);

}

$(document).ready(function() {
  $("#journal-map-toggle").on('click', function() {
    // temporary map toggle
    if ( $('#journal-map').css('z-index') < 12345 ) {
      $('#journal-map').css('z-index', 123456);
      $('#map-overlay').hide();
    } else {
      $('#journal-map').css('z-index', -3);      
      $('#map-overlay').show();
    }
  }); 
});