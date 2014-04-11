function validateData(){
	if($('#group_id').val()==''){
		alert('Vui lòng chọn nhóm menu');
		$('#group_id').focus();
		return false;
	}
	
	if($('#menu_url').val()==''){
		alert('Vui lòng nhập menu url');
		$('#menu_url').focus();
		return false;
	}
	
	if($('#menu_name').val()==''){
		alert('Vui lòng nhập tên menu');
		$('#menu_name').focus();
		return false;
	}
	
	if($('#menu_order').val()==''){
		alert('Vui lòng chọn vị trí xuất hiện của menu');
		$('#menu_order').focus();
		return false;
	}else{
		$('#frmUpdate').submit();
	}
	return false;		
}
