$(function() {
	$(".ic_container").capslide({
		caption_color	: '#373737',
		caption_bgcolor	: '#cccccc',
		overlay_bgcolor : '#000000',
		showcaption	    : false
	});
	
	
	$(".ic_container").hover(
		function() {
			$(this).find('.ic_title').stop().animate({"opacity": "0"}, "slow");
		},
		function() {
			$(this).find('.ic_title').stop().animate({"opacity": "1"}, "slow");
		}
	);
	
});