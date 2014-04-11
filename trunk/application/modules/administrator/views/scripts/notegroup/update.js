function validateData(){
	if($('#group_name').val()==''){
		alert('Vui lòng nhập tên nhóm');
		$('#group_name').focus();
		return false;
	}else if($('#group_order').val()==''){
		alert('Vui lòng nhập độ ưu tiên');
		$('#group_order').focus();
		return false;
	}else{
		$('#frmUpdate').submit();
	}
	return false;		
}
