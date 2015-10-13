/* @goodthngs / lukassommer - goodthingseverywhere.com - 2013/2014 */

// init
var GoodThingsSite = {
  // specific pages
  common: {
    init: function() {

      $('#user-loc-btn').prop('disabled', true);
      if( /iPad/i.test(navigator.userAgent) ) {
          $('.selectpicker').selectpicker('mobile');
      } else {
          $('.selectpicker').selectpicker();
          $('.selectpicker').selectpicker('deselectAll');
      }

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
      $('.open-map').on('click', function() {
        $('.home').scrollTop(0);
        if(!mapOpen) {
          toggleMap();
        }
      });
      $('.carousel').carousel({
        interval: 10000
      });
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
                $('.insta-pic').append('<div class="insta-container"><div class="insta-meta"><i class="glyphicon glyphicon-map-marker"></i> <div class="insta-date">' + timeAgo(data.data[i].caption.created_time) + '</div> <div class="insta-loc"><span>' + ( data.data[i].location.name !== undefined ? 'in ' + data.data[i].location.name : '' ) + '</span></div></div><a href="' + data.data[i].link +'" title="Link to latest Instagram Photo" target="_blank" class="insta-link"><div class="instagram-img"><img src="' + data.data[i].images.standard_resolution.url + '" alt="" /></div></a><figcaption class="caption insta-caption wp-caption-text">' + data.data[i].caption.text + '</figcaption></div>');
              }
            }
         });
       });
    }
  },
  work: {
    init: function() {
      var container = jQuery('.portfolio-grid');
      // initialize Masonry after all images have loaded
      container.imagesLoaded( function() {
        container.masonry({
          columnWidth: '.grid-sizer',
          gutter: '.gutter-sizer',
          itemSelector: '.project-item',
          percentPosition: true,
          transitionDuration: 0,
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
