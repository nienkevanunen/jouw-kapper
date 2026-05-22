jQuery(function($) {
  "use strict";

  var $window = $(window);
  var $document = $(document);
  var $body = $('body');
  var $header = $('#header');
  var $backToTop = $('.back-to-top');
  var scrollThreshold = 100;

  function updateScrollState() {
    var isScrolled = $window.scrollTop() > scrollThreshold;

    $header.toggleClass('header-scrolled', isScrolled);
    $backToTop.stop(true, true)[isScrolled ? 'fadeIn' : 'fadeOut']('slow');
  }

  function closeMobileNav() {
    if (!$body.hasClass('mobile-nav-active')) {
      return;
    }

    $body.removeClass('mobile-nav-active');
    $('#mobile-nav-toggle i').removeClass('fa-times').addClass('fa-bars');
    $('#mobile-body-overly').fadeOut();
  }

  function getHeaderOffset() {
    if (!$header.length) {
      return 0;
    }

    var offset = $header.outerHeight();
    return $header.hasClass('header-fixed') ? offset : offset - 20;
  }

  function initPlugins() {
    if (window.WOW) {
      new WOW().init();
    }

    if ($.fn.venobox) {
      $('.venobox').venobox({
        bgcolor: '',
        overlayColor: 'rgba(6, 12, 34, 0.85)',
        closeBackground: '',
        closeColor: '#fff'
      });
    }

    if ($.fn.superfish) {
      $('.nav-menu').superfish({
        animation: {
          opacity: 'show'
        },
        speed: 400
      });
    }

    if ($.fn.owlCarousel) {
      $('.gallery-carousel').owlCarousel({
        autoplay: true,
        autoHeight: true,
        center: true,
        dots: true,
        loop: true,
        responsive: {
          0: { items: 1 },
          768: { items: 3 },
          992: { items: 4 },
          1200: { items: 5 }
        }
      });
    }
  }

  function initMobileNavigation() {
    var $navContainer = $('#nav-menu-container');

    if (!$navContainer.length) {
      $('#mobile-nav, #mobile-nav-toggle').hide();
      return;
    }

    var $mobileNav = $navContainer.clone().prop({ id: 'mobile-nav' });
    $mobileNav.find('> ul').removeAttr('class id');

    $body
      .append($mobileNav)
      .prepend('<button type="button" id="mobile-nav-toggle" aria-label="Menu openen"><i class="fa fa-bars"></i></button>')
      .append('<div id="mobile-body-overly"></div>');

    $('#mobile-nav').find('.menu-has-children').prepend('<i class="fa fa-chevron-down"></i>');

    $document.on('click', '.menu-has-children i', function() {
      var $icon = $(this);

      $icon.next().toggleClass('menu-item-active');
      $icon.nextAll('ul').eq(0).slideToggle();
      $icon.toggleClass('fa-chevron-up fa-chevron-down');
    });

    $document.on('click', '#mobile-nav-toggle', function() {
      var isActive = !$body.hasClass('mobile-nav-active');

      $body.toggleClass('mobile-nav-active', isActive);
      $('#mobile-nav-toggle i').toggleClass('fa-times', isActive).toggleClass('fa-bars', !isActive);
      $('#mobile-body-overly').toggle(isActive);
    });

    $document.on('click', function(event) {
      var $container = $('#mobile-nav, #mobile-nav-toggle');

      if (!$container.is(event.target) && $container.has(event.target).length === 0) {
        closeMobileNav();
      }
    });
  }

  function initSmoothScrolling() {
    $document.on('click', '.nav-menu a, #mobile-nav a, .scrollto', function() {
      var samePage = location.pathname.replace(/^\//, '') === this.pathname.replace(/^\//, '') &&
        location.hostname === this.hostname;

      if (!samePage) {
        return true;
      }

      var $target = $(this.hash);
      if (!$target.length) {
        return true;
      }

      $('html, body').animate({
        scrollTop: $target.offset().top - getHeaderOffset()
      }, 1000, 'easeInOutExpo');

      if ($(this).parents('.nav-menu').length) {
        $('.nav-menu .menu-active').removeClass('menu-active');
        $(this).closest('li').addClass('menu-active');
      }

      closeMobileNav();
      return false;
    });
  }

  function initNewLocationModal() {
    var $modal = $('#newLocationModal');
    if (!$modal.length || !$.fn.modal) {
      return;
    }

    var today = new Date();
    var stopDate = new Date(2026, 0, 10);
    var storageKey = 'jkNewLocationPopupDismissedAt';
    var dismissWindowMs = 24 * 60 * 60 * 1000;

    if (today > stopDate) {
      return;
    }

    try {
      var lastDismissed = parseInt(localStorage.getItem(storageKey), 10);
      if (!isNaN(lastDismissed) && (Date.now() - lastDismissed) <= dismissWindowMs) {
        return;
      }
    } catch (error) {
      // localStorage can be unavailable in private browsing or strict privacy modes.
    }

    $window.on('load', function() {
      $modal.modal('show');
    });

    $modal.on('hidden.bs.modal', function() {
      try {
        localStorage.setItem(storageKey, Date.now().toString());
      } catch (error) {
        // The modal should still close even when storage writes are blocked.
      }
    });
  }

  $window.on('scroll', updateScrollState);
  updateScrollState();

  $backToTop.on('click', function() {
    $('html, body').animate({ scrollTop: 0 }, 1000, 'easeInOutExpo');
    return false;
  });

  initPlugins();
  initMobileNavigation();
  initSmoothScrolling();
  initNewLocationModal();
});
