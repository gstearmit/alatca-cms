$(document).ready(function(){
	getPackageitem(1);
	$('#search').click(function(){
		getPackageitem(1);
    });
    $('#reset').click(function(){
    	$('#keyword').val('');
    	getPackageitem(1);
    });
	$('#keyword').keypress(function(event){
        var keycode = (event.keyCode ? event.keyCode : event.which);
        if(keycode == '13'){
        	getPackageitem(1); return false;
        }
 });
}); 

function deletePackageitem(packageitemId){
	if(confirm('Are you sure want to delete this data. The data cannot recovery!')){
		$('#loading').show();
		$('#'+packageitemId).hide("slow");
		$.ajax({
            url: $("#packageitemUrl").val(),
            cache: false,
            type: "POST",
            data: "do=delete&id="+packageitemId,           
            success: function(serverData){
            	$('#loading').hide();
            }
        });
	}
}

function getPackageitem(page){
	var ajaxData = $('#frmList').serialize()+"&do=list&page="+page;
	$('#loading').show();
	$.ajax({
        url: $("#packageitemUrl").val(),
        cache: false,
        type: "POST",
        data: ajaxData,           
        success: function(serverData){
        	$('#loading').hide();
        	$('#packageitemArea').html(serverData);
        }
      });
}
function buildNavigator(page,currentForm){
	getPackageitem(page);
}
