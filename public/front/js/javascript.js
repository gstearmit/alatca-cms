(function($) 
{

	$.fn.scrollPagination = function(options) 
	{
		
		var settings = 
		{ 
			nop     : 3, 
			offset  : 0, 
			error   : 'No More Posts!', 
			                            
			delay   : 500, 
			               
			scroll  : true 
			               
		}
		
		if(options) 
		{
			$.extend(settings, options);
		}
		
		return this.each(function() 
		{

			$this = $(this);
			$settings = settings;
			var offset = $settings.offset;
			var busy = false; 
			
			if($settings.scroll == true) $initmessage = 'Scroll for more or click here';
			else $initmessage = 'Click for more';
			$this.append('<div class="content"></div><div class="loading-bar">'+$initmessage+'</div>');
			
			function getData() 
			{
				//ajax2.php
				//ajax.php
				//ajax3.php
				$.post('http://192.168.1.38:12345/index/maybanhang/ajax3.php', {
						
					action        : 'scrollpagination',
				    number        : $settings.nop,
				    offset        : offset,
					    
				}, function(data) {
						
					$this.find('.loading-bar').html($initmessage);
					if(data == "") { 
						$this.find('.loading-bar').html($settings.error);	
					}
					else {
					    offset = offset+$settings.nop; 
						// gui du liue ra ngoai trang chu
					   	$this.find('.content').append(data);
						busy = false;
					}	
						
				});
					
			}	
			
			getData();

			if($settings.scroll == true) 
			{
				
				$(window).scroll(function() {
					
					
					if($(window).scrollTop() + $(window).height() > $this.height() && !busy) 
					{
						busy = true;
						$this.find('.loading-bar').html('Loading Posts');
						setTimeout(function() {
							getData();
						}, $settings.delay);
					}	
				});
			}
			
			$this.find('.loading-bar').click(function() 
			{
			
				if(busy == false) 
				{
					busy = true;
					getData();
				}
			
			});
			
		});
	}

})(jQuery);
