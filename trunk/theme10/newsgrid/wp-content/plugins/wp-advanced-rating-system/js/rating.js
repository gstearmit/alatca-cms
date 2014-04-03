/*---------------------------------------------*\
|   Project: Wordpress Advanced Rating System   |
|     This code were developed by: Mr. Kun 		|
|         Homepage: http://kuncode.com		    |
|      _ +-----------------------------+ _	    |
|     /o)|        Coder: Mr. Kun       |(o\	    |
|    / / |   Web: http://kuncode.com   | \ \	|
|   ( (_ |   Blog: http://veerkun.com  | _) )   |
|  ((\ \)+-/o)---------------------(o\-+(/ /))  |
|  (\\\ \_/ /                       \ \_/ ///)  |
|   \      /                         \      /   |
|    \____/                           \____/    |
\*---------------------------------------------*/

var rating_path = wpars.url;
var http = createRequestObject();
var field = '';
//var IMG_NONE  = rating_path + 'images/' + wpars.default_shape + '/' + wpars.default_color + '/none.png';
//var IMG_HOVER = rating_path + 'images/' + wpars.default_shape + '/' + wpars.default_color + '/hover.png';

var IMG_NONE = new Image();var IMG_HOVER = new Image(); 
IMG_NONE.src = rating_path + 'images/' + wpars.default_shape + '/' + wpars.default_color + '/none.png';
IMG_HOVER.src = rating_path + 'images/' + wpars.default_shape + '/' + wpars.default_color + '/hover.png';
var IMG_LOADING = rating_path + 'img/loading.gif';
var loadingText = '<img src="'+ IMG_LOADING +'">  Loading...';
function createRequestObject() {
	var xmlhttp;
	try { xmlhttp=new ActiveXObject("Msxml2.XMLHTTP"); }
	catch(e) {
    try { xmlhttp=new ActiveXObject("Microsoft.XMLHTTP");}
	catch(f) { xmlhttp=null; }
  }
  if(!xmlhttp&&typeof XMLHttpRequest!="undefined") {
	xmlhttp=new XMLHttpRequest();
  }
	return  xmlhttp;
}

function handleResponse() {
	try {
		if((http.readyState == 4)&&(http.status == 200)){
			response = http.responseText;
			field.innerHTML = response;
			field.scrollIntoView();
			if(!response) window.location.href = url;
		}
  	}
	catch(e){}
	finally{}
}

function rating(id,scores,custom) {
	try {
		document.getElementById('wpars_rating_' + id).innerHTML = loadingText;
		http.open('POST',  rating_path + 'includes/wpars_rating_process.php');
		http.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
		http.onreadystatechange = function() {
			if((http.readyState == 4)&&(http.status == 200)){
				document.getElementById('wpars_rating_' + id).innerHTML = http.responseText;
			}
		}
		http.send('wpars_rating=1&id='+id+'&scores='+scores+'&custom='+custom);
	}
	catch(e){}
	finally{}
	return false;
}
function default_show_star(starNum,id,max_stars) {
	default_arr_img = new Array();
	for (var i=1; i <= max_stars; i++) {
		if(document.getElementById('wpars_shapes_'+ id + '_' + i)) { default_arr_img[i] = document.getElementById('wpars_shapes_'+ id + '_' + i).src;}
	}
	//alert(img_hover);
	// remove star
	for (var i=1; i <= max_stars; i++) {
		document.getElementById('wpars_shapes_'+ id + '_' + i).src = IMG_NONE.src;
		}
	for (var j=1; j <= starNum; j++) {
		document.getElementById('wpars_shapes_'+ id + '_' + j).src = IMG_HOVER.src;
	}
	//return arr_img;
}
function default_restore_star(starNum,id,max_stars) {
	for (var i=1; i <= max_stars; i++) {
		if(document.getElementById('wpars_shapes_'+ id + '_' + i)) { document.getElementById('wpars_shapes_'+ id + '_' + i).src = default_arr_img[i];}
	}
}

function custom_show_star(starNum,id,shape,color,max_stars) {
	custom_arr_img = new Array();
	for (var i=1; i <= max_stars; i++) {
		if(document.getElementById('wpars_shapes_'+ id + '_' + i)) { custom_arr_img[i] = document.getElementById('wpars_shapes_'+ id + '_' + i).src;}
	}
	var img_none = rating_path + 'images/' + shape + '/' + color + '/none.png';
	var img_hover = rating_path + 'images/' + shape + '/' + color + '/hover.png';
	//alert(img_hover);
		 // remove star
		for (var i=1; i <= max_stars; i++) {
		document.getElementById('wpars_shapes_'+ id + '_' + i).src = img_none;
		} 
	for (var j=1; j <= starNum; j++) {
		document.getElementById('wpars_shapes_'+ id + '_' + j).src = img_hover;
	}
	//return arr_img;
}
function custom_restore_star(starNum,id,max_stars) {
	for (var i=1; i <= max_stars; i++) {
		if(document.getElementById('wpars_shapes_'+ id + '_' + i)) { document.getElementById('wpars_shapes_'+ id + '_' + i).src = custom_arr_img[i];}
	}
}