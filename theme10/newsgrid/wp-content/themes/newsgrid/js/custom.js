var j = jQuery.noConflict();

j(document).ready(function(){

j('#simple-menu').sidr({

side: 'right'

});

//////////////////////////////////////////////////////////////////////////////////FIT VID


jQuery('.widget-area ul li').hover(function() {
        jQuery(this).animate({ paddingLeft: '15px' }, 100);
    }, function() {
        jQuery(this).animate({ paddingLeft: '10px' }, 100);
  });

//////////////////////////////////////////////////////////////////////////////////FIT TEXT

   
  
j("a[rel^='prettyPhoto2']").prettyPhoto();
 
j('.bs-tooltip').tooltip();


});