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
    zoom: 12,
    mapTypeControlOptions: {
        mapTypeIds: [google.maps.MapTypeId.ROADMAP, 'map_style']
    },
    disableDefaultUI: true
  };
  var map = new google.maps.Map(document.getElementById("map-canvas"),
      mapOptions);

  // SVG Marker
  // var currentPosMarker = new google.maps.Marker({
  //     position: currentPos,
  //     map: map,
  //     icon: {
  //       path: 'M 100 0 L 0 0 L 0 100 L 35 100 L 50 120 L 65 100 L 100 100 Z',
  //       fillColor: '#129adb',
  //       fillOpacity: 1,
  //       strokeColor: '',
  //       strokeWeight: 0,
  //       scale: 1/2
  //   }
  // });

  map.mapTypes.set('map_style', customStyles);
  map.setMapTypeId('map_style');
  map.panBy(200, 200);

}
