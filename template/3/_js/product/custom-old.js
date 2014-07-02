$(function(){

	$('.with-tooltip').tooltip({
		track: true,
		delay: 0,
		showURL: false,
		showBody: "|",
		extraClass: "tooltip",
		fixPNG: true
	});
	
});

$(document).ready(function() {

	$('.jqzoom').jqzoom({
		zoomType: 'reverse',
    lens:true,
		preloadImages: false,
		preloadText: "Ładowanie…",
		alwaysOn:false,
		zoomWidth: 431, //400 
		zoomHeight: 578 //250
	});

});