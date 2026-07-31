/**
 * @copyright	Copyright (C) 2014 Cedric KEIFLIN alias ced1870
 * http://www.joomlack.fr
 * http://www.template-creator.com
 * Module Carousel CK
 * @license		GNU/GPL
 * */
 
 // v2.0.6	- 27/01/2025 : add aria label on navigation button
 // v2.0.5	- 25/05/23 : fix issue with data-time on each slide
 // v2.0.4	- 27/01/2020 : use image source if exists for JCH optimization
 // v2.0.3	- 13/01/2020 : add alt attribute on image, and aria-label on links
;
(function($) {
	var Carouselck = function (container, opts, callback) {

		var defaults = {
			wrapheight: '40%',
			imageheight: '62%',
			imagesratio: '0.72',
			pagination: true, // show the dots
			navigation: true, // show the navigation buttons
			navigationHover: false, // show the naviation on hover only
			duration: 600, // duration of the sliding effect
			time: 3000, // duration of the image before the next one
			captionduration: 600, // duration of the caption effet
			easing: 'easeInOutExpo', // easing for the caption effect
			autoAdvance: true, //true, false
			playPause: true, //true or false, to display or not the play/pause buttons
			lightbox: 'mediaboxck',
			thumbnails: true,
			captionresponsive: true,
			imagePath: 'modules/mod_carouselck/images/'
		};

		if (!(this instanceof Carouselck)) return new Carouselck(container, opts, callback);
		var carouselcks = window.carouselcks || [];
		if (carouselcks.indexOf(container) > -1) return;
		carouselcks.push(container);
		window.carouselcks = carouselcks;

		function isMobile() {
			if( navigator.userAgent.match(/Android/i) ||
				navigator.userAgent.match(/webOS/i) ||
				navigator.userAgent.match(/iPad/i) ||
				navigator.userAgent.match(/iPhone/i) ||
				navigator.userAgent.match(/iPod/i)
				){
					return true;
			}
		}

		var opts = $.extend({}, defaults, opts);

		var wrap = $(container).addClass('carousel_wrap');
		wrap.append(
				'<div class="carouselck_src"></div>'
				);
		wrap.append(
				'<div class="carouselck_images"></div>'
				);
		wrap.append(
				'<div class="carouselck_thumbs_cont"></div>'
				);
		wrap.append(
				'<div class="carouselck_captions"></div>'
				);
		wrap.append(
				'<div class="carouselck_videos"></div>'
				);

		if (opts.pagination == true) {
			wrap.append(
					'<div class="carouselck_pag"></div>'
					);
		}

		var pagination = $('.carouselck_pag', wrap);
		var carouselimages = $('.carouselck_images', wrap);
		var carouselcaptions = $('.carouselck_captions', wrap);
		var carouselvideos = $('.carouselck_videos', wrap);
		var thumbs = $('.carouselck_thumbs_cont', wrap);

		var allImgSrc = new Array();
		var allImgw = new Array();
		var allImgh = new Array();
		var allImgleft = new Array();
		var allImgtop = new Array();
		var allImgzindex = new Array();
		var allImgopacity = new Array();
		var allThumbs = new Array();
		var allLinks = new Array();
		var allRels = new Array();
		var allTitles = new Array();
		var allTargets = new Array();
		var allAlts = new Array();
		var tmpImgw = new Array();
		var tmpImgh = new Array();
		var tmpImgleft = new Array();
		var tmpImgtop = new Array();
		var tmpImgzindex = new Array();
		var tmpImgopacity = new Array();

		$('> div.carouselck', wrap).each(function() {
			if ($(this).find('> img').length) {
				allImgSrc.push($(this).find('> img').attr('src'));
			} else {
				allImgSrc.push($(this).attr('data-src'));
			}
			if ($(this).attr('data-thumb')) {
				allThumbs.push($(this).attr('data-thumb'));
			} else {
				allThumbs.push('');
			}
			if ($(this).attr('data-link')) {
				allLinks.push($(this).attr('data-link'));
			} else {
				allLinks.push('');
			}
			if ($(this).attr('data-rel')) {
				allRels.push('rel="' + $(this).attr('data-rel') + '" ');
			} else {
				allRels.push('');
			}
			if ($(this).attr('data-title')) {
				allTitles.push('title="' + $(this).attr('data-title') + '" ');
			} else {
				allTitles.push('');
			}
			if ($(this).attr('data-target')) {
				allTargets.push($(this).attr('data-target'));
			} else {
				allTargets.push('');
			}
			if($(this).attr('data-alt')){
				allAlts.push($(this).attr('data-alt'));
			} else {
				allAlts.push('');
			}
			$(this).css('display', 'none');
			$('.carouselck_src', wrap).append($(this));
		});

		var amountSlide = allImgSrc.length;

		var w = wrap.width();

		wrap.css({
			'width': '100%',
			'height': wrap.width() * (parseFloat(opts.wrapheight) / 100)
		});

		var ismoving = false;
		var loopMove;

		// center big image
		tmpImgh[1] = wrap.height();
		tmpImgw[1] = parseFloat(tmpImgh[1]) / parseFloat(opts.imageheight) * 100;
		tmpImgleft[1] = (wrap.width() / 2) - (tmpImgw[1] / 2);
		tmpImgtop[1] = 0;
		tmpImgzindex[1] = 1000;
		tmpImgopacity[1] = 1;

		imagesmtmpoffset = tmpImgw[1] / 2 * opts.imagesratio * opts.imagesratio;

		// left small image
		tmpImgh[0] = tmpImgh[1] * opts.imagesratio;
		tmpImgw[0] = tmpImgw[1] * opts.imagesratio;
		tmpImgleft[0] = tmpImgleft[1] - imagesmtmpoffset;
		tmpImgtop[0] = (tmpImgh[1] - tmpImgh[0]) / 2;
		tmpImgzindex[0] = tmpImgzindex[1] - 11;
		tmpImgopacity[0] = 0.8;

		// right small image
		tmpImgh[2] = tmpImgh[0];
		tmpImgw[2] = tmpImgw[0];
		tmpImgleft[2] = tmpImgleft[1] + tmpImgw[1] - tmpImgw[2] + imagesmtmpoffset;
		tmpImgtop[2] = tmpImgtop[0];
		tmpImgzindex[2] = tmpImgzindex[1] - 10;
		tmpImgopacity[2] = 0.8;

		// hidden small image
		tmpImgh[-1] = tmpImgh[0] * opts.imagesratio;
		tmpImgw[-1] = tmpImgh[0] * opts.imagesratio;
		tmpImgleft[-1] = (wrap.width() / 2) - (tmpImgh[-1] / 2);
		tmpImgtop[-1] = (tmpImgh[0] - tmpImgh[-1]) / 2;
		tmpImgzindex[-1] = tmpImgzindex[0] - 10;
		tmpImgopacity[-1] = 0;

		var i;
		var imagelink;
		for (loopMove = 1; loopMove < amountSlide + 1; loopMove++)
		{
			imgopacity = 1;
			if (loopMove < 3) {
				i = loopMove;
			} else if (loopMove == amountSlide) {
				i = 0;
			} else {
				i = -1;
				imgopacity = 0;
			}

			var allImg = new Array();
			for (var ii=0; ii<allImgSrc.length; ii++) {
				var imgsrc = allImgSrc[ii];
				
				imgsrctmp = imgsrc.split('\/');
				imgnametmp = imgsrctmp[imgsrctmp.length - 1];
				allImg.push(imgnametmp);
			}
	
			var allAltText = new Array();
			$('.carouselck_caption',wrap).each(function(){
				var ind = $(this).parent().index();
				var title = $(this).find('.carouselck_caption_title').text().trim();
				var desc = $(this).find('.carouselck_caption_desc').text().trim();
				var altText = title.length ? title : (desc ? desc : allImg[ind]);
				allAltText.push(altText);
			});
	
	
			newImg = new Image();
			$(newImg).css({
				'position': 'absolute',
				'height': tmpImgh[i],
				'width': tmpImgw[i],
				'left': tmpImgleft[i],
				'top': tmpImgtop[i],
				'z-index': tmpImgzindex[i],
				'opacity': imgopacity
			});
			newImg.src = allImgSrc[loopMove - 1];
			$(newImg).attr('width', parseInt(tmpImgw[i]));
			$(newImg).attr('height', parseInt(tmpImgh[i]));
			$(newImg).attr('alt', (allAltText[loopMove - 1] ? allAltText[loopMove - 1] : allAlts[loopMove - 1]) );
			// $(newImg).attr('alt', allAlts[loopMove - 1] );
			if (loopMove == 1)
				$(newImg).addClass('carouselckcurrentimg');
			imgindex = loopMove == amountSlide ? 0 : loopMove;
			$(newImg).attr('index', imgindex);
			$(newImg).attr('naturalindex', loopMove - 1);
			carouselimages.append($(newImg));

			$(newImg).click(function() {
				if (!ismoving)
					moveImage(getMoveImageTimes($(this).attr('index')), getMoveImageTimes($(this).attr('index')));
			}).mouseover(function() {
			}).mouseleave(function() {
			});

			imagelink = '';
			if (allLinks[loopMove - 1] != '') {
				imagelink = $('<a class="carouselck_link" tabindex="0" aria-label="Link for : ' + allAlts[loopMove - 1] + '" ' + allRels[loopMove - 1] + allTitles[loopMove - 1] + 'href="' + allLinks[loopMove - 1] + '" ' + ' target="' + allTargets[loopMove - 1] + '"></a>');
				carouselimages.append(imagelink);
			}
			if (loopMove == 1 && allLinks[loopMove - 1] != '') {
				imagelink.css({
					'display': 'block',
					'left': tmpImgleft[i],
					'top': tmpImgtop[i],
					'width':  tmpImgw[i],
					'height': tmpImgh[i]
				});
			}

			// reset the array
			allImgh[imgindex] = tmpImgh[i];
			allImgw[imgindex] = tmpImgw[i];
			allImgleft[imgindex] = tmpImgleft[i];
			allImgtop[imgindex] = tmpImgtop[i];
			allImgzindex[imgindex] = tmpImgzindex[i];
			allImgopacity[imgindex] = tmpImgopacity[i];
		}

		var images = $('> img', carouselimages);

		$('.carouselck_caption', wrap).each(function() {
			if (!$(this).hasClass('emptyck')) {
				$(this).wrapInner('<div />');
			}
				$(this).hide();
				carouselcaptions.append($(this));
		});

		carouselcaptions.css({
			'position': 'absolute',
			'height': parseInt(tmpImgh[1]),
			'width': parseInt(tmpImgw[1]),
			'left': parseInt(tmpImgleft[1]) + parseInt($('.carouselckcurrentimg').css('borderLeftWidth')),
			'top': parseInt(tmpImgtop[1]) + parseInt($('.carouselckcurrentimg').css('borderTopWidth'))
		});

		animCaption(0);

		$('.carouselck_src iframe', wrap).each(function() {
			$(this).attr('naturalindex', $(this).parent().index());
			$(this).hide();
			carouselvideos.append($(this));
		});

		carouselvideos.css({
			'position': 'absolute',
			'z-index': 1000,
			'height': parseInt(tmpImgh[1]),
			'width': parseInt(tmpImgw[1]),
			'left': parseInt(tmpImgleft[1]) + parseInt($('.carouselckcurrentimg').css('borderLeftWidth')),
			'top': parseInt(tmpImgtop[1]) + parseInt($('.carouselckcurrentimg').css('borderTopWidth'))
		});
		carouselvideos.hide();
		imgFake();
		$('.imgFake').hide();
		if ($('iframe[naturalindex=0]', $('.carouselck_videos', wrap)).length) {
			carouselvideos.show();
			$('iframe[naturalindex=0]', $('.carouselck_videos', wrap)).show().next('.imgFake').show();
		}

		if ($(pagination).length) {
			$(pagination).append('<div class="carouselck_pag_ul" />');
			var index;
			for (index = 0; index < amountSlide; index++) {
				$('.carouselck_pag_ul', wrap).append('<div class="carouselck_pag_nav pag_nav_' + index + '" style="position:relative; z-index:1002" tabindex="0" aria-label="Show slide '+(index+1)+'"><span><span>' + index + '</span></span></div>');
			}
			$('.carouselck_pag_ul .carouselck_pag_nav', wrap).hover(function() {
				$(this).addClass('carouselck_hover');
				if ($('.carouselck_thumb', this).length) {
					var wTh = $('.carouselck_thumb', this).outerWidth(),
							hTh = $('.carouselck_thumb', this).outerHeight(),
							wTt = $(this).outerWidth();
					$('.carouselck_thumb', this).show().css({'top': '-' + hTh + 'px', 'left': '-' + (wTh - wTt) / 2 + 'px'}).animate({'opacity': 1, 'margin-top': '-3px'}, 200);
					$('.thumb_arrow', this).show().animate({'opacity': 1, 'margin-top': '-3px'}, 200);
				}
			}, function() {
				$(this).removeClass('carouselck_hover');
				$('.carouselck_thumb', this).animate({'margin-top': '-20px', 'opacity': 0}, 200, function() {
					$(this).css({marginTop: '5px'}).hide();
				});
				$('.thumb_arrow', this).animate({'margin-top': '-20px', 'opacity': 0}, 200, function() {
					$(this).css({marginTop: '5px'}).hide();
				});
			}).click(function() {
				if (!ismoving) {
					var thumbDiff = $(this).attr('index') - $('.carouselckcurrent', pagination).attr('index');
					moveImage(thumbDiff, thumbDiff);
				}
			});
		}

		if ($(thumbs).length) {
			var thumbUrl;
			$.each(allThumbs, function(i, val) {
				if ($('> div.carouselck', wrap).eq(i).attr('data-thumb') != '' && opts.thumbnails==true) {
					var thumbUrl = $('div.carouselck_src > div.carouselck', wrap).eq(i).attr('data-thumb'),
					newImg = new Image();
					newImg.src = thumbUrl;
					$(newImg).attr('alt', (allAltText[i] ? allAltText[i] : allAlts[i]) );
					$('.pag_nav_' + i, pagination).attr('index', i).append($(newImg).attr('class', 'carouselck_thumb').css({'position': 'absolute'}).animate({opacity: 0}, 0));
					$('.pag_nav_' + i + ' > img', pagination).after('<div class="thumb_arrow" />');
					$('.pag_nav_' + i + ' > .thumb_arrow', pagination).animate({opacity: 0}, 0);
				}
			});
			$('.carouselck_pag .carouselck_pag_nav', wrap).removeClass('carouselckcurrent');
			$('.carouselck_pag .carouselck_pag_nav', wrap).eq(0).addClass('carouselckcurrent');
		}

		function getMoveImageTimes(imgindex) {
			return imgindex - $('.carouselckcurrentimg', wrap).attr('index');
		}

		function moveImage(times, divider) {
			if (!times)
				times = 1;
			var direction = (times < 0) ? 'fromleft' : 'fromright';
			if (!divider)
				divider = 1;
			if (times == 0)
				return;
			divider = Math.abs(times);
			(times > 0) ? times-- : times++;

			if (divider <= 0)
				divider = 1;
			if (ismoving)
				return;
			ismoving = true;
			wrapw = wrap.width();
			r = wrapw / w;
			$('.carouselck_caption', carouselcaptions).hide();
			$('iframe', carouselvideos).hide();
			carouselvideos.hide();
			$('.imgFake').hide();
			var imgNewIndex;
			var iCaption = '';
			var launchedCaption = false;
			images.each(function(i, img) {
				img = $(img);
				imgIndex = img.attr('index');

				if (img.next('a.carouselck_link').length)
					img.next('a.carouselck_link').css('display', 'none');

				if (direction == 'fromleft') {
					if (parseInt(imgIndex) == amountSlide - 1) {
						imgNewIndex = 0;
					} else {
						imgNewIndex = parseInt(imgIndex) + 1;
					}
				} else {
					if (parseInt(imgIndex) == 0) {
						imgNewIndex = amountSlide - 1;
					} else {
						imgNewIndex = parseInt(imgIndex) - 1;
					}
				}
				if (imgNewIndex == 1)
					img.css('z-index', '1010');
				if (imgNewIndex == 1) {
					iCaption = i;
					iNatural = img.attr('naturalindex');
				}
				img.zindex = allImgzindex[imgNewIndex];
				img.animate({
					'left': parseInt(allImgleft[imgNewIndex]) * r,
					'top': parseInt(allImgtop[imgNewIndex]) * r,
					'width': parseInt(allImgw[imgNewIndex]) * r,
					'height': parseInt(allImgh[imgNewIndex]) * r,
					// 'z-index': allImgzindex[imgNewIndex],
					'opacity': allImgopacity[imgNewIndex]
				}, opts.duration / divider, "linear", function() {
					img.css('z-index', img.zindex);
					if (iCaption !== '' && launchedCaption == false) {
						if (times != 0) {
							ismoving = false;
							moveImage(times, divider);
						} else {
							autoplayImage(iCaption);
							ismoving = false;
							// launchedCaption = true;
							animCaption(iCaption);
							if ($('iframe[naturalindex=' + iCaption + ']', $('.carouselck_videos', wrap)).length && times == 0) {
								carouselvideos.show();
								$('iframe[naturalindex=' + iCaption + ']', $('.carouselck_videos', wrap)).show().next('.imgFake').show();
							}
							if ($('img[naturalindex=' + iCaption + ']', wrap).next('a.carouselck_link').length) {
								$('img[naturalindex=' + iCaption + ']', wrap).next('a.carouselck_link').css({
									'display': 'block',
									'left': parseInt(allImgleft[1]) * r + parseInt($('.carouselckcurrentimg').css('borderLeftWidth')),
									'top': parseInt(allImgtop[1]) * r + parseInt($('.carouselckcurrentimg').css('borderTopWidth')),
									'width': parseInt(allImgw[1]) * r,
									'height': parseInt(allImgh[1]) * r
								});
							}
						}
						launchedCaption = true;
					}
				});
				img.attr('index', imgNewIndex);

				if (imgNewIndex == 1) {
					img.addClass('carouselckcurrentimg');

					if ($(pagination).length) {
						thumbindex = ((i) > $('.carouselck_pag_nav', pagination).length) ? 0 : i;
						$('.carouselck_pag_nav', pagination).removeClass('carouselckcurrent');
						$('.carouselck_pag_nav', pagination).eq(thumbindex).addClass('carouselckcurrent');
					}

					if ($(thumbs).length) {
						thumbindex = ((i) > $('.carouselck_pag_nav', thumbs).length) ? 0 : i;
						$('.carouselck_pag_nav', thumbs).removeClass('carouselckcurrent');
						$('.carouselck_pag_nav', thumbs).eq(thumbindex).addClass('carouselckcurrent');
						$('.carouselck_pag_nav', thumbs).not('.carouselckcurrent').find('img').animate({opacity: .5}, 0);
						$('.carouselck_pag_nav.carouselckcurrent img', thumbs).animate({opacity: 1}, 0);
						$('.carouselck_pag_nav', thumbs).hover(function() {
							$('img', this).stop(true, false).animate({opacity: 1}, 150);
						}, function() {
							if (!$(this).hasClass('carouselckcurrent')) {
								$('img', this).stop(true, false).animate({opacity: .5}, 150);
							}
						});
					}
				} else {
					img.removeClass('carouselckcurrentimg');
				}
			});
		}

		var started;

		$(window).bind('resize pageshow', function() {
			resizeImage();
			if (opts.captionresponsive == true) resizeFont();
		});

		addLightbox();

		function addLightbox() {
			if (opts.lightbox == 'mediaboxck' && typeof(Mediabox) != "undefined") {
				Mediabox.scanPage();
			} else if (opts.lightbox == 'squeezebox') {
				SqueezeBox.initialize({});
				SqueezeBox.assign($$('a.camera_link[rel=lightbox]'), {
					/*parse: 'rel'*/
				});
			}
		}

		function animCaption(i) {
			var dataTime = $('.carouselck_src > div', wrap).eq(i).attr('data-time');
			if (typeof dataTime !== 'undefined' && dataTime !== false && dataTime !== '') {
				time = parseFloat(dataTime);
			} else {
				time = opts.time;
			}

			captionindex = i;
			if ($('.carouselck_caption:eq(' + captionindex + ')', carouselcaptions).length && !$('.carouselck_caption:eq(' + captionindex + ')', carouselcaptions).hasClass('emptyck')) {
				if ($(this).attr('data-easing') != '') {
					var easeMove = $(this).attr('data-easing');
				} else {
					var easeMove = opts.easing;
				}
				var t = $('.carouselck_caption:eq(' + captionindex + ')', carouselcaptions);
				var pos = t.position();
				var left = pos.left;
				var top = pos.top;
				var tClass = t.attr('class');
				var ind = t.index();
				var thisH = t.outerHeight();
				var h = wrap.height();
				var w = wrap.width();
				t.css('display', 'block');
				if (tClass.indexOf("moveFromLeft") != -1) {
					t.css({'left': '-' + (w) + 'px', 'right': 'auto'});
					t.css('visibility', 'visible').animate({'left': pos.left}, opts.captionduration, easeMove);
				} else if (tClass.indexOf("moveFromRight") != -1) {
					t.css({'left': w + 'px', 'right': 'auto'});
					t.css('visibility', 'visible').animate({'left': pos.left}, opts.captionduration, easeMove);
				} else if (tClass.indexOf("moveFromTop") != -1) {
					t.css({'top': '-' + h + 'px', 'bottom': 'auto'});
					t.css('visibility', 'visible').animate({'top': pos.top}, opts.captionduration, easeMove, function() {
						t.css({top: 'auto', bottom: 0});
					});
				} else if (tClass.indexOf("moveFromBottom") != -1) {
					t.css({'top': h + 'px', 'bottom': 'auto'});
					t.css('visibility', 'visible').animate({'top': 0}, opts.captionduration, easeMove);
				} else if (tClass.indexOf("fadeFromLeft") != -1) {
					t.animate({opacity: 0}, 0).css({'left': '-' + (w) + 'px', 'right': 'auto'});
					t.css('visibility', 'visible').animate({'left': pos.left, opacity: 1}, opts.captionduration, easeMove);
				} else if (tClass.indexOf("fadeFromRight") != -1) {
					t.animate({opacity: 0}, 0).css({'left': (w) + 'px', 'right': 'auto'});
					t.css('visibility', 'visible').animate({'left': pos.left, opacity: 1}, opts.captionduration, easeMove);
				} else if (tClass.indexOf("fadeFromTop") != -1) {
					t.animate({opacity: 0}, 0).css({'top': '-' + (h) + 'px', 'bottom': 'auto'});
					t.css('visibility', 'visible').animate({'top': pos.top, opacity: 1}, opts.captionduration, easeMove, function() {
						t.css({top: 'auto', bottom: 0});
					});
				} else if (tClass.indexOf("fadeFromBottom") != -1) {
					t.animate({opacity: 0}, 0).css({'bottom': '-' + thisH + 'px'});
					t.css('visibility', 'visible').animate({'bottom': '0', opacity: 1}, opts.captionduration, easeMove);
				} else if (tClass.indexOf("fadeIn") != -1) {
					t.animate({opacity: 0}, 0).css('visibility', 'visible').animate({opacity: 1}, opts.captionduration, easeMove);
				} else {
					t.css('visibility', 'visible');
				}
			}
		}

		function resizeImage() {
			var res;
			function resizeImageWork() {
				wrapw = wrap.width();
				r = wrapw / w;
				wrap.css('height', wrap.width() * (parseFloat(opts.wrapheight) / 100));
				images.each(function(i, t) {

					var t = $(t),
							imgindex = t.attr('index'),
							tw = parseInt(allImgw[imgindex]) * r,
							th = parseInt(allImgh[imgindex]) * r,
							tleft = parseInt(allImgleft[imgindex]) * r,
							ttop = parseInt(allImgtop[imgindex]) * r;

					t.css({
						'height': th,
						'width': tw,
						'left': tleft,
						'top': ttop
					});
				});
				$('.carouselck_captions, .carouselck_videos, .carouselck_link', wrap).css({
					'height': parseInt(tmpImgh[1]) * r,
					'width': parseInt(tmpImgw[1]) * r,
					'left': parseInt(tmpImgleft[1]) * r + parseInt($('.carouselckcurrentimg').css('borderLeftWidth')),
					'top': parseInt(tmpImgtop[1]) * r + parseInt($('.carouselckcurrentimg').css('borderTopWidth'))
				});
			}
			if (started == true) {
				clearTimeout(res);
				res = setTimeout(resizeImageWork, 200);
			} else {
				resizeImageWork();
			}

			started = true;
		}

		function resizeFont() {
			var fontRatio = wrap.width() / 500;
			$('.carouselck_caption > div', wrap).css('font-size', fontRatio + 'em');
		}

		if (opts.autoAdvance == false) {
			carouselimages.addClass('paused');
		}
		var setT;
		autoplayImage(0);

		function autoplayImage(i) {
			if (opts.autoAdvance == false)
				return;

			var dataTime = $('.carouselck_src > div', wrap).eq(i).attr('data-time');
			if (typeof dataTime !== 'undefined' && dataTime !== false && dataTime !== '') {
				time = parseFloat(dataTime);
			} else {
				time = opts.time;
			}

			if (!carouselimages.hasClass('paused')) {
				clearTimeout(setT);
				setT = setTimeout(moveImage, time);
			}
		}

		if (opts.playPause == true) {
			wrap.append(
					'<div class="carouselck_commands"></div>'
					)
		}
		var commands = $('.carouselck_commands', wrap);

		if ($(commands).length) {
			$(commands).append('<div class="carouselck_play" tabindex="0" aria-label="Start the carousel"></div>').append('<div class="carouselck_stop" tabindex="0" aria-label="Pause the carousel"></div>');
			if (opts.autoAdvance == true) {
				$('.carouselck_play', wrap).hide();
				$('.carouselck_stop', wrap).show();
			} else {
				$('.carouselck_stop', wrap).hide();
				$('.carouselck_play', wrap).show();
			}

		}

		if (opts.navigation == true) {
			wrap.append(
					'<div class="carouselck_prev" tabindex="0" aria-label="Previous slide"><span></span></div>'
					).append(
					'<div class="carouselck_next" tabindex="0" aria-label="Next slide"><span></span></div>'
					);
		}

		function imgFake() {
			$('iframe', $('.carouselck_videos', wrap)).each(function() {
				// $('.carouselck_caption',wrap).show();
				var t = $(this);
				var cloneSrc = t.attr('src');
				// t.attr('src', cloneSrc);
				var imgFakeUrl = opts.imagePath + 'blank.gif';
				var imgFake = new Image();
				imgFake.src = imgFakeUrl;
				h = parseInt(opts.height);
				t.after($(imgFake).attr({
					'class': 'imgFake', 
					}).css({
					'width': $('.carouselck_videos', wrap).width(), 
					'height': $('.carouselck_videos', wrap).height(),
					'position': 'absolute',
					'top': '0',
					'left': '0',
					'cursor': 'pointer'
					}));
				// var clone = t.clone();
				// t.remove();
				$(imgFake).bind('click', function() {
					if ($(this).css('position') == 'absolute') {
						$(this).remove();
						if (cloneSrc.indexOf('vimeo') != -1 || cloneSrc.indexOf('youtube') != -1) {
							if (cloneSrc.indexOf('?') != -1) {
								autoplay = '&autoplay=1';
							} else {
								autoplay = '?autoplay=1';
							}
						} else if (cloneSrc.indexOf('dailymotion') != -1) {
							if (cloneSrc.indexOf('?') != -1) {
								autoplay = '&autoPlay=1';
							} else {
								autoplay = '?autoPlay=1';
							}
						}
						t.attr('src', cloneSrc + autoplay);
						videoPresent = true;
						autoAdv = false;
						carouselimages.addClass('paused');
						clearTimeout(setT);
						if ($('.carouselck_stop', commands).length) {
							$('.carouselck_stop', commands).hide()
							$('.carouselck_play', commands).show();
						}
					} else {
						$(this).css({position: 'absolute', top: 0, left: 0, zIndex: 10}).after(t);
						t.css({position: 'absolute', top: 0, left: 0, zIndex: 9});
					}
				});
			});
		}

		// imgFake();

		// manage events ands actions
		wrap.on('click', '.carouselck_stop', function() {
			autoAdv = false;
			carouselimages.addClass('paused');
			clearTimeout(setT);
			if ($('.carouselck_stop', commands).length) {
				$('.carouselck_stop', commands).hide()
				$('.carouselck_play', commands).show();
//			if(loader!='none'){
//				$('#'+pieID).hide();
//			}
			} else {
//			if(loader!='none'){
//				$('#'+pieID).hide();
//			}
			}
		});

		wrap.on('click', '.carouselck_play', function() {
			autoAdv = true;
			carouselimages.removeClass('paused');
			if ($('.carouselck_play', commands).length) {
				$('.carouselck_play', commands).hide();
				$('.carouselck_stop', commands).show();
				autoplayImage();
//			if(loader!='none'){
//				$('#'+pieID).show();
//			}
			} else {
//			if(loader!='none'){
//				$('#'+pieID).show();
//			}
			}
		});

		// $('.carouselck_videos').click(function() {alert('toto');
			// if(videoPresent == true && videoHover == true) {
				// autoAdv = false;
				// $('.camera_caption',fakeHover).hide();
				// elem.addClass('paused');
				// $('.camera_stop',camera_thumbs_wrap).hide()
				// $('.camera_play',camera_thumbs_wrap).show();
				// $('#'+pieID).hide();
			// }
		// });

		wrap.on('click', '.carouselck_next', function() {
			moveImage(1, 1);
		});

		wrap.on('click', '.carouselck_prev', function() {
			moveImage(-1, -1);
		});

		var prevNav = $('.carouselck_prev', wrap),
				nextNav = $('.carouselck_next', wrap);

		if (opts.navigationHover == true) {
			$(prevNav, wrap).animate({opacity: 0}, 0);
			$(nextNav, wrap).animate({opacity: 0}, 0);
			$(commands, wrap).animate({opacity: 0}, 0);
			wrap.hover(function() {
				$(prevNav, wrap).animate({opacity: 1}, 200);
				$(nextNav, wrap).animate({opacity: 1}, 200);
				$(commands, wrap).animate({opacity: 1}, 200);
			}, function() {
				$(prevNav, wrap).animate({opacity: 0}, 200);
				$(nextNav, wrap).animate({opacity: 0}, 200);
				$(commands, wrap).animate({opacity: 0}, 200);
			});
		}

		// for touch device
		if(isMobile()){
			wrap.bind('swipeleft',function(event){
				moveImage(1, 1);
			});
			wrap.bind('swiperight',function(event){
				moveImage(-1, -1);
			});
		}
	}
	window.Carouselck = Carouselck;
})(jQuery);