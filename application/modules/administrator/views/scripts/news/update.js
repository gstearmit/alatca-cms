function validateData(){
	var totalCate = 0;
	$('#checkboxes input:checked').each(function() {
		totalCate++;
	});
	
	if(totalCate == 0){
		alert('Bạn phải chọn ít nhất một chuyên mục');
		return false;
	}
	
	if($('#title_vn').val()==''){
		alert('Vui lòng nhập tiêu đề tin');
		$('#title_vn').focus();
		return false;
	}
	
	if($('#desc_vn').val()==''){
		alert('Vui lòng nhập trích dẫn ngắn cho tin');
		$('#desc_vn').focus();
		return false;
	}else{
		$('#frmUpdate').submit();
	}
	return false;		
}
