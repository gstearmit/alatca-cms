$(document).ready(function(){
	getNewsgroup(1);
	$('#search').click(function(){
		getNewsgroup(1);
    });
    $('#reset').click(function(){
    	$('#keyword').val('');
    	getNewsgroup(1);
    });
	$('#keyword').keypress(function(event){
        var keycode = (event.keyCode ? event.keyCode : event.which);
        if(keycode == '13'){
        	getNewsgroup(1); return false;
        }
 });
}); 

function deleteNewsgroup(newsgroupId){
	if(confirm('Bạn có chắc chắn muốn xóa dữ liệu này không? Dữ liệu sẽ bị xóa và không thể phục hồi!')){
		$('#loading').show();
		$('#'+newsgroupId).hide("slow");
		$.ajax({
            url: $("#newsgroupUrl").val(),
            cache: false,
            type: "POST",
            data: "do=delete&id="+newsgroupId,           
            success: function(serverData){
            	$('#loading').hide();
            }
        });
	}
}

function getNewsgroup(page){
	var ajaxData = $('#frmList').serialize()+"&do=list&page="+page;
	$('#loading').show();
	$.ajax({
        url: $("#newsgroupUrl").val(),
        cache: false,
        type: "POST",
        data: ajaxData,           
        success: function(serverData){
        	$('#loading').hide();
        	$('#newsgroupArea').html(serverData);
        }
      });
}
function buildNavigator(page,currentForm){
	getNewsgroup(page);
}
