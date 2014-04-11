function validateData(){
	if($('#note_key').val()==''){
		alert('Vui lòng nhập note key');
		$('#note_key').focus();
		return false;
	}else if($('#note_value').val()==''){
		alert('Vui lòng nhập note value');
		$('#note_value').focus();
		return false;
	}else{
		$('#frmUpdate').submit();
	}
	return false;		
}
