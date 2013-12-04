jQuery(window).on("load", function() {
	
	// Centered Play icon (Videos)
	if (jQuery('.latest-video').length) {
		jQuery('.latest-video').each(function() {
			var lv_s = jQuery(this).find('a span');
			var lv_w = jQuery(this).width();
			var lv_h = jQuery(this).height();
			lv_s.css('left',(lv_w/2)-26);
			lv_s.css('top',(lv_h/2)-46);
		})
	}
	// Flexslider
	jQuery('.flexslider').flexslider({
		directionNav: true,
		smoothHeight: true,
		slideshow: Boolean(ThemeOption.slider_auto),
		after: function(slider){
		var currentSlide = slider.slides.eq(slider.currentSlide);
		currentSlide.siblings().each(function() {
			var src = jQuery(this).find('iframe').attr('src');
			jQuery(this).find('iframe').attr('src',src);
		});
		}	 
	});

	if (jQuery('.listing').length) {
		jQuery('.listing').equalHeights();
	}

	if (jQuery('.discography').length) {
		jQuery('.discography').equalHeights();
	}


	if (jQuery(".filter-container").length > 0) {
		var $container = jQuery('.filter-container');
		$container.isotope();
	
		// filter items when filter link is clicked
		jQuery('.filters-nav li a').click(function(){
			var selector = jQuery(this).attr('data-filter');
			jQuery(this).parent().siblings().find('a').removeClass('selected');
			jQuery(this).addClass("selected");
		
			$container.isotope({ 
				filter: selector,
				animationOptions: {
					duration: 750,
					easing: 'linear',
					queue: false
				}
			});
		
			return false;
		});
	}	

});

jQuery(document).ready(function($) {

	// Main navigation
	$('ul.sf-menu').superfish({ 
		delay:       1000,
		animation:   {opacity:'show'},
		speed:       'fast',
		dropShadows: false
	});

	/* -----------------------------------------
	 Responsive Menus Init with jPanelMenu
	 ----------------------------------------- */
	var jPM = $.jPanelMenu({
		menu: '#navigation',
		trigger: '.menu-trigger',
		excludedPanelContent: "style, script, #wpadminbar"
	});

	var jRes = jRespond([
		{
			label: 'mobile',
			enter: 0,
			exit: 959
		}
	]);

	jRes.addFunc({
		breakpoint: 'mobile',
		enter: function() {
			jPM.on();
		},
		exit: function() {
			jPM.off();
		}
	});

	// Tour dates widget
	if ($('.widget .tour-dates li').length) {
		$('.widget .tour-dates li').equalHeights();
	}
	
	// Content videos
	if ($('.post').length) {
		$('.post').fitVids();
	}
	
	// Video Slides
	if ($('.video-slide').length) {
		$('.video-slide').fitVids();
	}
	
	// Tracklisting
	if ($('.track-listen').length) {
		$('.track-listen').click(function(){
			var target 		= $(this).siblings('.track-audio');
			var siblings	= $(this).parents('.track').siblings().children('.track-audio');
			siblings.slideUp('fast');
			target.slideToggle('fast');
			return false;
		});
	}
	
	// Tracklisting check subtitles
	if ($('.track').length) {
		$('.track').each(function(){
			var main_head = $(this).find('.main-head');
			if (main_head.length == 0) 
				$(this).addClass('track-single');
		});
	}
	
	// Lightboxes
	if ($("a[data-rel^='prettyPhoto']").length) {
		$("a[data-rel^='prettyPhoto']").prettyPhoto({
			show_title: false
		});
		$("a[data-rel^='prettyPhoto']").each(function() {
			$(this).attr("rel", $(this).data("rel"));
		});
	}


	/* -----------------------------------------
	 SoundManager2 Init
	 ----------------------------------------- */
	soundManager.setup({
		url: ThemeOption.swfPath
	});





});

// Hyphenator
Hyphenator.run();

// Self-hosted Audio Player
function setupjw(playerID,track) {
	jwplayer(playerID).setup({
		autostart: false,
		file: track,
		flashplayer: ThemeOption.theme_url + "/jwplayer/jwplayer.flash.swf",
		width: "100%",
		height:"65",
		events: {
			onPlay: function(event)
				{
					// Grab only the self hosted ones
					var tracklisting_items = jQuery('[id^=custom_player]').length;
					
					// Grab the ID of the last one.
					var tracklisting_items_last = jQuery('[id^=custom_player]').last().attr('id').replace('custom_',''); 
					
					// For some reason when 1st or last track is triggered the player id is fucked up. Let's fix this.
					if ((tracklisting_items == 1) || (playerID == tracklisting_items_last)) {
						idPlayer= "player1";
					}
					
					// If the player Ids are not the same it means that we are initiating a new track. Stop the current one.
					if(this.id != idPlayer)
					{
						jwplayer(idPlayer).stop();
						idPlayer = this.id;
					}
			}
		}
	});
}

// Self-hosted Video Player
function setupvjw(playerID,track) {
	jwplayer(playerID).setup({
		'autostart': 	false,
		'file': 		track,
		'height':		'380',
		'width'	:		'500',
		'flashplayer': 	ThemeOption.theme_url + '/jwplayer/jwplayer.flash.swf',
		'controlbar':	'bottom',
		'id'			: playerID
	});
}