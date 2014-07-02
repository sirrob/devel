var imgTable = new Array();
var hrefTable = new Array();
var thumbTable = new Array();
var currIndex = 0;
var imageCount = -1;
var plusImage;
var leftArrowImage;
var rightArrowImage;
var langid = 1;

function StartGalery(lang) {

	// change language
	if (lang == 'en') {
		langid = 2;
	}

	getImages();
	setStartImage();
	setThumbs();
	setOnImageClick();
	setArrows();
	setThumbsCaption();
}


function getImages() {
	$('.Image').each(function( index ) {
		imgTable.push($(this).children('img').attr('src'));
		hrefTable.push($(this).children('.Link').text());
		thumbTable.push($(this).children('.Thumbnail').children('img').attr('src'));
		imageCount++;
	});
	plusImage = $('.Settings > .PlusImage').attr('src');
	leftArrowImage = $('.Settings > .LeftArrowImage').attr('src');
	rightArrowImage = $('.Settings > .RightArrowImage').attr('src');
}

function setStartImage() {
	$('.MainImage').append($('<a href=""><img class="BigImg" src=""></a>'));
	setImage(0);
}

function setImage(id) {
	var imgSrc = imgTable[id];
	var imgHref = hrefTable[id];
	$('.MainImage > a').attr('href', imgHref);
	$('.MainImage > a > img').attr('src', imgSrc);
	currIndex = id;
	clearPluses();
	setPluses();	
}

function setThumbs() {
	$('.ThumbImages').html('');
	var j=0;
	for(var i=currIndex; i<currIndex + 5; i++)
	{
		if(i<=imageCount){

			if(j!=4){
				var topCaption = $('#' + i + '.Image').children('.TopCaption').html();
				var botCaption = $('#' + i 	+ '.Image').children('.BotCaption').html();
				$('.ThumbImages').append($('<div class="ThumbImage" id="' + i + '"><div style="left:' + j * 199 + 'px"class="CaptionBot">' + botCaption + '</div><div style="left:' + j * 199 + 'px"class="CaptionTop">'+ topCaption +'</div><img src="' + thumbTable[i] +'"/></div>'));
			} else {
				var topCaption = $('#' + i + '.Image').children('.TopCaption').html();
				var botCaption = $('#' + i 	+ '.Image').children('.BotCaption').html();
				$('.ThumbImages').append($('<div class="ThumbImage Last" id="' + i + '"><div style="left:' + j * 199 + 'px"class="CaptionBot">' + botCaption + '</div><div style="left:' + j * 199 + 'px"class="CaptionTop">'+ topCaption +'</div><img src="' + thumbTable[i] +'"/></div>'));

			}
		} else if(imageCount >= 4){			
			if(j!=4){
				var topCaption = $('#' + parseInt(i - imageCount - 1) + '.Image').children('.TopCaption').html();
				var botCaption = $('#' + parseInt(i - imageCount - 1) + '.Image').children('.BotCaption').html();
				$('.ThumbImages').append($('<div class="ThumbImage" id="' + parseInt(i - imageCount - 1) + '"><div style="left:' + j * 199 + 'px"class="CaptionBot">' + botCaption + '</div><div style="left:' + j * 199 + 'px"class="CaptionTop">'+ topCaption +'</div><img src="' + thumbTable[parseInt(i - imageCount - 1)] +'"/></div>'));
			} else {
				var topCaption = $('#' + parseInt(i - imageCount - 1) + '.Image').children('.TopCaption').html();
				var botCaption = $('#' + parseInt(i - imageCount - 1) 	+ '.Image').children('.BotCaption').html();
				$('.ThumbImages').append($('<div class="ThumbImage Last" id="' + parseInt(i - imageCount - 1) + '"><div style="left:' + j * 199 + 'px"class="CaptionBot">' + botCaption + '</div><div style="left:' + j * 199 + 'px"class="CaptionTop">'+ topCaption +'</div><img src="' + thumbTable[parseInt(i - imageCount - 1)] +'"/></div>'));	
			}				
		} 
		j++;
	}
	setOnImageClick();
}

function setOnImageClick() {
	$('.ThumbImage').click (function() {
		if(currIndex != parseInt($(this).attr('id')))
			setImage(parseInt($(this).attr('id')));
		setThumbs();
		setThumbsCaption();
	});
}

function setArrows() {
	
	$('.MainImage').append($('<div class="LeftArrow"><img src="' + leftArrowImage + '"/></div>'));
	$('.MainImage').append($('<div class="RightArrow"><img src="' + rightArrowImage + '"/></div>'));
	
	$('.LeftArrow').click (function() {
		if(currIndex==0)
		{
			setImage(imageCount);
			setThumbs();
			setThumbsCaption();
		} else {
			setImage(currIndex - 1);
			setThumbs();
			setThumbsCaption();
		}
	});
	$('.RightArrow').click (function() {
		if(currIndex==imageCount)
		{
			setImage(0);
			setThumbs();
			setThumbsCaption();
		} else {
			setImage(currIndex + 1);
			setThumbs();
			setThumbsCaption();
		}
	});
	
	$('.MainImage').hover(
		function () {
			$(this).children('.LeftArrow').stop(true, true).fadeIn(0);
			$(this).children('.RightArrow').stop(true, true).fadeIn(0);
		},
		function () {
			$(this).children('.LeftArrow').stop(true, true).fadeOut(0);
			$(this).children('.RightArrow').stop(true, true).fadeOut(0);		   
		});
}

function setPluses() {
	var id = currIndex;

	$('#' + id + '.Image').each(function(index ) {
		$(this).children('.PlusContainer').children('.Plus').each(function(index2){
			var x = $(this).children('.CordX').text();
			var y = $(this).children('.CordY').text();
			var PId = $(this).children('.Tittle').children('.ProductID').text();
			var x_tooltip = parseInt(x) + 25;
			var y_tooltip = parseInt(y) + 25;

			$('.MainPluses').append($('<a href="" class="tip" id="tip' + index2 + '"><img style="position:absolute; left:' + x + 'px; top:' + y + 'px" src="' + 	plusImage + 
				'"><span class="MainPlusesContent" id="tipspan' + index2 + '" style="left:' + x + 'px; top:' + y_tooltip+ 'px"></span></a>'));

			$.ajax({
				url: "http://gomez.pl/template/3/ajax_php/looks/getProduct.php?p_id=" + PId + "&langid="+langid,
				beforeSend: function ( xhr ) {
					xhr.overrideMimeType("text/plain; charset=x-user-defined");
				}
			}).done(function ( data ) {
				obj = JSON.parse(data);
				$('#tip' + index2 + ' .MainPlusesContent').append($(obj.tooltip));
				$('#tip' + index2).attr('href', obj.link);
			});			

		});

});
}

function setThumbsCaption()
{
	$('.ThumbImage').hover(
		function () {
			$(this).children('.CaptionBot').stop(true, true).slideDown(300);
			$(this).children('.CaptionTop').stop(true, true).fadeOut(300);
		},
		function () {
			$(this).children('.CaptionBot').stop(true, true).slideUp(300);   
			$(this).children('.CaptionTop').stop(true, true).fadeIn(300);		   
		});
}

function clearPluses()
{
	$('.MainPluses').html("");
}
