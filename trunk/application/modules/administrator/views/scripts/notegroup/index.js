$(document).ready(function(){
	getNotegroup(1);
	$('#search').click(function(){
		getNotegroup(1);
    });
    $('#reset').click(function(){
    	$('#keyword').val('');
    	getNotegroup(1);
    });
	$('#keyword').keypress(function(event){
        var keycode = (event.keyCode ? event.keyCode : event.which);
        if(keycode == '13'){
        	getNotegroup(1); return false;
        }
 });
}); 

function deleteNotegroup(notegroupId){
	if(confirm('Bạn có chắc chắn muốn xóa dữ liệu này không? Dữ liệu sẽ bị xóa và không thể phục hồi!')){
		$('#loading').show();
		$('#'+notegroupId).hide("slow");
		$.ajax({
            url: $("#notegroupUrl").val(),
            cache: false,
            type: "POST",
            data: "do=delete&id="+notegroupId,           
            success: function(serverData){
            	$('#loading').hide();
            }
        });
	}
}

function getNotegroup(page){
	var ajaxData = $('#frmList').serialize()+"&do=list&page="+page;
	$('#loading').show();
	$.ajax({
        url: $("#notegroupUrl").val(),
        cache: false,
        type: "POST",
        data: ajaxData,           
        success: function(serverData){
        	$('#loading').hide();
        	$('#notegroupArea').html(serverData);
        }
      });
}
function buildNavigator(page,currentForm){
	getNotegroup(page);
}
