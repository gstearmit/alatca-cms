$(document).ready(function(){
	getNewscategory(1);
	$('#search').click(function(){
		getNewscategory(1);
    });
    $('#reset').click(function(){
    	$('#keyword').val('');
    	getNewscategory(1);
    });
	$('#keyword').keypress(function(event){
        var keycode = (event.keyCode ? event.keyCode : event.which);
        if(keycode == '13'){
        	getNewscategory(1); return false;
        }
 });
}); 

function deleteNewscategory(newscategoryId){
	if(confirm('Bạn có chắc chắn muốn xóa dữ liệu này không? Dữ liệu sẽ bị xóa và không thể phục hồi!')){
		$('#loading').show();
		$('#'+newscategoryId).hide("slow");
		$.ajax({
            url: $("#newscategoryUrl").val(),
            cache: false,
            type: "POST",
            data: "do=delete&id="+newscategoryId,           
            success: function(serverData){
            	$('#loading').hide();
            }
        });
	}
}

function getNewscategory(page){
	var ajaxData = $('#frmList').serialize()+"&do=list&page="+page;
	$('#loading').show();
	$.ajax({
        url: $("#newscategoryUrl").val(),
        cache: false,
        type: "POST",
        data: ajaxData,           
        success: function(serverData){
        	$('#loading').hide();
        	$('#newscategoryArea').html(serverData);
        }
      });
}
function buildNavigator(page,currentForm){
	getNewscategory(page);
}
