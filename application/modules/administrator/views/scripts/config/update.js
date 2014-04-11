function validateData(){
	if($('#config_key').val()==''){
		alert('Vui lòng nhập config key');
		$('#config_key').focus();
		return false;
	}else if($('#config_value').val()==''){
		alert('Vui lòng nhập config value');
		$('#config_value').focus();
		return false;
	}else{
		$('#frmUpdate').submit();
	}
	return false;		
}
