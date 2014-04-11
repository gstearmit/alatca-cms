$( document ).ready(function() {
    
	setInterval(function(){
		$('.alert').delay(1000).fadeOut("slow")
		},1000);
	
	
	
	$('.val_select li a').click(function(){
		  $('.item_val .val_selected').text($(this).text());
		  $('#orderBy').val($(this).attr("value"));
	});
	

	$('.collapse.navbar-collapse.navbar-ex1-collapse .nav.navbar-nav.navbar-right.nav_top li').each(function() {
	    var href = $(this).find('a').attr('href');
	    if (href === document.URL) {
	      $(this).addClass('active');
	      $(this).parent().parent().find('a:first,ul:first').addClass("in");
	    }
	  });
	
	// css top menu topnav - 02
	/*
	$("ul#topnav li").hover(function() { //Hover over event on list item
		$(this).css({ 'background' : 'url("../img/bg-menu-topnav-2.png") repeat-x','color':'#69c4df','text-decoration': 'none'}); //Add background color + image on hovered list item
		$(this).find("span").show(); //Show the subnav
	} , function() { //on hover out...
		$(this).css({ 'background' : 'none'}); //Ditch the background
		$(this).find("span").hide(); //Hide the subnav
	});
		
	});
	*/
	
});