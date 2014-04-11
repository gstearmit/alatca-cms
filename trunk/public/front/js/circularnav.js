var wrapper = $('.cn-wrapper'),
    items = $('.cn-wrapper li'),
    anchors = $('.cn-wrapper li a');
/*
step1();

function step1(){
      items.css({
        'left': '50%',
        'margin-top': '-1.4em',
        'margin-left': '-10em',
        'overflow': 'hidden'
      });
	  
	  items.each(function(i, el){
      var angle = i * 40 - 10;
      $(this).css({
        'transform': 'rotate('+angle+'deg) skew(50deg)'
      });
    });
	
	anchors.css({
      'transform': 'skew(-50deg) rotate(-70deg) scale(1)',
      'border-radius': '50%',
      'text-align': 'center',
      'padding-top': '2em'
    });
	
	wrapper.css({'border-radius': '50%', 'overflow': 'hidden'});
  
  }

*/




$('li.nav-header').hover(function(){ 

var c = $(this).index() +1;
var j = ".service-" + c + " .cn-content";
var k = ".service-" + c;

$(".service").hide(); 
$(k).show().stop(); 

$(".cn-content").animate({ "opacity": 0 }, 300, 'easeInOutCubic'); 
    $(j).animate({ "opacity": 1 }, 400, 'easeInOutCubic').stop(); 

});



  $(".service-1").animate(500, 'easeInOutCubic').show(); 
  $(".service-1 .cn-content").animate({ "opacity": 1 }, 400, 'easeInOutCubic'); 
	
	
	$('.cn-wrapper ul li a').hover(function() {
		alert('hoang phucsssssss');
		$('.cn-wrapper ul li a').removeClass('active');
        $(this).addClass('active');
})


$("a[href='#']").removeAttr("href").css("cursor","pointer");