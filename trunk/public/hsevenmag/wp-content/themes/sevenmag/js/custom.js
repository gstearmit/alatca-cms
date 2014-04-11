jQuery(document).ready(function ($) {
	if ($(".res_mode")[0]) {
		function checkWindowSize() {
			if ($(window).width() >= 1177) {
				$('.head .sf-menu').css('display', 'block').removeClass("xactive");
			} else {
				$('.head .sf-menu').css('display', 'none');
			}
			if ($(window).width() >= 959) {
				$('.sec_head .sf-menu').css('display', 'block').removeClass("xactive");
			} else {
				$('.sec_head .sf-menu').css('display', 'none');
			}
		}
	}

	"use strict";

	jQuery.browser={};(function(){jQuery.browser.msie=false;
	jQuery.browser.version=0;if(navigator.userAgent.match(/MSIE ([0-9]+)\./)){
	jQuery.browser.msie=true;jQuery.browser.version=RegExp.$1;}})();
	jQuery('#ajax-login-tabs').insertAfter('#ajax-login-wrap');
	jQuery("#layout").fitVids();

	// Superfish
	if ($(".sf-menu")[0]) {
		$('.sf-menu').superfish({
			delay: 100,
			autoArrows: true,
			animation: {
				opacity: 'none', height: 'show'
			},
			speed: 300
		});
		$('.sf-menu li li .sf-sub-indicator i').removeClass('fa-chevron-down').addClass('icon-chevron-right');
	}

	// Tabs widget
	(function() {
		var $tabsNav       = $('.T20-tabs-nav'),
			$tabsNavLis    = $tabsNav.children('li'),
			$tabsContainer = $('.T20-tabs-container');

		$tabsNav.each(function() {
			var $this = $(this);
			$this.next().children('.T20-tab').stop(true,true).hide()
			.siblings( $this.find('a').attr('href') ).show();
			$this.children('li').first().addClass('active').stop(true,true).show();
		});

		$tabsNavLis.on('click', function(e) {
			var $this = $(this);

			$this.siblings().removeClass('active').end()
			.addClass('active');
			
			$this.parent().next().children('.T20-tab').stop(true,true).hide()
			.siblings( $this.find('a').attr('href') ).fadeIn();
			e.preventDefault();
		}).children( window.location.hash ? 'a[href=' + window.location.hash + ']' : 'a:first' ).trigger('click');

	})();

	// Responsive
	if ($(".res_mode")[0]) {
		if ($(".head .sf-menu")[0]) {
			$(".head nav").before('<div id="mobilepro"><i class="fa fa-bars fa-times"></i></div>');
		}
		if ($(".sec_head .sf-menu")[0]) {
			$(".sec_head nav").before('<div id="mobilepro"><i class="fa fa-bars fa-times"></i></div>');
		}
		$(".sf-menu a.sf-with-ul").before('<div class="subarrow"><i class="fa fa-angle-down"></i></div>');
		$('.head .subarrow').click(function () {
			$(this).parent().toggleClass("xpopdrop");
		});
		$('.sec_head .subarrow').click(function () {
			$(this).parent().toggleClass("xpopdrop");
		});
		$('.head #mobilepro').click(function () {
			$('.head .sf-menu').slideToggle('slow', 'easeInOutExpo').toggleClass("xactive");
			$(".head #mobilepro i").toggleClass("fa-reorder");
		});
		$('.sec_head #mobilepro').click(function () {
			$('.sec_head .sf-menu').slideToggle('slow', 'easeInOutExpo').toggleClass("xactive");
			$(".sec_head #mobilepro i").toggleClass("fa-reorder");
		});
		$("body").click(function() {
			$('.head .xactive').slideUp('slow', 'easeInOutExpo').removeClass("xactive");
			$(".head #mobilepro i").addClass("fa-reorder");
		});
		$("body").click(function() {
			$('.sec_head .xactive').slideUp('slow', 'easeInOutExpo').removeClass("xactive");
			$(".sec_head #mobilepro i").addClass("fa-reorder");
		});
		$('.head #mobilepro, .head .sf-menu').click(function(e) {
			e.stopPropagation();
		});
		$('.sec_head #mobilepro, .sec_head .sf-menu').click(function(e) {
			e.stopPropagation();
		});
		$(window).load(checkWindowSize);
		$(window).resize(checkWindowSize);
	}

	// ToTop
	jQuery('#toTop').click(function () {
		jQuery('body,html').animate({
			scrollTop: 0
		}, 1000);
	});
	jQuery("#toTop").addClass("hidett");
	jQuery(window).scroll(function () {
		if (jQuery(this).scrollTop() < 400) {
			jQuery("#toTop").addClass("hidett").removeClass("showtt");
		} else {
			jQuery("#toTop").removeClass("hidett").addClass("showtt");
		}
	});

	// Slideshow
	if ($("[class^='gallery_']")[0]) {
		$("[class^='gallery_']").owlCarousel({
			slideSpeed : 500,
			paginationSpeed : 500,
			autoPlay: true,
			autoHeight: true,
			stopOnHover: true,
			transitionStyle : "goDown",
			singleItem:true,
			navigation : true,
			navigationText : ["<i class='fa fa-chevron-left'></i>","<i class='fa fa-chevron-right'></i>"],
			rewindNav : true,
			scrollPerPage : false,
			pagination : false
		});
	}
	if ($("[class^='slideshow_']")[0]) {
		$("[class^='slideshow_b']").owlCarousel({
			slideSpeed : 500,
			paginationSpeed : 1500,
			autoPlay: true,
			autoHeight: true,
			stopOnHover: true,
			transitionStyle : "goDown",
			singleItem:true,
			navigation : false,
			rewindNav : true,
			scrollPerPage : false,
			pagination : true
		});
	}

	if ($("[class^='carousel_posts_']")[0]) {
		$(".grid_2 [class^='carousel_posts_'], .grid_3 [class^='carousel_posts_']").owlCarousel({
			slideSpeed : 600,
			paginationSpeed : 1000,
			items : 1,
			autoPlay: true,
			stopOnHover: true,
			autoHeight: false,
			responsive: true,
			pagination: true,
			paginationNumbers: false
		});
		$(".grid_4 [class^='carousel_posts_']").owlCarousel({
			slideSpeed : 600,
			paginationSpeed : 1000,
			items : 2,
			autoPlay: true,
			stopOnHover: true,
			autoHeight: false,
			responsive: true,
			pagination: true,
			paginationNumbers: false
		});
		$(".grid_8 [class^='carousel_posts_'], .grid_6 [class^='carousel_posts_'], .grid_7 [class^='carousel_posts_'], .grid_5 [class^='carousel_posts_']").owlCarousel({
			slideSpeed : 600,
			paginationSpeed : 1000,
			items : 3,
			autoPlay: true,
			stopOnHover: true,
			autoHeight: false,
			responsive: true,
			pagination: true,
			paginationNumbers: false
		});
		$(".grid_10 [class^='carousel_posts_'], .grid_9 [class^='carousel_posts_']").owlCarousel({
			slideSpeed : 600,
			paginationSpeed : 1000,
			items : 4,
			autoPlay: true,
			stopOnHover: true,
			autoHeight: false,
			responsive: true,
			pagination: true,
			paginationNumbers: false
		});
		$(".grid_11 [class^='carousel_posts_'], .grid_12 [class^='carousel_posts_']").owlCarousel({
			slideSpeed : 600,
			paginationSpeed : 1000,
			items : 5,
			autoPlay: true,
			stopOnHover: true,
			autoHeight: false,
			responsive: true,
			pagination: true,
			paginationNumbers: false
		});
	}

	// Search
	var popupStatus = 0;
	$(".search_icon").click(function() {
		var windowWidth = document.documentElement.clientWidth;
		var windowHeight = document.documentElement.clientHeight;
		var popupHeight = $("#popup").height();
		var popupWidth = $("#popup").width();
		$("#popup").css({
			"top": windowHeight / 2 - popupHeight / 2,
			"left": windowWidth / 2 - popupWidth / 2
		});
		// Aligning bg
		$("#SearchBackgroundPopup").css({"height": windowHeight});
		if (popupStatus == 0) {
			$("#SearchBackgroundPopup").css({"opacity": "0.9", "background": "#000"});
			$("#SearchBackgroundPopup").fadeIn(500);
			$("#popup").fadeIn();
			popupStatus = 1;
		}
		$('#inputhead').select();
	});
	// Close Popup
	$("#popupLoginClose").click(function() {
		if (popupStatus == 1) {
			$("#SearchBackgroundPopup").fadeOut(500);
			$("#popup").fadeOut(500);
			popupStatus = 0;
		}
	});
	$("body").click(function() {
		$("#SearchBackgroundPopup").fadeOut(500);
		$("#popup").fadeOut(500);
		popupStatus = 0;
	});
	$('#popup, .search_icon').click(function(e) {
		e.stopPropagation();
	});

	// Tipsy
	$('.toptip').tipsy({fade: true,gravity: 's'});
	$('.bottomtip').tipsy({fade: true,gravity: 'n'});
	$('.righttip').tipsy({fade: true,gravity: 'w'});
	$('.lefttip').tipsy({fade: true,gravity: 'e'});
});