$(document).ready(function(){
	getRole(1);
	$('#search').click(function(){
		getRole(1);
    });
    $('#reset').click(function(){
    	$('#keyword').val('');
    	getRole(1);
    });
	$('#keyword').keypress(function(event){
        var keycode = (event.keyCode ? event.keyCode : event.which);
        if(keycode == '13'){
        	getRole(1); return false;
        }
 });
}); 

function deleteRole(roleId){
	if(confirm('Bạn có chắc chắn muốn xóa role này không. Khi bạn xóa, tất cả admin thuộc role này sẽ không thể truy cập!')){
		if(confirm('Dữ liệu sẽ không thể phục hồi, và ảnh hưởng tới nhiều người. Bạn chắc chắn muốn xóa chứ?')){
			$('#loading').show();
			$('#'+roleId).hide("slow");
			$.ajax({
	            url: $("#roleUrl").val(),
	            cache: false,
	            type: "POST",
	            data: "do=delete&id="+roleId,           
	            success: function(serverData){
	            	$('#loading').hide();
	            }
	        });
		}
	}
}

function getRole(page){
	var ajaxData = $('#frmList').serialize()+"&do=list&page="+page;
	$('#loading').show();
	$.ajax({
        url: $("#roleUrl").val(),
        cache: false,
        type: "POST",
        data: ajaxData,           
        success: function(serverData){
        	$('#loading').hide();
        	$('#roleArea').html(serverData);
        }
      });
}
function buildNavigator(page,currentForm){
	getRole(page);
}
