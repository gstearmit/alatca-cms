var j = jQuery.noConflict();

j(function($){
j(window).load(function() {

///////////////////////////////////////FLEXSLIDER

j('.flexslider').flexslider({
     start: function(slider) {
  		slider.removeClass('loading');
	},
	slideshow: false,
	animation: "slide",
	slideshowSpeed: 7000,
    pauseOnHover: true,
    initDelay: 100, 
    touch: true,
    video: false,
    smoothHeight: true
});

j('.stories').flexslider({
     start: function(slider) {
  		slider.removeClass('loading');
	},
	animation: "slide",
    animationLoop: false,
    itemWidth: 220,
    itemMargin: 0
});

j('.hero_slider').flexslider({
     start: function(slider) {
  		slider.removeClass('loading');
	},
	slideshow: true,
	animation: "slide",
	slideshowSpeed: 33000,
    pauseOnHover: true,
    initDelay: 100, 
    touch: false,
    video: false,
    smoothHeight: true
});


j('.footer_slider').flexslider({
     start: function(slider) {
  		slider.removeClass('loading');
	},
	slideshow: true,
	animation: "slide",
	slideshowSpeed: 33000,
    pauseOnHover: true,
    initDelay: 100, 
    touch: false,
    video: false,
    smoothHeight: false
});


///////////////////////////////////////DROP IT



///////////////////////////////////////STICKY NAVIGATION
//var win      = j(window),
//    fxel     = j('#kodda_container'),
//    eloffset = fxel.offset().top;

//win.scroll(function() {
//    if (eloffset < win.scrollTop()) {
 //       fxel.addClass("fixed");
 //   } else {
 //       fxel.removeClass("fixed");
 //   }
//});
   

//END THE SLIDER, YO.
});
});