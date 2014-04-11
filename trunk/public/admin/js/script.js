//  Andy Langton's show/hide/mini-accordion @ http://andylangton.co.uk/jquery-show-hide

// this tells jquery to run the function below once the DOM is ready
$(document).ready(function() {

// choose text for the show/hide link - can contain HTML (e.g. an image)
var showText='Open';
var hideText='Close';

// initialise the visibility check
var is_visible = false;

// append show/hide links to the element directly preceding the element with a class of "toggle"
$('.toggle').prev().append(' <a href="#" class="toggleLink">'+hideText+'</a>');

// hide all of the elements with a class of 'toggle'
$('.toggle').show();

// capture clicks on the toggle links
$('a.toggleLink').click(function() {

// switch visibility
is_visible = !is_visible;

// change the link text depending on whether the element is shown or hidden
if ($(this).text()==showText) {
$(this).text(hideText);
$(this).parent().next('.toggle').slideDown('fast');
}
else {
$(this).text(showText);
$(this).parent().next('.toggle').slideUp('fast');
}

// return false so any link destination is not followed
return false;

});
});




//popup
window.onload = function (){
	var node_a = document.getElementsByTagName('a');
		for (var i in node_a) {
			if(node_a[i].className == 'popup'){
				node_a[i].onclick = function() {
					return winOpen(this.href, this.rel)
				};
			}
		}
} ;

function winOpen(url, rel) {
	var split = rel.split(',') ;
	window.open(
	url,'popup',
	'width='+ split[0] +',height='+ split[1] +',toolbar=no,location=no,status=no,menubar=no,scrollbars=yes,resizable=yes');

	return false;
};


/* ====マウスオーバーエフェクト==== */
$(function(){
$(".onM").hover(
	function(){
		$(this).fadeTo(300,0.5);
	},
	function(){
		$(this).fadeTo(300,1);
	}
);
$(".btn_show").hover(
	function(){
		$(this).fadeTo(300,0.5);
	},
	function(){
		$(this).fadeTo(300,1);
	}
);
});