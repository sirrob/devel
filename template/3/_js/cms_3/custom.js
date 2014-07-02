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
	//$('#brands-carousel').jcarousel({auto: 5, wrap: 'circular', initCallback: mycarousel_initCallback});
        $("#gc-container *").click(function(){
            if(gc==1)
            $('#gc-container').fadeTo('slow', 1);
            $('#gc-container-log').hide();
            gc=0;
        });
        
        $('.scroll-pane').jScrollPane({
		verticalGutter: 0,
		horizontalGutter: 0,
		showArrows: false,
		verticalArrowPositions: 'os',
		horizontalArrowPositions: 'os'
	});
});


function m_formy2()
{
    m_formy();
    $('p24_mtd341d').attr('size',1);
    NFFix();
    //alert($('p24_mtd341d').attr('size',1));
}
var gc = 0;
function gomezclublog()
{
    //$('#gc-container').hide();
    //$('#gc-container').opac
    $('#gc-container').fadeTo('slow', 0.5);
    $('#gc-container-log').show();
    gc = 1;
}