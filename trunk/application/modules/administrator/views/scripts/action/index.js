$(document).ready(function(){
	getAction(1);
	$('#search').click(function(){
		getAction(1);
    });
    $('#reset').click(function(){
    	$('#keyword').val('');
    	getAction(1);
    });
	$('#keyword').keypress(function(event){
        var keycode = (event.keyCode ? event.keyCode : event.which);
        if(keycode == '13'){
        	getAction(1); return false;
        }
 });
}); 

function deleteAction(actionId){
	if(confirm('Bạn có chắc chắn muốn xóa dữ liệu này không? Dữ liệu sẽ bị xóa và không thể phục hồi!')){
		$('#loading').show();
		$('#'+actionId).hide("slow");
		$.ajax({
            url: $("#actionUrl").val(),
            cache: false,
            type: "POST",
            data: "do=delete&id="+actionId,           
            success: function(serverData){
            	$('#loading').hide();
            }
        });
	}
}

function getAction(page){
	var ajaxData = $('#frmList').serialize()+"&do=list&page="+page;
	$('#loading').show();
	$.ajax({
        url: $("#actionUrl").val(),
        cache: false,
        type: "POST",
        data: ajaxData,           
        success: function(serverData){
        	$('#loading').hide();
        	$('#actionArea').html(serverData);
        }
      });
}
function buildNavigator(page,currentForm){
	getAction(page);
}
