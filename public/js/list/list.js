/*
 *  ListNav - A Simple Content List
 *  Copyright 2010 Monjurul Dolon, http://mdolon.com/
 *  Released under the MIT, BSD, and GPL Licenses.
 *  More information: http://devgrow.com/listnav
 */
$.fn.listNav = function(options) {
	var defaults = { items: ["a","b","c","d","e","f","g","h","i","j","k","l","m","n","o","p","q","r","s","t","u","v","w","x","y","z"], debug: false, height: null, arrows: true};
	var opts = $.extend(defaults, options); var o = $.meta ? $.extend({}, opts, $$.data()) : opts; var list = $(this); $(list).addClass('list_list');
	$('.list-content li:first', list).addClass('selected');
	var height = 300;
	if(o.height) height = o.height;
	$('.list-content', list).css('height',height);
//	if(o.debug) $(list).append('<div id="debug">Scroll Offset: <span>0</span></div>');
//	$('.list-nav a', list).mouseover(function(event){
//		var target = $(this).attr('alt');
//		var cOffset = $('.list-content', list).offset().top;
//		var tOffset = $('.list-content '+target, list).offset().top;
//		var height = $('.list-nav', list).height(); if(o.height) height = o.height;
//		var pScroll = (tOffset - cOffset) - height/8;
//		$('.list-content li', list).removeClass('selected');
//		$(target).addClass('selected');
//		$('.list-content', list).stop().animate({scrollTop: '+=' + pScroll + 'px'});
//		if(o.debug) $('#debug span', list).html(tOffset);
//	});
	if(o.arrows){
		$(list).prepend('<div class="slide-up end"><span class="arrow up"></span></div>');
		$(list).append('<div class="slide-down"><span class="arrow down"></span></div>');
	}

    $('.list-content ul li span', list).click(function(){
        $(list).find('.active').removeClass('active');
        $(this).addClass('active')
    });
};