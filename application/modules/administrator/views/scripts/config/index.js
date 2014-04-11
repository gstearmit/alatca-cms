$(document).ready(function(){
	getConfig(1);
	$('#search').click(function(){
		getConfig(1);
    });
    $('#reset').click(function(){
    	$('#keyword').val('');
    	getConfig(1);
    });
	$('#keyword').keypress(function(event){
        var keycode = (event.keyCode ? event.keyCode : event.which);
        if(keycode == '13'){
        	getConfig(1); return false;
        }
 });
}); 

function deleteConfig(configId){
	if(confirm('Bạn có chắc chắn muốn xóa dữ liệu này không? Dữ liệu sẽ bị xóa và không thể phục hồi!')){
		$('#loading').show();
		$('#'+configId).hide("slow");
		$.ajax({
            url: $("#configUrl").val(),
            cache: false,
            type: "POST",
            data: "do=delete&id="+configId,           
            success: function(serverData){
            	$('#loading').hide();
            	var mes = "<div class='alert alert-success alert-dismissable'>" +
    			"<a href='#' data-dismiss='alert' class='close'>×" +
                "</a><i class='icon-ok-sign'></i>"+
                 "  <strong> Xoá </strong> Thành công"+
                "</div>";
            	$('.box_message').html(mes);
            }
        });
	}
}

function getConfig(page){
	var ajaxData = $('#frmList').serialize()+"&do=list&page="+page;
	$('#loading').show();
	$.ajax({
        url: $("#configUrl").val(),
        cache: false,
        type: "POST",
        data: ajaxData,           
        success: function(serverData){
        	$('#loading').hide();
        	$('#configArea').html(serverData);
        }
      });
}
function buildNavigator(page,currentForm){
	getConfig(page);
}
