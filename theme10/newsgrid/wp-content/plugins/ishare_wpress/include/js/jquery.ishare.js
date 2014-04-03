(function (jQuery) {
	jQuery.fn.ishare = function (e) {
		var f = {
			activation: "hover",
			keepAlive: false,
			width: "400px",
			height: "115px",
			edgeOffset: 10,
			position: "top",
			delay: 40,
			fadeIn: 40,
			fadeOut: 0, //600
			fadeOut_delay: 100, //500
			iconsDir: "",
			displayTitle: true,
			title: "Share &amp; Bookmark",
			sharetitle: "",
			shareurl: "",
			icons: "facebook,twitter,google,digg,delicious,stumbleupon,myspace,tumblr,linkedin,bebo,mixx,friendfeed",
			targetBlank: true,
			fbLikebox: true,
			fbAction: "like",
			fbcolorscheme: "light",
			tweets: true,
			tweetVia: "",
			twitterRelated: "yougapi",
			tweetButton: "Tweet",
			plusone: true,
			pinterest: true,
			enter: function () {},
			exit: function () {}
		};
		var g = jQuery.extend(f, e);
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
		if (g.activation == 'hover') {
			this.click(function () {
				return false
			})
		}
		var j = jQuery('<div id="ishare_holder" style="width:' + g.width + ';"></div>');
		j.css({
			position: 'absolute',
			zIndex: 100000
		});
		var k = '';
		if (g.displayTitle) {
			var k = jQuery('<div id="ishare_title">' + g.title + '<span id="ishare_close"><a href="javascript:void(0);" class="ishare_close">X</a></span></div>')
		}
		var l = jQuery('<div id="ishare_icons" style="height:' + g.height + ';"></div>');
		var m = '';
		if (g.fbLikebox) {
			var m = '<iframe src="http://www.facebook.com/plugins/like.php?href=' + encodeURL(sharing_url()) + '&amp;layout=button_count&amp;width=100&amp;action=' + g.fbAction + '&amp;colorscheme=' + g.fbcolorscheme + '&amp;height=21" scrolling="no" frameborder="0" style="border:none; overflow:hidden; width:100px; height:21px;" allowTransparency="true"></iframe>'
		}
		if (g.tweetVia) {
			var n = '&amp;via=' + g.tweetVia
		} else {
			var n = ''
		}
		var o = '';
		if (g.tweets) {
			var o = '<iframe allowtransparency="true" frameborder="0" scrolling="no" src="http://platform.twitter.com/widgets/tweet_button.html?url=' + encodeURL(sharing_url()) + '&amp;text=' + encodeURL(g.sharetitle) + '&amp;related=' + encodeURL(g.twitterRelated) + n + '" style="width:120px; height:21px;"></iframe>'
		}
		
		var gp = '';
		if (g.plusone) {
			var gp = '<div id="plusone-box"></div>'
		}
		
		var pinterest = '';
		if (g.pinterest) {
			var pinterest = '<a href="//pinterest.com/pin/create/button/?url='+sharing_url()+'&media='+ishare_wpress.page_image+'&description=" data-pin-do="buttonPin" data-pin-config="beside" target="_blank"><img src="//assets.pinterest.com/images/pidgets/pin_it_button.png" /></a>'
		}
		
		var p = '';
		if (g.fbLikebox || g.tweets || g.plusone) {
			var p = jQuery('<div id="ishare_extras">' + gp + o + m + pinterest + '</div>')
		}
		j.html(k);
		j.append(l, p);
		var q = g.icons.split(",");
		for (var r in q) {
			var s = q[r];
			var t = s.replace(/_/gi, " ");
			var u = h[s];
			if (u) {
				u = u.replace("{TITLE}", encodeURL(sharing_title()));
				u = u.replace("{URL}", encodeURL(sharing_url()));
				u = u.replace("{IMG}", encodeURL(sharing_image()));
				u = u.replace("{KEYWORDS}", encodeURL(ishare_metakeywords()));
				u = u.replace("{DESCRIPTION}", encodeURL(ishare_metadescription()));
				if(t=='print') var v = '<a href="' + u + '" onClick="window.print();" target="_self"><div class="ishare_icon"><img src="' + g.iconsDir + t + '.png"><span>' + t + '</span></div></a>';
				else var v = '<a href="' + u + '" class="ishare_wpress_popup_btn"><div class="ishare_icon"><img src="' + g.iconsDir + t + '.png"><span>' + t + '</span></div></a>';
				jQuery(v).appendTo(l)
			}
		}
		return this.each(function () {
			if (g.activation == 'hover') {
				var d = 'mouseenter click'
			} else {
				var d = g.activation
			}
			jQuery(this).bind(d, function () {
				j.stop();
				g.enter.call(this);
				var a = jQuery.extend({},
				jQuery(this).offset(), {
					width: this.offsetWidth,
					height: this.offsetHeight
				});
				j.get(0).className = 'ishare_ishare';
				j.remove().css({
					top: 0,
					left: 0,
					visibility: 'hidden',
					display: 'block'
				}).appendTo(document.body);
				var b = j[0].offsetWidth,
				actualHeight = j[0].offsetHeight;
				var c = (typeof g.position == 'function') ? g.position.call(this) : g.position;
				switch (c) {
				case 'bottom':
					j.css({
						top:
						a.top + a.height + g.edgeOffset,
						left: a.left + a.width / 2 - b / 2
					}).addClass('ishare_bottom');
					break;
				case 'top':
					j.css({
						top:
						a.top - actualHeight - g.edgeOffset,
						left: a.left + a.width / 2 - b / 2
					}).addClass('ishare_top');
					break;
				case 'left':
					j.css({
						top:
						a.top + a.height / 2 - actualHeight / 2,
						left: a.left - b - g.edgeOffset
					}).addClass('ishare_right');
					break;
				case 'right':
					j.css({
						top:
						a.top + a.height / 2 - actualHeight / 2,
						left: a.left + a.width + g.edgeOffset
					}).addClass('ishare_left');
					break
				}
				j.css({
					opacity: 0,
					display: 'block',
					visibility: 'visible'
				}).animate({
					opacity: 1
				},
				g.fadeIn);
				if(g.plusone) gapi.plusone.render("plusone-box", {"size": "medium", "count": "true"}); //plusone
			});
			jQuery(this).bind('mouseleave', function () {
				var b = this;
				if (!g.keepAlive) {
					var c = setTimeout(function () {
						g.exit.call(this);
						j.animate({
							opacity: 0
						},
						g.fadeOut, function () {
							j.css({
								display: 'none',
								visibility: 'hidden'
							})
						})
					},
					g.fadeOut_delay)
				}
				j.hover(function () {
					if (!g.keepAlive) clearTimeout(c)
				},
				function () {
					var a = setTimeout(function () {
						g.exit.call(this);
						j.animate({
							opacity: 0
						},
						g.fadeOut, function () {
							j.css({
								display: 'none',
								visibility: 'hidden'
							})
						})
					},
					g.fadeOut_delay)
				});
				jQuery('#ishare_close').click(function () {
					g.exit.call(this);
					j.animate({
						opacity: 0
					},
					g.fadeOut, function () {
						j.css({
							display: 'none',
							visibility: 'hidden'
						})
					})
				});
				jQuery(this).bind(d, function () {
					if (!g.keepAlive) clearTimeout(c)
				})
			})
		});
		function encodeURL(a) {
			if (!a) {
				return ""
			}
			return a.replace(/\s/g, ' ').replace('+', '+').replace('/ /g', '+').replace('*', '*').replace('/', '/').replace('@', '@')
		}
		function sharing_title() {
			if (g.sharetitle) {
				return g.sharetitle
			} else {
				return document.title
			}
		}
		function sharing_url() {
			return ishare_wpress.shareurl;
		}
		function sharing_image() {
			var img = jQuery('#ishare_img').html();
			return img;
		}
		var w = '';
		function ishare_metakeywords() {
			if (w === '') {
				metaCollection = document.getElementsByTagName('meta');
				for (i = 0; i < metaCollection.length; i++) {
					nameAttribute = metaCollection[i].name.search(/keywords/);
					if (nameAttribute != -1) {
						w = metaCollection[i].content;
						return w
					}
				}
			} else {
				return w
			}
		}
		var x = '';
		function ishare_metadescription() {
			if (x === '') {
				metaCollection = document.getElementsByTagName('meta');
				for (i = 0; i < metaCollection.length; i++) {
					nameAttribute = metaCollection[i].name.search(/description/);
					if (nameAttribute != -1) {
						x = metaCollection[i].content;
						return x
					}
				}
			} else {
				return x
			}
		}
		function bookmark_us() {
			alert('Press ctrl + D to bookmark')
		}
	}
})(jQuery);