function validateData(){
	if($('#role_name').val()==''){
		alert('Vui lòng nhập tên nhóm quản trị viên');
		$('#role_name').focus();
		return false;
	}else{
		$('#frmUpdate').submit();
	}
	return false;		
}
