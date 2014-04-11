$(document).ready(function(){
	drawPage();
});

function drawPage(){
	var sidebar_height = $('#sidebar').height();
	var main_height = $('#main').height();
	
	if(sidebar_height > main_height){
		$("#main").css('height', sidebar_height - 2);
	}else{
		$("#sidebar").css('height', main_height + 2);
	}
}

function is_number(number){
   var ValidChars = "0123456789.";
   var IsNumber=true;
   var Char;							
 
   for (i = 0; i < number.length && IsNumber == true; i++) 
	  { 
	  Char = number .charAt(i); 
	  if (ValidChars.indexOf(Char) == -1) 
		 {
			IsNumber = false;											
		 }
	  }
   return IsNumber;
   
}