/* ========================================================================
 * DOM-based Routing
 * Based on http://goo.gl/EUTi53 by Paul Irish
 *
 * Only fires on body classes that match. If a body class contains a dash,
 * replace the dash with an underscore when adding it to the object below.
 *
 * .noConflict()
 * The routing is enclosed within an anonymous function so that you can 
 * always reference jQuery with $, even when in .noConflict() mode.
 *
 * Google CDN, Latest jQuery
 * To use the default WordPress version of jQuery, go to lib/config.php and
 * remove or comment out: add_theme_support('jquery-cdn');
 * ======================================================================== */

(function($) {

// Use this variable to set up the common and page specific functions. If you 
// rename this variable, you will also need to rename the namespace below.
var Roots = {
  // All pages
  common: {
    init: function() {
      // JavaScript to be fired on all pages

      $('.section-header').fitText(0.75);

      // var sectionHeader = $('h3.section-header');
      // if(sectionHeader.text().indexOf(' ')>0) {
      //   sectionHeader.addClass('section-header_two');
      // }
      var lastScrollTop = 0;
      $(document).on( 'scroll', function(){
          var st = $(window).scrollTop();
          var headerHeight = $(".front-page-header").height();
          if (!headerHeight) {
            headerHeight = 0;
          }
          
          var nav = $(".navbar");

          if(st < lastScrollTop)
          {
            nav.fadeIn("");
            if (st > headerHeight) {
              nav.addClass("navbar-fixed-top");
            }
          }

          if(st > lastScrollTop)
          {
               if (st > headerHeight) {
                nav.fadeOut("", function() {
                   nav.removeClass("navbar-fixed-top");
                });
              } else {
                nav.removeClass("navbar-fixed-top");
              }
          }
          
          lastScrollTop = st;
      });

      var modalElement = $('#myModal');
      var mapsDiv = $('.google-maps');
      var options = {
          show: false
      };
      modalElement.modal(options);

      modalElement.on('shown.bs.modal', function(e) {
        $('body').trigger('tabsactivate');
      });
    }
  },
  // Home page
  home: {
    init: function() {
      // JavaScript to be fired on the home page
      var template = '<div class="col-xs-6 col-sm-6 col-md-3 padding-vertical"><a href="{{link}}"><img src="{{image}}" class="instagram"></a></div>';
      var feed = new Instafeed({
          get: 'tagged',
          tagName: 'cider',
          clientId: '3cb907cd5f6542a68f1092b22c4b92a2',
          resolution: 'low_resolution',
          limit: 8,
          "template": template
      });
      feed.run();
     
      $("#mobile-post-slider").swiperight(function() {
        $(this).carousel('prev');
      });
      $("#mobile-post-slider").swipeleft(function() {
        $(this).carousel('next');
      });

      
    }
  },
  // About us page, note the change from about-us to about_us.
  about_us: {
    init: function() {
      // JavaScript to be fired on the about us page
    }
  }
};

// The routing fires all common scripts, followed by the page specific scripts.
// Add additional events for more control over timing e.g. a finalize event
var UTIL = {
  fire: function(func, funcname, args) {
    var namespace = Roots;
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
  }
};

$(document).ready(UTIL.loadEvents);

})(jQuery); // Fully reference jQuery after this point.
