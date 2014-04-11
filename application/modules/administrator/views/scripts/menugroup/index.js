$(document).ready(function(){
	getMenugroup(1);
	$('#search').click(function(){
		getMenugroup(1);
    });
    $('#reset').click(function(){
    	$('#keyword').val('');
    	getMenugroup(1);
    });
	$('#keyword').keypress(function(event){
        var keycode = (event.keyCode ? event.keyCode : event.which);
        if(keycode == '13'){
        	getMenugroup(1); return false;
        }
 });
}); 

function deleteMenugroup(menugroupId){
	if(confirm('Bạn có chắc chắn muốn xóa dữ liệu này không? Dữ liệu sẽ bị xóa và không thể phục hồi!')){
		$('#loading').show();
		$('#'+menugroupId).hide("slow");
		$.ajax({
            url: $("#menugroupUrl").val(),
            cache: false,
            type: "POST",
            data: "do=delete&id="+menugroupId,           
            success: function(serverData){
            	$('#loading').hide();
            }
        });
	}
}

function getMenugroup(page){
	var ajaxData = $('#frmList').serialize()+"&do=list&page="+page;
	$('#loading').show();
	$.ajax({
        url: $("#menugroupUrl").val(),
        cache: false,
        type: "POST",
        data: ajaxData,           
        success: function(serverData){
        	$('#loading').hide();
        	$('#menugroupArea').html(serverData);
        }
      });
}
function buildNavigator(page,currentForm){
	getMenugroup(page);
}
