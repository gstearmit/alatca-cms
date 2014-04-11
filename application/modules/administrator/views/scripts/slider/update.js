function validateData(){
	if($('#sil_key').val()==''){
		alert('Vui lòng nhập Slider key');
		$('#sil_key').focus();
		return false;
	}else if($('#sil_title').val()==''){
		alert('Vui lòng nhập Slider title');
		$('#sil_title').focus();
		return false;
	}else{
		$('#frmUpdate').submit();
	}
	return false;		
}
