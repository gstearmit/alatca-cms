function validateData(){
	if($('#newsgroup_key').val()==''){
		alert('Vui lòng nhập newsgroup key');
		$('#newsgroup_key').focus();
		return false;
	}else if($('#newsgroup_value').val()==''){
		alert('Vui lòng nhập newsgroup value');
		$('#newsgroup_value').focus();
		return false;
	}else{
		$('#frmUpdate').submit();
	}
	return false;		
}
