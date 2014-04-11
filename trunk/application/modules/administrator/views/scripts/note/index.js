$(document).ready(function(){
	getNote(1);
	$('#search').click(function(){
		getNote(1);
    });
    $('#reset').click(function(){
    	$('#keyword').val('');
    	getNote(1);
    });
	$('#keyword').keypress(function(event){
        var keycode = (event.keyCode ? event.keyCode : event.which);
        if(keycode == '13'){
        	getNote(1); return false;
        }
 });
}); 

function deleteNote(noteId){
	if(confirm('Bạn có chắc chắn muốn xóa dữ liệu này không? Dữ liệu sẽ bị xóa và không thể phục hồi!')){
		$('#loading').show();
		$('#'+noteId).hide("slow");
		$.ajax({
            url: $("#noteUrl").val(),
            cache: false,
            type: "POST",
            data: "do=delete&id="+noteId,           
            success: function(serverData){
            	$('#loading').hide();
            }
        });
	}
}

function getNote(page){
	var ajaxData = $('#frmList').serialize()+"&do=list&page="+page;
	$('#loading').show();
	$.ajax({
        url: $("#noteUrl").val(),
        cache: false,
        type: "POST",
        data: ajaxData,           
        success: function(serverData){
        	$('#loading').hide();
        	$('#noteArea').html(serverData);
        }
      });
}
function buildNavigator(page,currentForm){
	getNote(page);
}
