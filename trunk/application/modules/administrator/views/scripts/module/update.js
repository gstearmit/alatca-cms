function validateData(){
	if($('#module_name').val()==''){
		alert('Vui lòng nhập tên module');
		$('#module_name').focus();
		return false;
	}else{
		$('#frmUpdate').submit();
	}
	return false;		
}
