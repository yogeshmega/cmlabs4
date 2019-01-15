(function(){
	var hh = 0;
	
	function initHeightSlider(){
		var slider = jQuery('#homeSlider'),
			wh = slider.attr('data-height');
		
		if(wh === undefined || wh == "full" || isNaN(wh)){
			wh = window.innerHeight ? window.innerHeight : jQuery(window).height();    
		}
		
		var a  = slider.find('.carousel-inner .item'),
			ns = slider.find('.next-subpage'),
			nsspace = 15,
			customspace = 20;
		
		a.each(function(){
			jQuery(this).addClass('active');
			var target = $(this).find('.post-content');
			
		   var ah = target.height() + parseFloat(target.css('padding-top')) + parseFloat(target.css('padding-bottom')),
				space = (wh - ah) / 2;
			
			
		   jQuery(this).find('.bgcontent').height(ah);
			
			if(ns.length > 0){
				nsspace = ns.height() + parseFloat(ns.css('bottom')) + 30;        
			}
			
			if(ah+space+nsspace > wh){
				jQuery(this).height(ah+hh+nsspace);
				jQuery(this).removeClass('flex-container');
				target.css({'margin-top' : customspace+hh+'px'});
				//ns.css('bottom', '15px');
			}
			else{
				jQuery(this).height(wh);
				jQuery(this).addClass('flex-container');
				target.css({'margin-top' : 0});
				//ns.css('bottom', '');    
			}
			
			jQuery(this).removeClass('active');    
		});
		
		a.eq(0).addClass('active');    
	}
	
	jQuery(document).ready(function(){
		hh = jQuery('#header').height();
		
		initHeightSlider();
		
		jQuery('.carousel').carousel({
			interval: 5000,
			pause: false
		});
		
		jQuery('#homeSlider .next-subpage').click(function(){
			var target = $(this).attr('href');
			jQuery.scrollTo(target, 500);
			return false;
		})  
	})
	
	jQuery(window).resize(function(){
		initHeightSlider();   
	})
	
	/*
	jQuery(window).on("orientationchange", function(){
		initHeightSlider();   
	}) 
	*/
	
	window.addEventListener('orientationchange', initHeightSlider);
	
	/* if (navigator.userAgent.match(/iPad;.*CPU.*OS 7_\d/i) && window.innerHeight != document.documentElement.clientHeight) {
		var fixViewportHeight = function() {
			document.documentElement.style.height = window.innerHeight + "px";
			if (document.body.scrollTop !== 0) {
				window.scrollTo(0, 0);
			}
		}.bind(this);

		window.addEventListener("scroll", fixViewportHeight, false);
		window.addEventListener("orientationchange", fixViewportHeight, false);
		fixViewportHeight();

		document.body.style.webkitTransform = "translate3d(0,0,0)";
	} */
	
})()