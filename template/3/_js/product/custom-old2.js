function mycarousel_initCallback(carousel)
{
    // Disable autoscrolling if the user clicks the prev or next button.
    carousel.buttonNext.bind('click', function() {
        carousel.startAuto(0);
    });

    carousel.buttonPrev.bind('click', function() {
        carousel.startAuto(0);
    });

    // Pause autoscrolling if the user moves with the cursor over the clip.
    carousel.clip.hover(function() {
        carousel.stopAuto();
    }, function() {
        carousel.startAuto();
    });
};

$(function(){
	// brands caousel
	$('#brands-carousel').jcarousel({ auto: 5, wrap: 'circular', initCallback: mycarousel_initCallback });
	// recommended caousel
	$('#product-recommended-list-carousel').jcarousel({ auto: 5, wrap: 'circular', initCallback: mycarousel_initCallback });


	$('.with-tooltip').tooltip({
		track: true,
		delay: 0,
		showURL: false,
		showBody: "|",
		extraClass: "tooltip",
		fixPNG: true
	});
	
	// scrolls for contents
	$('.scroll-pane').jScrollPane({
		verticalGutter: 0,
		horizontalGutter: 0,
		showArrows: false,
		verticalArrowPositions: 'os',
		horizontalArrowPositions: 'os'
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