$(document).ready(function(){
	$('#btnSetRole').click(function(){
		$('#frmUpdate').submit();
	});
}); 

function validateData(){
	if($('#user_name').val()==''){
		alert('Vui lòng nhập username');
		$('#user_name').focus();
		return false;
	}else{
		var ajaxData = "do=checkUsername&user_name="+$('#user_name').val();
		if($('#id')>0){
			ajaxData = ajaxData+"&id="+$('#id').val();
		}else{
			if($('#pass').val()==''){
    			alert('Vui lòng nhập password');
    			$('#pass').focus();
    			return false;
    		}
		}
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
