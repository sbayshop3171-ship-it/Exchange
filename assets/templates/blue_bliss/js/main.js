(function ($) {
  "user strict";
  // Preloader Js
  $(window).on('load', function () {
    $('.preloader').fadeOut(1000);
    var img = $('.bg_img');
    img.css('background-image', function () {
      var bg = ('url(' + $(this).data('background') + ')');
      return bg;
    });
  });
  $(document).ready(function () {
    const tooltipTriggerList = document.querySelectorAll('[data-bs-toggle="tooltip"]')
    const tooltipList = [...tooltipTriggerList].map(tooltipTriggerEl => new bootstrap.Tooltip(tooltipTriggerEl))

    // Nice Select
    $('.select-bar').niceSelect();

    // aos js active
    new WOW().init()
    //Faq
    $('.faq-wrapper .faq-title').on('click', function (e) {
      var element = $(this).parent('.faq-item');
      if (element.hasClass('open')) {
        element.removeClass('open');
        element.find('.faq-content').removeClass('open');
        element.find('.faq-content').slideUp(300, "swing");
      } else {
        element.addClass('open');
        element.children('.faq-content').slideDown(300, "swing");
        element.siblings('.faq-item').children('.faq-content').slideUp(300, "swing");
        element.siblings('.faq-item').removeClass('open');
        element.siblings('.faq-item').find('.faq-title').removeClass('open');
        element.siblings('.faq-item').find('.faq-content').slideUp(300, "swing");
      }
    });
    //Menu Dropdown Icon Adding
    $("ul>li>.sub-menu").parent("li").addClass("menu-item-has-children");
    // drop down menu width overflow problem fix

    $('ul').parent('li').hover(function () {
      var menu = $(this).find("ul");
      var menupos = $(menu).offset();
      if (menupos.left + menu.width() > $(window).width()) {
        var newpos = -$(menu).width();
        menu.css({
          left: newpos
        });
      }
    });

    $('.menu li a').on('click', function (e) {
      var element = $(this).parent('li');
      if (element.hasClass('open')) {
        element.removeClass('open');
        element.find('li').removeClass('open');
        element.find('ul').slideUp(300, "swing");
      } else {
        element.addClass('open');
        element.children('ul').slideDown(300, "swing");
        element.siblings('li').children('ul').slideUp(300, "swing");
        element.siblings('li').removeClass('open');
        element.siblings('li').find('li').removeClass('open');
        element.siblings('li').find('ul').slideUp(300, "swing");
      }
    });

    // Scroll To Top 
    var scrollTop = $(".scrollToTop");
    $(window).on('scroll', function () {
      if ($(this).scrollTop() < 500) {
        scrollTop.removeClass("active");
      } else {
        scrollTop.addClass("active");
      }
    });
    //Click event to scroll to top
    $('.scrollToTop').on('click', function () {
      $('html, body').animate({
        scrollTop: 0
      }, 500);
      return false;
    });

    //Header Bar
    $('.header-bar').on('click', function () {
      $(this).toggleClass('active');
      $('.overlay').toggleClass('active');
      $('.menu').toggleClass('active');
    })
    //Header Bar
    $('.overlay').on('click', function () {
      $(this).removeClass('active');
      $('.header-bar').removeClass('active');
      $('.menu').removeClass('active');
      $('.header-top-area').removeClass('active');
    })
    $('.ellipsis-bar').on('click', function () {
      $('.header-top-area').toggleClass('active');
      $('.overlay').addClass('active');
    })
    //Header
    var fixed_top = $(".header-bottom");
    $(window).on('scroll', function () {
      if ($(this).scrollTop() > 500) {
        fixed_top.addClass("fixed__header animated fadeInDown");
      } else {
        fixed_top.removeClass("fixed__header fadeInDown");
      }
    });
    //Tab Section
    // $('.tab ul.tab-menu').addClass('active').find('> li:eq(0)').addClass('active');
    $('.tab ul.tab-menu li').on('click', function (g) {
      var tab = $(this).closest('.tab'),
        index = $(this).closest('li').index();
      tab.find('li').siblings('li').removeClass('active');
      $(this).closest('li').addClass('active');
      tab.find('.tab-area').find('div.tab-item').not('div.tab-item:eq(' + index + ')').hide(10);
      tab.find('.tab-area').find('div.tab-item:eq(' + index + ')').fadeIn(10);
      g.preventDefault();
    });
    //Odometer
    $(".counter-item").each(function () {
      $(this).isInViewport(function (status) {
        if (status === "entered") {
          for (var i = 0; i < document.querySelectorAll(".odometer").length; i++) {
            var el = document.querySelectorAll('.odometer')[i];
            el.innerHTML = el.getAttribute("data-odometer-final");
          }
        }
      });
    });

    //client slider
    var swiper = new Swiper('.service-slider', {
      slidesPerView: 3,
      loop: true,
      spaceBetween: 30,
      breakpoints: {
        991: {
          slidesPerView: 2,
        },
        767: {
          slidesPerView: 1,
        },
      },
      speed: 300,
      pagination: {
        el: '.common-pagination',
        clickable: true,
      },
      loop: true,
      autoplay: {
        delay: 2500,
        disableOnInteraction: true,
      },
    });
    var swiper = new Swiper('.sponsor-slider', {
      slidesPerView: 6,
      loop: true,
      spaceBetween: 30,
      breakpoints: {
        1199: {
          slidesPerView: 5,
        },
        991: {
          slidesPerView: 3,
        },
        767: {
          slidesPerView: 2,
        },
        500: {
          slidesPerView: 1,
        },
      },
      speed: 300,
      loop: true,
      autoplay: {
        delay: 1000,
        disableOnInteraction: false,
      },
    });
    var swiper = new Swiper('.client-slider', {
      loop: true,
      slidesPerView: 2,
      spaceBetween: 30,
      autoplay: {
        delay: 2000,
        disableOnInteraction: false,
      },
      breakpoints: {
        767: {
          slidesPerView: 1,
        },
      },
      pagination: {
        el: '.common-pagination',
        clickable: true,
      },
    });
    var swiper = new Swiper('.team-slider', {
      loop: true,
      slidesPerView: 3,
      spaceBetween: 30,
      autoplay: {
        delay: 2000,
        disableOnInteraction: false,
      },
      breakpoints: {
        991: {
          slidesPerView: 2,
        },
        767: {
          slidesPerView: 1,
        },
      },
    });
  });

})(jQuery);

Array.from(document.querySelectorAll('table')).forEach(table => {
  let heading = table.querySelectorAll('thead tr th');
  Array.from(table.querySelectorAll('tbody tr')).forEach((row) => {
    Array.from(row.querySelectorAll('td')).forEach((colum, i) => {
      colum.setAttribute('data-label', heading[i].innerText)
    });
  });
});

const NOTICE = document.getElementById('top-notice');
if (NOTICE) {
  $(".notice-close").on("click", function () {
    if (!localStorage.getItem('NOTICE_BAR_CLOSED')) {
      localStorage.setItem('NOTICE_BAR_CLOSED', true);
    }
    NOTICE.remove();
  });

  if (localStorage.getItem('NOTICE_BAR_CLOSED')) {
    NOTICE.remove();
  }
}