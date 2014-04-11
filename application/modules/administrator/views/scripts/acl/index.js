$(document).ready(function(){
	$('#btnSave').click(function(){
		$('#frmList').submit();
	});
	
	$('#btnCheckall').click(function(){
		if($(this).hasClass("checkall")){
			$('input:checkbox').attr('checked','checked');
			$(this).addClass("uncheck").removeClass('checkall').html("Bỏ chọn");
		}else{
			$('input:checkbox').removeAttr('checked');
			$(this).addClass("checkall").removeClass('uncheck').html("Chọn hết");
		}
	}); 
});