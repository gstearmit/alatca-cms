
    $("document").ready(function() {
    	
    	
    		var i =0;
		   setTimeout(function(){
		       $('#max-tietkiemchiphi').each(function( index ) {
		         	$("#chiphi img:first").hide();
		            $("#chiphi img:last").addClass('start').show();
		            //$("#chiphi img").show().addClass('start');
		            $("#chiphi img[class^='start']").next().show();
		            //alert("remove addd!"); 
		         });
		   	/*
		   	$('#max-tietkiemchiphi').each(function( index ) {
		         	$("#chiphi img[class^='view-cp']").addClass("hitden-cp").removeClass("view-cp");
		            $("#chiphi img[class^='hitden-cp']").addClass("hitden-cp").removeClass("hitden-cp");
		         });



		       $("#chiphi img:first").removeClass("view-cp");
		       $("#chiphi img:first").addClass("hitden-cp");
                   //alert("Here I am!"); 
               $("#chiphi img:last").addClass("view-cp");
               $("#chiphi img:last").removeClass("hitden-cp");
               // alert("remove addd!"); 
               */
		       
		 i++;
		 if(i==5)
		 {

		 i=0;

		 }
		}, 2000);



	    var j =0;
		   setTimeout(function(){
		      $("#chiphi img");
		       
		 j++;
		 if(j==5)
		 {

		   j=0;

		 }
		}, 2000);
  
  
   		// tooltip 


  


     
    });
/*

$(function() {
    $( document ).tooltip({
      items: "a",
      content: function() {
           var element = $( this );
           if (element.attr('id') === 'riverroad') {
               return "<img class='map' src='http://upload.wikimedia.org/wikipedia/commons/thumb/9/91/Wien_Stefansdom_DSC02656.JPG/450px-Wien_Stefansdom_DSC02656.JPG' />";
           }
        }
    });
  });

  */