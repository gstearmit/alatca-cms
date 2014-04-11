$(document).ready(function(){
	getMenu(1);
	$('#search').click(function(){
		getMenu(1);
    });
    $('#reset').click(function(){
    	$('#keyword').val('');
    	getMenu(1);
    });
	$('#keyword').keypress(function(event){
        var keycode = (event.keyCode ? event.keyCode : event.which);
        if(keycode == '13'){
        	getMenu(1); return false;
        }
 });
}); 

function deleteMenu(menuId){
	if(confirm('Bạn có chắc chắn muốn xóa dữ liệu này không? Dữ liệu sẽ bị xóa và không thể phục hồi!')){
		$('#loading').show();
		$('#'+menuId).hide("slow");
		$.ajax({
            url: $("#menuUrl").val(),
            cache: false,
            type: "POST",
            data: "do=delete&id="+menuId,           
            success: function(serverData){
            	$('#loading').hide();
            }
        });
	}
}

function getMenu(page){
	var ajaxData = $('#frmList').serialize()+"&do=list&page="+page;
	$('#loading').show();
	$.ajax({
        url: $("#menuUrl").val(),
        cache: false,
        type: "POST",
        data: ajaxData,           
        success: function(serverData){
        	$('#loading').hide();
        	$('#menuArea').html(serverData);
        }
      });
}
function buildNavigator(page,currentForm){
	getMenu(page);
}
