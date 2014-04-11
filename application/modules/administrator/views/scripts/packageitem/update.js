function validateData(){
	if($('#package_id').val()==''){
		alert('Please select package name');
		$('#package_id').focus();
		return false;
	}else if($('#item_name').val()==''){
		alert('Please enter package feature');
		$('#item_name').focus();
		return false;
	}else if($('#item_order').val()==''){
		alert('Please enter feature order');
		$('#item_order').focus();
		return false;
	}else{
		$('#frmUpdate').submit();
	}
	return false;		
}
