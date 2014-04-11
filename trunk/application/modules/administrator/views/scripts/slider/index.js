$(document).ready(function(){
	getslider(1);
	$('#search').click(function(){
		getslider(1);
    });
    $('#reset').click(function(){
    	$('#keyword').val('');
    	getslider(1);
    });
	$('#keyword').keypress(function(event){
        var keycode = (event.keyCode ? event.keyCode : event.which);
        if(keycode == '13'){
        	getslider(1); return false;
        }
 });
}); 

function deleteslider(sliderId){
	if(confirm('Bạn có chắc chắn muốn xóa dữ liệu này không? Dữ liệu sẽ bị xóa và không thể phục hồi!')){
		$('#loading').show();
		$('#'+sliderId).hide("slow");
		$.ajax({
            url: $("#sliderUrl").val(),
            cache: false,
            type: "POST",
            data: "do=delete&id="+sliderId,           
            success: function(serverData){
            	$('#loading').hide();
            }
        });
	}
}

function getslider(page){
	var ajaxData = $('#frmList').serialize()+"&do=list&page="+page;
	$('#loading').show();
	$.ajax({
        url: $("#sliderUrl").val(),
        cache: false,
        type: "POST",
        data: ajaxData,           
        success: function(serverData){
        	$('#loading').hide();
        	$('#sliderArea').html(serverData);
        }
      });
}
function buildNavigator(page,currentForm){
	getslider(page);
}
