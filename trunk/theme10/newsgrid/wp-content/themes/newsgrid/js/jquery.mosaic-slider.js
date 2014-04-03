/**
 * Plugin Name: OTV Mosaic Slider
 * Plugin URI: ---
 * Description: Creates a Slider interface for jQuery.PhotoMosaic
 * Author: Michael Kafka
 * Author URI: http://www.codecanyon.net/user/makfak?ref=makfak | http://www.makfak.com
 * Version: 1.4
 */

;(function ( $, window, document, undefined ) {

    var pluginName = 'mosaicSlider';

    var windowSize;
    var self;

    var Plugin = function ( element, options ) {
        self = this;

        this.el = element;
        this.obj = $(element);
        this._options = options;
        this._id = (Date.parse(new Date()) + Math.round(Math.random() * 10000)).toString();

        this.mosaic = {
            container : null,
            instance : null,
            opts : {
                width : 0,
                columns : 0
            },
            initial : {}
        };

        this.init();
    };

    Plugin.prototype = {

        _defaults : {
            bypass : false,
            columnWidth : 240,
            rows : 3,
            width : '100%',
            height : 640,
            loadFonts : true,
            fonts : [ 'Varela+Round::latin' ],
            callbacks : {
                at_right : function () {},
                at_left : function() {}
            },
            // this is passed straight through to PhotoMosaic
            photoMosaic : {
                input : 'json',
                gallery : '', // set in this.photoMosaic
                padding : 0,
                links : true,
                modal_name: 'prettyPhoto',
                responsive_transition : false,
                modal_ready_callback : function (pm) {
                    $(pm).find('a[rel^="prettyPhoto"]').prettyPhoto( self.opts.prettyPhoto );
                },
                // this is passed straight through to Lazyload
                lazyload : {
                    active : true,
                    threshold : 0
                }
            },
            // this is passed straight through to PrettyPhoto
            prettyPhoto : {
                overlay_gallery : false,
                slideshow : false,
                theme : "pp_default",
                deeplinking : false,
                social_tools : "",
                show_title : false,
                caption_selector : '.ms_description'
            }
        },

        _templates : {
            main : '' +
                '<div id="mosaicSlider_{{id}}" class="mosaicSlider" style="width:{{width}}">' +
                    '<div class="ms_mosaic overthrow">{{{html}}}</div>' +
                    '<a href="#" class="ms_arrow ms_prev"><span>prev</span></a>' +
                    '<a href="#" class="ms_arrow ms_next"><span>next</span></a>' +
                '</div>',

            caption : '' +
                '<div class="ms_caption">' +
                    '<span class="ms_title">{{title}}</span>' +
                    '<span class="ms_description">{{description}}</span>' +
                '</div>',

            bubble : '' +
                '<a href="{{link}}" class="ms_bubble ms_bubble_{{clazz}}">' +
                    '<span class="ms_speaker"></span>' +
                    '<span class="ms_text">{{text}}</span>' +
                '</a>'
        },

        init : function (options) {
            var self = this;

            this.opts = $.extend( true, {}, this._defaults, this._options );

            // copy the HTML that PhotoMosaic will use to construct a gallery
            // place it in the MosaicSlider template
            // replace the HTML with the new markup
            this.clonedHTML = this.obj.html();

            // make sure this.opts.width is a valid string
            this.opts.width = this.validateWidth(this.opts.width);

            // save px for window.resize checks later
            windowSize = this.obj.width();

            this.obj.html(
                PhotoMosaic.Mustache.to_html(
                    this._templates.main,
                    {
                        id : this._id,
                        html : this.clonedHTML,
                        width : this.opts.width
                    }
                )
            );

            // wait a tick so we can reference the markup we just added to the DOM
            setTimeout(function () {
                self.mosaic.instance = self.photoMosaic();
                self.arrows = $('.ms_arrow');
                // prevent the at_left event from firing immediately.
                self.mosaic.container.get(0).scrollLeft = 1;
                self.manageArrows();
                self.bindEvents();
            }, 0);

            this.loadFonts();
        },

        photoMosaic : function () {
            var mosaic;
            var mosaicHeight = 0;

            this.mosaic.container = this.obj.find('.ms_mosaic');

            this.mosaic.opts = this.determineMosaicOptions();

            // set the gallery data
            this.opts.photoMosaic.gallery = this.constructJSONFromHTML(this.mosaic.container);

            // call PhotoMosaic
            this.mosaic.container.photoMosaic(
                $.extend(true, {}, this.mosaic.opts, this.opts.photoMosaic)
            );

            // this is a reference to the PM instance
            mosaic = this.mosaic.container.data('photoMosaic');

            // the resulting mosaic will likely be too tall
            // tweak the number of columns and the width to reach the target height
            mosaic = this.adjustMosaicHeight(mosaic);

            return mosaic;
        },

        determineMosaicOptions : function () {
            if (this.opts.bypass) {
                return {
                    // height : this.opts.height
                };
            }

            var items = this.mosaic.container.find('li').length;
            var columns = Math.ceil(items / this.opts.rows);
            var mosaicWidth = columns * this.opts.columnWidth;
            var windowWidth = this.mosaic.container.width();

            // the mosaic should, at least, be as wide as the window
            if ( mosaicWidth <= windowWidth ) {
                mosaicWidth = windowWidth;
                columns = Math.floor(mosaicWidth / this.opts.columnWidth);

                if (columns > items) {
                    columns = items;
                }
            }

            return {
                width : mosaicWidth,
                columns : columns
            };
        },

        adjustMosaicHeight : function (mosaic) {
            var self = this;

            if ( this.opts.bypass ) {
                setTimeout(function () {
                    self.refresh();
                }, 0);

            } else if ( mosaic.height > (self.opts.height + 50) ) {
                mosaic.opts.columns = this.mosaic.opts.columns = this.mosaic.opts.columns + 1;
                mosaic.opts.width = mosaic.opts.width + this.opts.columnWidth;
                mosaic.refresh();
                setTimeout(function () {
                    self.adjustMosaicHeight(mosaic);
                }, 0);
            } else {
                mosaic.opts.height = self.opts.height;
                mosaic.refresh();

                this.mosaic.initial = {
                    columns : mosaic.opts.columns,
                    width : mosaic.opts.width,
                    columnWidth : this.opts.columnWidth
                };

                setTimeout(function () {
                    self.refresh();
                }, 0);
            }

            return mosaic;
        },

        refresh : function () {
            var windowWidth = this.mosaic.container.width();
            var mosaicWidth = this.mosaic.container.find('.photoMosaic').outerWidth();
            var mosaic = this.mosaic.instance;
            var self = this;

            if ( this.opts.bypass ) {
                mosaic.refresh();

            } else if ( this.resizeDirection() === 'up' && (windowWidth > mosaicWidth) ) {
                // if the window is wider than the mosaic, increase the number of columns
                // until the mosaic is wider OR #cols === #images
                // then increase the width of columns to keep the mosaic.width === window.width
                if (mosaic.opts.gallery.length > mosaic.opts.columns) {
                    // we can add another column
                    mosaic.opts.columns = this.mosaic.opts.columns = this.mosaic.opts.columns + 1;
                    mosaic.opts.width = mosaic.opts.width + this.opts.columnWidth;
                    mosaic.refresh();

                    if (mosaic.opts.width < windowWidth) {
                        setTimeout(function () {
                            self.refresh();
                        }, 0);
                    }

                } else {
                    // increase columnWidth
                    mosaic.opts.width = windowWidth;
                    this.opts.columnWidth = Math.floor(windowWidth / mosaic.opts.columns);
                    mosaic.refresh();
                }

            } else if (this.resizeDirection() === 'down' && (windowWidth < mosaicWidth)) {
                if (this.opts.columnWidth > this.mosaic.initial.columnWidth) {
                    // decrease columnWidth
                    mosaic.opts.width = windowWidth;
                    this.opts.columnWidth = Math.floor(windowWidth / mosaic.opts.columns);
                    mosaic.refresh();

                } else if (mosaic.opts.columns > this.mosaic.initial.columns) {
                    // decrease the number of columns
                    if ( (mosaic.opts.width - this.opts.columnWidth) > windowWidth ) {
                        mosaic.opts.columns = this.mosaic.opts.columns = this.mosaic.opts.columns - 1;
                        mosaic.opts.width = mosaic.opts.width - this.opts.columnWidth;
                        mosaic.refresh();
                    }
                }
            }

            windowSize = windowWidth;

            this.mosaicReady();
            this.manageArrows();
        },

        // modified copy from PhotoMosaic source
        constructJSONFromHTML : function (node) {
            var gallery = [];
            var $images = node.find('img');

            for (var i = 0; i < $images.length; i++) {
                var $image = $images.eq(i)
                var image = {
                    caption : $image.attr('title'),
                    alt : $image.attr('alt'),
                    width : parseInt( $image.attr('width') ),
                    height : parseInt( $image.attr('height') )
                };

                if ($image.parent('a').length > 0 && this.opts.photoMosaic.links) {
                    image.src = $image.attr('src');
                    image.url = $image.parent('a').attr('href');
                } else if ($image.parent('a').length > 0) {
                    image.src = $image.parent('a').attr('href');
                } else {
                    image.src = $image.attr('src');
                }

                if ($image.attr('data-bubble-text') && $image.attr('data-bubble-class') && $image.attr('data-bubble-link')) {
                    image.bubble = {
                        text : $image.attr('data-bubble-text'),
                        clazz : $image.attr('data-bubble-class'),
                        link : $image.attr('data-bubble-link')
                    };
                }

                gallery.push(image);
            }

            return gallery;
        },

        mosaicReady : function () {
            var $items = this.mosaic.container.find('.photoMosaic > a');
            var self = this;

            $items.each(function (i) {
                var $a = $(this);
                var $img = $a.find('img');
                var $caption = $a.find('.ms_caption');
                var $bubble = $a.find('.ms_bubble');

                if ($caption.length === 0) {
                    self.createCaption($a, $img);
                } else {
                    self.updateCaption($a);
                }

                if ($bubble.length === 0) {
                    self.createBubble($a, $img);
                }
            });
        },

        createCaption : function ($a, $img) {
            var caption = PhotoMosaic.Mustache.to_html(
                this._templates.caption,
                {
                    title : $img.attr('alt'),
                    description : $img.attr('title')
                }
            );

            $a.append(caption);

            // remove titles to prevent the default browser tooltip
            $a.removeAttr('title');
            $img.removeAttr('title');

            // bind hover events
            $a.on('mouseenter.mosaicSlider', function (e) {
                $(this).find('.ms_caption').css('bottom', '0px');
            });

            $a.on('mouseleave.mosaicSlider', function (e) {
                var $caption = $(this).find('.ms_caption');
                $caption.css(
                    'bottom',
                    ($caption.attr('data-height') * -1) + 'px'
                );
            });

            this.updateCaption($a);
        },

        updateCaption : function ($a) {
            var $caption = $a.find('.ms_caption');

            $caption.attr(
                'data-height',
                $a.find('.ms_description').outerHeight()
            );

            $caption.css(
                'bottom',
                ($caption.attr('data-height') * -1) + 'px'
            );
        },

        createBubble : function ($a, $img) {
            var data = this.mosaic.instance.deepSearch(
                this.mosaic.instance.images,
                'id',
                $img.attr('id')
            );
            var bubble;
            var self = this;

            if (data.bubble) {
                bubble = PhotoMosaic.Mustache.to_html( this._templates.bubble, data.bubble );
                $a.append(bubble);
            }

            setTimeout(function () {
                self.obj.find('.ms_bubble').on( 'click.mosaicSlider', function (e) {
                    e.stopPropagation();
                });
            }, 0);
        },

        validateWidth : function (width) {
            switch (typeof width) {
                case 'string':
                    // make sure it has a unit
                    if (!width.match(/^([0-9]+)(px|em|ex|%|in|cm|mm|pt|pc)$/)) {
                        if ( isNaN(parseInt(width)) ) {
                            width = this._defaults.width;
                        } else {
                            width = width + 'px';
                        }
                    }
                    break;
                case 'number':
                    width = (width).toString() + 'px';
                    break;
                default:
                    width = this._defaults.width;
                    break;
            }

            return width;
        },

        loadFonts : function () {
            if (
                this.opts.loadFonts &&
                this.opts.fonts !== null &&
                typeof this.opts.fonts !== 'undefined' &&
                typeof this.opts.fonts !== 'string' &&
                this.opts.fonts.length > 0
            ) {
                // as per Google.Fonts instructions
                // used in the speech bubbles
                WebFontConfig = {
                    google: {
                        families: this.opts.fonts || []
                    }
                };
                (function() {
                    var wf = document.createElement('script');
                    wf.src = ('https:' == document.location.protocol ? 'https' : 'http') +
                        '://ajax.googleapis.com/ajax/libs/webfont/1/webfont.js';
                    wf.type = 'text/javascript';
                    wf.async = 'true';
                    var s = document.getElementsByTagName('script')[0];
                    s.parentNode.insertBefore(wf, s);
                })();
            }
        },

        mousewheel : function (e, delta, deltaX, deltaY) {
            var current = this.mosaic.container.scrollLeft();
            var next = Math.ceil( current + (-1 * (delta * 20)) );

            this.mosaic.container.scrollLeft(next);
            this.manageArrows();

            var mosaicWidth = this.mosaic.container.find('.photoMosaic').outerWidth();
            var scroll = next;
            var windowWidth = this.mosaic.container.width();

            if ( (scroll > 0) && ((scroll + windowWidth) < mosaicWidth) ) {
                e.preventDefault();
                e.stopPropagation();
            }
        },

        arrowClick : function (e) {
            var width = this.obj.width();
            var scroll = this.mosaic.container.scrollLeft();
            var direction;
            var node = $(e.target);
            var self = this;

            if ( node.is('.ms_prev') ) {
                direction = -1;
            }

            if ( node.is('.ms_next') ) {
                direction = 1;
            }

            this.mosaic.container.animate({
                scrollLeft : scroll + (width * direction)
            }, 650, 'swing', function () {
                self.manageArrows();
            });

            e.preventDefault();
        },

        manageArrows : function () {
            var mosaicWidth = this.mosaic.container.find('.photoMosaic').outerWidth();
            var scroll = this.mosaic.container.scrollLeft();
            var windowWidth = this.mosaic.container.width();

            this.arrows.show();

            if (scroll === 0) {
                this.arrows.filter('.ms_prev').hide();
                this.opts.callbacks.at_left.apply(this, []);
            }

            if ( (scroll + windowWidth) > (mosaicWidth - 5) ) { // 5px margin for error
                this.arrows.filter('.ms_next').hide();
                this.opts.callbacks.at_right.apply(this, []);
            }
        },

        bindEvents : function () {
            var self = this;

            this.obj.on('click', '.ms_arrow', function (e) {
                self.arrowClick(e);
            });

            this.obj.bind('mousewheel', function (e, delta, deltaX, deltaY) {
                self.mousewheel(e, delta, deltaX, deltaY);
            });

            this.mosaic.container.bind('scroll', function (e) {
                self.manageArrows();
            });

            $(window)
                .unbind('resize.mosaicSlider' + this._id)
                .bind('resize.mosaicSlider' + this._id, function () {
                    self.refresh();
                });
        },

        resizeDirection : function () {
            var width = this.obj.width();

            if (width > windowSize) {
                return 'up';
            } else if (width < windowSize) {
                return 'down';
            } else {
                return 'same';
            }
        },

        _name : pluginName,

        version : '1.4'
    };

    $.fn[pluginName] = function ( options ) {
        options = options || {};
        return this.each(function () {
            if (!$.data(this, pluginName)) {
                $.data(this, pluginName, new Plugin( this, options ));
            }
        });
    };

}( jQuery, window, document ));

/*! Overthrow v.0.1.0. An overflow:auto polyfill for responsive design. (c) 2012: Scott Jehl, Filament Group, Inc. http://filamentgroup.github.com/Overthrow/license.txt */
;(function(e,t){var n=e.document,r=n.documentElement,i="overthrow-enabled",s="ontouchmove"in n,o="WebkitOverflowScrolling"in r.style||!s&&e.screen.width>1200||function(){var t=e.navigator.userAgent,n=t.match(/AppleWebKit\/([0-9]+)/),r=n&&n[1],i=n&&r>=534;return t.match(/Android ([0-9]+)/)&&RegExp.$1>=3&&i||t.match(/ Version\/([0-9]+)/)&&RegExp.$1>=0&&e.blackberry&&i||t.indexOf(/PlayBook/)>-1&&RegExp.$1>=0&&i||t.match(/Fennec\/([0-9]+)/)&&RegExp.$1>=4||t.match(/wOSBrowser\/([0-9]+)/)&&RegExp.$1>=233&&i||t.match(/NokiaBrowser\/([0-9\.]+)/)&&parseFloat(RegExp.$1)===7.3&&n&&r>=533}(),u=function(e,t,n,r){return n*((e=e/r-1)*e*e+1)+t},a=false,f,l=function(n,r){var i=0,s=n.scrollLeft,o=n.scrollTop,u={top:"+0",left:"+0",duration:100,easing:e.overthrow.easing},a,l;if(r){for(var c in u){if(r[c]!==t){u[c]=r[c]}}}if(typeof u.left==="string"){u.left=parseFloat(u.left);a=u.left+s}else{a=u.left;u.left=u.left-s}if(typeof u.top==="string"){u.top=parseFloat(u.top);l=u.top+o}else{l=u.top;u.top=u.top-o}f=setInterval(function(){if(i++<u.duration){n.scrollLeft=u.easing(i,s,u.left,u.duration);n.scrollTop=u.easing(i,o,u.top,u.duration)}else{if(a!==n.scrollLeft){n.scrollLeft=a}if(l!==n.scrollTop){n.scrollTop=l}h()}},1);return{top:l,left:a,duration:u.duration,easing:u.easing}},c=function(e,t){return!t&&e.className&&e.className.indexOf("overthrow")>-1&&e||c(e.parentNode)},h=function(){clearInterval(f)},p=function(){if(a){return}a=true;if(o||s){r.className+=" "+i}e.overthrow.forget=function(){r.className=r.className.replace(i,"");if(n.removeEventListener){n.removeEventListener("touchstart",T,false)}e.overthrow.easing=u;a=false};if(o||!s){return}var f,p=[],d=[],v,m,g=function(){p=[];v=null},y=function(){d=[];m=null},b=function(){var e=(p[0]-p[p.length-1])*8,t=(d[0]-d[d.length-1])*8,n=Math.max(Math.abs(t),Math.abs(e))/8;e=(e>0?"+":"")+e;t=(t>0?"+":"")+t;if(!isNaN(n)&&n>0&&(Math.abs(t)>80||Math.abs(e)>80)){l(f,{left:t,top:e,duration:n})}},E,S=function(e){E=f.querySelectorAll("textarea, input");for(var t=0,n=E.length;t<n;t++){E[t].style.pointerEvents=e}},x=function(e,r){if(n.createEvent){var i=(!r||r===t)&&f.parentNode||f.touchchild||f,s;if(i!==f){s=n.createEvent("HTMLEvents");s.initEvent("touchend",true,true);f.dispatchEvent(s);i.touchchild=f;f=i;i.dispatchEvent(e)}}},T=function(e){h();g();y();f=c(e.target);if(!f||f===r||e.touches.length>1){return}S("none");var t=e,n=f.scrollTop,i=f.scrollLeft,s=f.offsetHeight,o=f.offsetWidth,u=e.touches[0].pageY,a=e.touches[0].pageX,l=f.scrollHeight,w=f.scrollWidth,E=function(e){var r=n+u-e.touches[0].pageY,c=i+a-e.touches[0].pageX,h=r>=(p.length?p[0]:0),b=c>=(d.length?d[0]:0);if(r>0&&r<l-s||c>0&&c<w-o){e.preventDefault()}else{x(t)}if(v&&h!==v){g()}if(m&&b!==m){y()}v=h;m=b;f.scrollTop=r;f.scrollLeft=c;p.unshift(r);d.unshift(c);if(p.length>3){p.pop()}if(d.length>3){d.pop()}},T=function(e){b();S("auto");setTimeout(function(){S("none")},450);f.removeEventListener("touchmove",E,false);f.removeEventListener("touchend",T,false)};f.addEventListener("touchmove",E,false);f.addEventListener("touchend",T,false)};n.addEventListener("touchstart",T,false)};e.overthrow={set:p,forget:function(){},easing:u,toss:l,intercept:h,closest:c,support:o?"native":s&&"polyfilled"||"none"};p()})(this);

// --- if you're including Mousewheel elsewhere, everything below this line can be deleted ---

/*! Copyright (c) 2011 Brandon Aaron (http://brandonaaron.net)
 * Licensed under the MIT License (LICENSE.txt).
 *
 * Thanks to: http://adomas.org/javascript-mouse-wheel/ for some pointers.
 * Thanks to: Mathias Bank(http://www.mathias-bank.de) for a scope bug fix.
 * Thanks to: Seamus Leahy for adding deltaX and deltaY
 *
 * Version: 3.0.6
 * 
 * Requires: 1.2.2+
 */
;(function(a){function d(b){var c=b||window.event,d=[].slice.call(arguments,1),e=0,f=!0,g=0,h=0;return b=a.event.fix(c),b.type="mousewheel",c.wheelDelta&&(e=c.wheelDelta/120),c.detail&&(e=-c.detail/3),h=e,c.axis!==undefined&&c.axis===c.HORIZONTAL_AXIS&&(h=0,g=-1*e),c.wheelDeltaY!==undefined&&(h=c.wheelDeltaY/120),c.wheelDeltaX!==undefined&&(g=-1*c.wheelDeltaX/120),d.unshift(b,e,g,h),(a.event.dispatch||a.event.handle).apply(this,d)}var b=["DOMMouseScroll","mousewheel"];if(a.event.fixHooks)for(var c=b.length;c;)a.event.fixHooks[b[--c]]=a.event.mouseHooks;a.event.special.mousewheel={setup:function(){if(this.addEventListener)for(var a=b.length;a;)this.addEventListener(b[--a],d,!1);else this.onmousewheel=d},teardown:function(){if(this.removeEventListener)for(var a=b.length;a;)this.removeEventListener(b[--a],d,!1);else this.onmousewheel=null}},a.fn.extend({mousewheel:function(a){return a?this.bind("mousewheel",a):this.trigger("mousewheel")},unmousewheel:function(a){return this.unbind("mousewheel",a)}})})(jQuery);
