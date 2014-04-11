
		$(document).ready(function(){

			$( '#infinite_scroll' ).scrollLoad({

				url : 'http://localhost:12345/index/maybanhang/my_scroll_ajax_file.php',

				getData : function() {
					//you can post some data along with ajax request
					
				},

				start : function() {
					$('<div class="loading"><img src="img/ajax-loader.gif"/></div>').appendTo(this);
				},

				ScrollAfterHeight : 95,			//this is the height in percentage

				onload : function( data ) {
					$(this).append( data );
					$('.loading').remove();
				},

				continueWhile : function( resp ) {
					// 2: chay 2 lan 
					if( $(this).children('li').length >=2) 
					{
						return false;
					}
					return true;
				}
			});

		});

