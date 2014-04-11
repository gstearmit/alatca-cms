function validateData(){
	if($('#module_id').val()==''){
		alert('Vui lòng chọn module');
		$('#module_id').focus();
		return false;
	}else if($('#action_name').val()==''){
		alert('Vui lòng nhập chức năng');
		$('#action_name').focus();
		return false;
	}else if($('#action_name_display').val()==''){
		alert('Vui lòng nhập chức năng hiển thị');
		$('#action_name_display').focus();
		return false;
	}else{
		$('#frmUpdate').submit();
	}
	return false;		
}
