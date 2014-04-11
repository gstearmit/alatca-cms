$(document).ready(function(){
	getNews(1);
	$('#search').click(function(){
		getNews(1);
    });
    $('#reset').click(function(){
    	$('#keyword').val('');
    	getNews(1);
    });
	$('#keyword').keypress(function(event){
        var keycode = (event.keyCode ? event.keyCode : event.which);
        if(keycode == '13'){
        	getNews(1); return false;
        }
 });
}); 

function deleteNews(newsId){
	if(confirm('Bạn có chắc chắn muốn xóa dữ liệu này không? Dữ liệu sẽ bị xóa và không thể phục hồi!')){
		$('#loading').show();
		$('#'+newsId).hide("slow");
		$.ajax({
            url: $("#newsUrl").val(),
            cache: false,
            type: "POST",
            data: "do=delete&id="+newsId,           
            success: function(serverData){
            	$('#loading').hide();
            }
        });
	}
}

function getNews(page){
	var ajaxData = $('#frmList').serialize()+"&do=list&page="+page;
	$('#loading').show();
	$.ajax({
        url: $("#newsUrl").val(),
        cache: false,
        type: "POST",
        data: ajaxData,           
        success: function(serverData){
        	$('#loading').hide();
        	var arr = serverData.split(':::');
        	$('#newsArea').html(arr[0]);
        	$('#searchResult').html(arr[1]);
        }
      });
}
function buildNavigator(page,currentForm){
	getNews(page);
}
