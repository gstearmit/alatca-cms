$(document).ready(function(){
	getLanguage(1);
	$('#search').click(function(){
		getLanguage(1);
    });
    $('#reset').click(function(){
    	$('#keyword').val('');
    	getLanguage(1);
    });
	$('#keyword').keypress(function(event){
        var keycode = (event.keyCode ? event.keyCode : event.which);
        if(keycode == '13'){
        	getLanguage(1); return false;
        }
 });
}); 

function saveLang(){
	var ajaxData = $('#frmList').serialize()+"&do=save";
	$('#loading').show();
	$.ajax({
        url: $("#languageUrl").val(),
        cache: false,
        type: "POST",
        data: ajaxData,           
        success: function(serverData){
        	$('#loading').hide();
        	$('#keyword').val('');
        	getLanguage(1);
        }
      });
}

function getLanguage(page){
	var ajaxData = $('#frmList').serialize()+"&do=list&page="+page;
	$('#loading').show();
	$.ajax({
        url: $("#languageUrl").val(),
        cache: false,
        type: "POST",
        data: ajaxData,           
        success: function(serverData){
        	$('#loading').hide();
        	$('#languageArea').html(serverData);
        }
      });
}
function buildNavigator(page,currentForm){
	getLanguage(page);
}
