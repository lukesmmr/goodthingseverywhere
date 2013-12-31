// Modified http://paulirish.com/2009/markup-based-unobtrusive-comprehensive-dom-ready-execution/
// Only fires on body class (working off strictly WordPress body_class)
var posLocated = false,
    userPos,
    posDenied = false,
    infowindow = null,
    currentPos = new google.maps.LatLng($('#getloc').data('lat'), $('#getloc').data('lng')),
    websitePath = "http://localhost/goodthingseverywhere/"
    markerPath = websitePath + "wp-content/themes/goodthings/assets/img/maps-marker.svg",
    userMarkerPath = websitePath + "wp-content/themes/goodthings/assets/img/maps-marker-user.svg";


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

  var customStyles = new google.maps.StyledMapType(styles, {name: "Good Things Map"});
  var mapOptions = {
    center: currentPos,
    zoom: 6,
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

  var map = new google.maps.Map(document.getElementById("map-canvas"),
      mapOptions);
  
  // Add the markers and infowindows to the map
  for (var i = 0; i < journalLocs.length; i++) {  

    var journalLocLabel = "<label class='loclabel'><span class='locnr'>" + (i + 1) +"</span></label>";
    var marker = new MarkerWithLabel({
      url: journalLocs[i][3],
      position: new google.maps.LatLng(journalLocs[i][1], journalLocs[i][2]),
      map: map,
      icon : new google.maps.MarkerImage(markerPath,
    null, null, null, null),
      draggable: false,
      labelContent: journalLocLabel,
      labelAnchor: new google.maps.Point(4, 37),
      labelClass: "marker-label", // the CSS class for the label
      labelInBackground: true,
      html: "<a href='" + journalLocs[i][3] + "'><div class='loctitle'>" + (i + 1) +". " + journalLocs[i][0] + "</div></a>"
    });

    infowindow = new google.maps.InfoWindow({
        content: "loading..."
    });

    google.maps.event.addListener(marker, 'mouseover', function() {
    
      $(this.labelContent).css('color', 'red');
      
      infowindow.setContent(this.html);
      infowindow.open(map, this);
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
        labelContent: "Yay, you're only " + distanceToMe.toFixed(2) + "km away!",
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
          labelContent: "My current position",
          labelAnchor: new google.maps.Point(-20, 22),
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
          labelClass: "marker-label", // the CSS class for the label
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

  // quick jump
  $( "select.marker-coords" ).change(function() {
      var selected_lat = $(this).find(':selected').data('post-loc-lat'),
          selected_lng = $(this).find(':selected').data('post-loc-lng');
      map.setZoom(8);
      map.panTo(new google.maps.LatLng(selected_lat, selected_lng));
  });

}

$(document).ready(function() {
  $('#journal-map').css('z-index', 123456);
  $('#map-overlay').hide();
  $("#journal-map-toggle").on('click', function() {
    console.log($('.site-head').css("margin-top"));
    if( $('.site-head').css("margin-top")!='0px' ) {
      $('.site-head').animate({marginTop: "0px"}, 500);
    } else {
      $('.site-head').animate({marginTop: "420px"}, 500);
    }
  }); 
});