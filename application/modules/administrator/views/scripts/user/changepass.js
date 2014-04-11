





$(document).ready(function(){
	$('#btnSetRole').click(function(){
		$('#frmUpdate').submit();
	});
}); 

function validateData(){
	var pass=$('#password').val();
	var new_pass=$('#newpassword').val();
	var renew_pass=$('#renewpassword').val();
	if(pass==''){
		alert('Please enter password !');
		$('#password').focus();
		return false;
	}
	if(new_pass==''){
		alert('Please enter new password !');
		$('#newpassword').focus();
		return false;
	}
	if(renew_pass != new_pass){
		alert('Require repassword !');
		$('#renewpassword').focus();
		return false;
	}
	else{
		$('#frmUpdate').submit();
		return;
		$('#loading').show();
		$.ajax({
	        url: $("#userUrl").val(),
	        cache: false,
	        type: "POST",
	        data: ajaxData,           
	        success: function(totalUser){
	        	
	        	if(totalUser >0){
	        		alert('Username đã tồn tại, vui lòng chọn username khác!');
	        		return false;
	        	}else{
	        		if($('#email').val()==''){
	        			alert('Vui lòng nhập email');
	        			$('#email').focus();
	        			return false;
	        		}else{
	        			$('#frmUpdate').submit();
	        		}
	        	}
	        }
	      });
	}
}
