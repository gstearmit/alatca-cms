function validateData(){
	if($('#group_name').val()==''){
		alert('Vui lòng nhập tên nhóm menu');
		$('#group_name').focus();
		return false;
	}
	
	if($('#group_order').val()==''){
		alert('Vui lòng nhập vị trí sắp xếp của nhóm');
		$('#group_order').focus();
		return false;
	}else{
		$('#frmUpdate').submit();
	}
	return false;		
}
