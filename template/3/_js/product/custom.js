var pozx = 0;
var pozy = 0;

function tooltip_go()
{
    $('.with-tooltip').tooltip({
		track: true,
		delay: 0,
		showURL: false,
		showBody: "|",
		extraClass: "tooltip",
		fixPNG: true
	});
}

function tooltip_go2()
{
    $('.recommended-product').mouseover(function (){
            //$('#tt'+this.id).css({'display':'block','top':pozy+'px', 'left':pozx+'px'});
            //$('#tt'+this.id).css({'display':'block'});
            $(this).find('.recommended-product-tt').css({'display':'block'});
        });

        
        $('.recommended-product').mouseout(function (){
            //$('#tt'+this.id).css({'display':'none'});
            $(this).find('.recommended-product-tt').css({'display':'none'});
        });

}

function mycarousel_initCallback(carousel)
{
    // Disable autoscrolling if the user clicks the prev or next button.
    carousel.buttonNext.bind('click', function() {
        carousel.startAuto(0);
        tooltip_go2();
    });



    carousel.buttonPrev.bind('click', function() {
        carousel.startAuto(0);
        tooltip_go2();
    });

    // Pause autoscrolling if the user moves with the cursor over the clip.
    carousel.clip.hover(function() {
        carousel.stopAuto();
        tooltip_go2();
        
        
//        //alert('qweqwe');
//        $(document).mousemove(function(e){
//        //alert(e.pageX +', '+ e.pageY);
//        $('.recommended-product').mouseover(function (){
//            //alert(this.id+''+e.pageX+'--'+e.pageY);
//            $('#tt'+this.id).css({'display':'block','top':e.pageY+'px','left':e.pageX+'px'});
//        });
//        
//        $('.recommended-product').mouseout(function (){
//            //alert(this.id+''+e.pageX+'--'+e.pageY);
//            $('#tt'+this.id).css({'display':'none','top':e.pageY+'px','left':e.pageX+'px'});
//        });
//        
//   }); 
/*        
        jQuery(document).ready(function(){
   $(document).mousemove(function(e){
      $('#status').html(e.pageX +', '+ e.pageY);
   }); 
})
  */      
        
        
        
    }, function() {
        carousel.startAuto();
        tooltip_go2();
    });
};

function callback2()
{
    tooltip_go2();
        
        //alert('qwe');
    //tooltip_go();
    
}


$(function(){
	// brands caousel
	$('#brands-carousel').jcarousel({auto: 5, wrap: 'circular', initCallback: mycarousel_initCallback});
	// recommended caousel
	$('#product-recommended-list-carousel').jcarousel({auto: 15, wrap: 'circular', initCallback: mycarousel_initCallback, itemVisibleInCallback: {onBeforeAnimation: callback2, onAfterAnimation: callback2},itemLoadCallback: {onBeforeAnimation: callback2, onAfterAnimation: callback2}});



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
        
        $( ".menuwew" ).accordion();
});


function setcssimg()
{}



$(document).ready(function() {

	$('.jqzoom').jqzoom({
		zoomType: 'reverse',
                lens:true,
		preloadImages: false,
		preloadText: "Ładowanie…",
		alwaysOn:false,
		zoomWidth: 433, //400 
		zoomHeight: 579, //250
                title: false
	});


        $('.fileimgmini').click(function(){
            //alert($('#prewphoto').width());
            //setTimeOut();
        });
        
        $(document).mousemove(function(e){
        //alert(e.pageX +', '+ e.pageY);
            pozx = e.pageX;
            pozy = e.pagey;
        });

});

function tabsizer()
{
    //alert('tutaj będzie się otwierać tabela rozmiarów');
    $('.tabs').tabs();
    $('#tabsizer').dialog({modal: true, width: 400, height: 300});
}

