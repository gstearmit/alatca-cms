$(document).ready(function(){
	getModule(1);
	$('#search').click(function(){
		getModule(1);
    });
    $('#reset').click(function(){
    	$('#keyword').val('');
    	getModule(1);
    });
	$('#keyword').keypress(function(event){
        var keycode = (event.keyCode ? event.keyCode : event.which);
        if(keycode == '13'){
        	getModule(1); return false;
        }
 });
}); 

function deleteModule(moduleId){
	if(confirm('Bạn có chắc chắn muốn xóa dữ liệu này không? Dữ liệu sẽ bị xóa và không thể phục hồi!')){
		$('#loading').show();
		$('#'+moduleId).hide("slow");
		$.ajax({
            url: $("#moduleUrl").val(),
            cache: false,
            type: "POST",
            data: "do=delete&id="+moduleId,           
            success: function(serverData){
            	$('#loading').hide();
            }
        });
	}
}

function getModule(page){
	var ajaxData = $('#frmList').serialize()+"&do=list&page="+page;
	$('#loading').show();
	$.ajax({
        url: $("#moduleUrl").val(),
        cache: false,
        type: "POST",
        data: ajaxData,           
        success: function(serverData){
        	$('#loading').hide();
        	$('#moduleArea').html(serverData);
        }
      });
}
function buildNavigator(page,currentForm){
	getModule(page);
}
