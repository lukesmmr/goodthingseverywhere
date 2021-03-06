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
      var currentLat = $('#loc-settings').data('lat');
      var currentLng = $('#loc-settings').data('lng');
      if (currentLat && currentLng) {
        distanceToUser();
      }

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
       $.getScript(template_dir + "/assets/js/keys.js", function() {
         $.ajax({
            type: "GET",
            dataType: "jsonp",
            cache: false,
            url: "https://api.instagram.com/v1/users/2167719/media/recent/?access_token=" + insta_token,
            success: function(data) {
              $('.insta-pic').html('');
              for (i = 0; i < 2; i++) {
                var createdAt = timeAgo(data.data[i].caption.created_time);
                var locationName = '';
                if (typeof(data.data[i].location) !== "undefined" && data.data[i].location !== null) {
                  locationName = 'in ' + data.data[i].location.name;
                }
                var instaLink = data.data[i].link;
                var instaImgSrc = data.data[i].images.standard_resolution.url;
                var captionText = data.data[i].caption.text;
                $('.insta-pic').append('<div class="insta-container"><div class="insta-meta"><i class="glyphicon glyphicon-map-marker"></i> <div class="insta-date">' + createdAt + '</div> <div class="insta-loc"><span>' + locationName + '</span></div></div><a href="' + instaLink +'" title="Link to latest Instagram Photo" target="_blank" class="insta-link"><div class="instagram-img" style="background-image: url(' + instaImgSrc + ');"></div></a><figcaption class="caption insta-caption wp-caption-text">' + captionText + '</figcaption></div>');
              }
            }
         });
       });
    }
  },
  work: {
    init: function() {
      var gridOptions = {
        columnWidth: '.grid-sizer',
        gutter: '.gutter-sizer',
        itemSelector: '.project-item',
        percentPosition: true,
        transitionDuration: '0.2s'
      };
      var $grid = $('.portfolio-grid').masonry( gridOptions );
      // layout Masonry after each image loads
      $grid.imagesLoaded().progress( function() {
        $grid.masonry('layout');
      });

      $('.filter-projects li').on('click', function(){
        var $filter = $(this).attr('class');
        $(this).siblings().removeClass('active');
        if ($(this).hasClass('active')) {
          $(this).removeClass('active');
          $("article.project").not($filter).show();
          $('article.' + $filter).toggle();
        } else {
          $(this).parent().parent().find('li').removeClass('active');
          $(this).addClass('active');
          $("article.project").not($filter).hide();
          $('article.' + $filter).toggle();
        }
        $grid.masonry( gridOptions ); // re-initialize
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
