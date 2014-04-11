function validateData(){
	if($('#category_name').val()==''){
		alert('Vui lòng nhập loại tin');
		$('#category_name').focus();
		return false;
	}else{
		$('#frmUpdate').submit();
	}
	return false;		
}
