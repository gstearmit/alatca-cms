jQuery('.ishare_wpress_popup_btn').live('click', function(event) {
	event.preventDefault();
  	var url = jQuery(this).attr('href');
  	var width = '800';
  	var height = '500';
	window.open(url, "", "scrollbars=yes,menubar=no,toolbar=no,resizable=yes,width="
    + width + ",height=" + height + ",left=" +
	((screen.width - 760)/2) + ",top=" + ((screen.height - 450)/2) );
});

jQuery('.ishare_wpress_inline_popup_btn').live('click', function(event) {
	event.preventDefault();
  	
	var url=jQuery(this).attr('href');
	var title=jQuery(this).attr('name');
	var img=jQuery('#ishare_img').html();
	
	if(url=='') url=ishare_wpress.shareurl;
	if(title=='') url=ishare_wpress.title;
	
	var icon = jQuery(this).attr('title').toLowerCase();
	var u='';
	
	var h = new Object();
	h.digg = "http://digg.com/submit?phase=2&url={URL}&title={TITLE}";
	h.linkedin = "http://www.linkedin.com/shareArticle?mini=true&url={URL}&title={TITLE}&summary={DESCRIPTION}&source=";
	h.technorati = "http://www.technorati.com/faves?add={URL}";
	h.delicious = "http://del.icio.us/post?url={URL}&title={TITLE}";
	h.yahoo = "http://myweb2.search.yahoo.com/myresults/bookmarklet?u={URL}&t={TITLE}";
	h.google = "http://www.google.com/bookmarks/mark?op=edit&bkmk={URL}&title={TITLE}";
	h.newsvine = "http://www.newsvine.com/_wine/save?u={URL}&h={TITLE}";
	h.reddit = "http://reddit.com/submit?url={URL}&title={TITLE}";
	h.live = "https://favorites.live.com/quickadd.aspx?marklet=1&mkt=en-us&url={URL}&title={TITLE}&top=1";
	h.facebook = "http://www.facebook.com/share.php?u={URL}";
	h.twitter = "http://twitter.com/?status={TITLE} - {URL}";
	h.pinterest = "http://pinterest.com/pin/create/button/?url={URL}&media={IMG}";
	h.stumbleupon = "http://www.stumbleupon.com/submit?url={URL}&title={TITLE}";
	h.orkut = "http://promote.orkut.com/preview?nt=orkut.com&tt={TITLE}&du={URL}&cn={DESCRIPTION}";
	h.bebo = "http://www.bebo.com/c/share?Url={URL}&title={TITLE}";
	h.evernote = "http://s.evernote.com/grclip?url={URL}&title={TITLE}";
	h.mixx = "http://www.mixx.com/submit?page_url={URL}&title={TITLE}";
	h.myspace = "http://www.myspace.com/Modules/PostTo/Pages/?u={URL}&title={TITLE}";
	h.netvibes = "http://www.netvibes.com/share?title={TITLE}&url={URL}";
	h.tumblr = "http://www.tumblr.com/share?v=3&u={URL}&t={TITLE}&s=";
	h.google_buzz = "http://www.google.com/reader/link?url={URL}&title={TITLE}&srcURL={URL}";
	h.friendfeed = "http://friendfeed.com/share/bookmarklet/frame#title={TITLE}&url={URL}";
	h.design_moo = "http://www.designmoo.com/node/add/drigg/?url={URL}&title={TITLE}";
	h.designfloat = "http://www.designfloat.com/submit.php?url={URL}&title={TITLE}";
	h.design_bump = "http://www.designbump.com/node/add/drigg/?url={URL}&title={TITLE}";
	h.squidoo = "http://www.squidoo.com/lensmaster/bookmark?{URL}";
	h.yahoo_buzz = "http://buzz.yahoo.com/buzz?targetUrl={URL}&headline={TITLE}&summary={DESCRIPTION}";
	h.print = 'javascript:void(0);" onClick="window.print();" target="_self';
	h.favorites = 'javascript:void(0);" onClick="bookmark_us();" target="_self';
	
	u = h[icon];
	
	u = u.replace("{TITLE}", encodeURIComponent(title));
	u = u.replace("{URL}", encodeURIComponent(url));
	u = u.replace("{IMG}", encodeURIComponent(img));
	u = u.replace("{KEYWORDS}", encodeURIComponent(''));
	u = u.replace("{DESCRIPTION}", encodeURIComponent(''));
	
  	var width = '800';
  	var height = '500';
	window.open(u, "", "scrollbars=yes,menubar=no,toolbar=no,resizable=yes,width="
    + width + ",height=" + height + ",left=" +
	((screen.width - 760)/2) + ",top=" + ((screen.height - 450)/2) );
});

jQuery('.ishare').ishare({
	
	displayTitle: ishare_wpress.displayTitle,
	title: ishare_wpress.title,
	width: ishare_wpress.width,
	height: ishare_wpress.height,
	position: ishare_wpress.position,
	activation: ishare_wpress.activation,
	
	icons: ishare_wpress.icons,
	targetBlank: true, // open link in new tab? true or false
	
	sharetitle: ishare_wpress.sharetitle,
	shareurl: ishare_wpress.shareurl,
	
	fbLikebox: ishare_wpress.fbLikebox,
	fbAction: ishare_wpress.fbAction, // like or recommend
	fbcolorscheme: ishare_wpress.fbcolorscheme, // light or dark
	
	tweets: ishare_wpress.tweets,
	tweetVia: ishare_wpress.tweetVia, // via twitter account
	twitterRelated: ishare_wpress.twitterRelated, // related twitter account
	
	plusone: ishare_wpress.plusone,
	
	iconsDir: ishare_wpress.icons_dir+'social/16/'
});

function display_inline_sharing_icons() {
	var img = ishare_wpress.icons_dir+'ajax-loader.gif';
	var url='';
	var title='';
	var img='';
	
	jQuery('.ishare_inline_icons_display').each(function (i, element) {
		url = jQuery(element).attr('href');
		title = jQuery(element).attr('title');
		img = jQuery(element).attr('data-img');
		jQuery(element).html('<img src="'+img+'">');
		jQuery.ajax({
			type: 'POST',
			url: ishare_wpress.ajaxurl,
			data: 'action=ishare_wpress_listener&method=display_inline_sharing_icons&url='+url+'&title='+title+'&img='+img,
			success: function(msg) {
				jQuery(element).html(msg);
			}
		});
	});
}
