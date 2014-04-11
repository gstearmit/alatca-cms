$( document ).ready(function() {
   //alert('test');
   //$('.alert').hide();
	
	setInterval(function(){
		$('.alert').delay(1000).fadeOut("slow")
		},1000);
	
	
	
	$('.val_select li a').click(function(){
		  $('.item_val .val_selected').text($(this).text());
		  $('#orderBy').val($(this).attr("value"));
	});
	

	$('#main-nav .navigation .nav-stacked li').each(function() {
	    var href = $(this).find('a').attr('href');
	    if (href === document.URL) {
	      $(this).addClass('active');
	      $(this).parent().parent().find('a:first,ul:first').addClass("in");
	    }
	  });
	
	
});