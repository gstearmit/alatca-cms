$(document).ready(function(){
	getUser(1);
	$('#search').click(function(){
		getUser(1);
    });
    $('#reset').click(function(){
    	$('#keyword').val('');
    	getUser(1);
    });
	$('#keyword').keypress(function(event){
        var keycode = (event.keyCode ? event.keyCode : event.which);
        if(keycode == '13'){
        	getUser(1); return false;
        }
 });

}); 

function setActive(userid,active){
	$('#loading').show();
	var _this = $('#icon_'+userid);
	if(_this.hasClass("icon_on")){
		_this.removeClass("icon_on").addClass("icon_off");
	}else{
		_this.removeClass("icon_off").addClass("icon_on");
	}
	$.ajax({
        url: $("#userUrl").val(),
        cache: false,
        type: "POST",
        data: "do=setActive&userid="+userid+"&active="+active,           
        success: function(serverData){
        	$('#loading').hide();
        }
    });
}

function deleteUser(userid){
	if(confirm('Bạn có chắc chắn muốn xóa dữ liệu này không? Dữ liệu sẽ bị xóa và không thể phục hồi!')){
		$('#loading').show();
		$('#'+userid).hide("slow");
		$.ajax({
            url: $("#userUrl").val(),
            cache: false,
            type: "POST",
            data: "do=delete&userid="+userid,           
            success: function(serverData){
            	$('#loading').hide();
            	var mes = "<div class='alert alert-success alert-dismissable'>" +
    			"<a href='#' data-dismiss='alert' class='close'>×" +
                "</a><i class='icon-ok-sign'></i>"+
                 "  <strong> Xoá </strong> Thành công"+
                "</div>";
            	$('.box_message').html(mes);
            	
            }
        });
	}
}

function getUser(page){
	var ajaxData = $('#frmList').serialize()+"&do=list&page="+page;
	$('#loading').show();
	$.ajax({
        url: $("#userUrl").val(),
        cache: false,
        type: "POST",
        data: ajaxData,           
        success: function(serverData){
        	$('#loading').hide();
        	$('#userArea').html(serverData);
        }
      });
}

function buildNavigator(page,currentForm){
	getUser(page);
}