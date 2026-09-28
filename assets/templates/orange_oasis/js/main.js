"user strict";

// Preloader
$(window).on("load", function () {
	$(".preloader").fadeOut(1000);
});


$(window).on('load', function () {
	$('.preloader').fadeOut(1000);
	var img = $('.bg_img');
	img.css('background-image', function () {
		var bg = ('url(' + $(this).data('background') + ')');
		return bg;
	});
});

// Menu Click Event
let trigger = $(".header-trigger");
let dropdown = $(".menu");
if (trigger || dropdown) {
	trigger.each(function () {
		$(this).on("click", function (e) {
			e.stopPropagation();
			dropdown.slideToggle();
			menuToggleBtn();
		});
	});
	dropdown.each(function () {
		$(this).on("click", function (e) {
			e.stopPropagation();
			menuToggleBtn();
		});
	});
	$(document).on("click", function () {
		if (parseInt(screenSize) < parseInt(991)) {
			dropdown.slideUp();
			$(`.header-trigger`).html(`<i class="las la-bars"></i>`)
		}
	});
}

function menuToggleBtn() {
	if ($(`.header-trigger`).find(`i`).hasClass(`la-bars`)) {
		$(`.header-trigger`).html(`<i class="las la-times"></i>`)
	} else {
		$(`.header-trigger`).html(`<i class="las la-bars"></i>`)
	}
}

$(".menu-close").on("click", function () {
	$(".menu").slideUp();
});

// /=============== Header Overlay js Start =================
$('.header-trigger').on('click', function () {
	$('.overlay').toggleClass('show');
	$('body').toggleClass('scroll-hidden');
});
$('.overlay').on('click', function () {
	$(this).removeClass('show');
	$('body').removeClass('scroll-hidden')
});
// /=============== Header Overlay js End =================

//Menu Dropdown
$("ul>li>.sub-menu").parent("li").addClass("has-sub-menu");

let screenSize = window.innerWidth;
window.addEventListener("resize", function (e) {
	screenSize = window.innerWidth;
});

$(".menu li a").on("click", function (e) {
	if (parseInt(screenSize) < parseInt(991)) {
		$(this).siblings(".sub-menu").slideToggle();
	}
});

// Sticky Menu
var header = document.querySelector(".header");
if (header) {
	window.addEventListener("scroll", function () {
		header.classList.toggle("sticky", window.scrollY > 0);
	});
}

// Scroll To Top
var scrollTop = $(".scrollToTop");
$(window).on("scroll", function () {
	if ($(this).scrollTop() < 500) {
		scrollTop.removeClass("active");
	} else {
		scrollTop.addClass("active");
	}
});

//Click event to scroll to top
$(".scrollToTop").on("click", function () {
	$("html, body").animate({
			scrollTop: 0,
		},
		300
	);
	return false;
});

$(".testimonial-slider").slick({
	fade: false,
	slidesToShow: 3,
	slidesToScroll: 1,
	infinite: true,
	autoplay: true,
	pauseOnHover: true,
	centerMode: false,
	dots: false,
	arrows: false,
	nextArrow: '<i class="las la-arrow-right arrow-right"></i>',
	prevArrow: '<i class="las la-arrow-left arrow-left"></i> ',
	responsive: [{
			breakpoint: 1199,
			settings: {
				slidesToShow: 3,
			},
		},
		{
			breakpoint: 992,
			settings: {
				slidesToShow: 2,
			},
		},
		{
			breakpoint: 767,
			settings: {
				slidesToShow: 1,
			},
		},
		{
			breakpoint: 575,
			settings: {
				slidesToShow: 1,
			},
		},
	],
});

$(".brand-slider").slick({
	fade: false,
	slidesToShow: 9,
	slidesToScroll: 1,
	infinite: true,
	autoplay: true,
	pauseOnHover: true,
	centerMode: false,
	dots: false,
	arrows: false,
	nextArrow: '<i class="las la-arrow-right arrow-right"></i>',
	prevArrow: '<i class="las la-arrow-left arrow-left"></i> ',
	responsive: [{
			breakpoint: 1199,
			settings: {
				slidesToShow: 7,
			},
		},
		{
			breakpoint: 992,
			settings: {
				slidesToShow: 6,
			},
		},
		{
			breakpoint: 767,
			settings: {
				slidesToShow: 5,
			},
		},
		{
			breakpoint: 575,
			settings: {
				slidesToShow: 4,
			},
		},
	],
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

var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'))
var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
	return new bootstrap.Tooltip(tooltipTriggerEl)
})

Array.from(document.querySelectorAll('table')).forEach(table => {
	let heading = table.querySelectorAll('thead tr th');
	Array.from(table.querySelectorAll('tbody tr')).forEach((row) => {
		Array.from(row.querySelectorAll('td')).forEach((colum, i) => {
			colum.setAttribute('data-label', heading[i].innerText)
		});
	});
});

//Odometer
$(document).ready(function () {
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
});
