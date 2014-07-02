function userminilogin() {
	$('#login-form').submit();
}

$(function(){
	$('form.jqTransform').jqTransform();
	$('form.niceform').jqTransform();
});

$(document).ready(function () {
	$('.page').fadeIn(2000);	


	function megaHoverOver() {
		$(this).find(".sub").stop().fadeTo('fast', 1).show();
	}

	function megaHoverOut() {
		$(this).find(".sub").stop().fadeTo('fast', 0, function() {
			$(this).hide();
		});
	}

	var config = {
		sensitivity: 2, // number = sensitivity threshold (must be 1 or higher)
		interval: 10, // number = milliseconds for onMouseOver polling interval
		over: megaHoverOver, // function = onMouseOver callback (REQUIRED)
		timeout: 500, // number = milliseconds delay before onMouseOut
		out: megaHoverOut // function = onMouseOut callback (REQUIRED)
	};

	$("ul#topnav li .sub").css({'opacity':'0'});
	$("ul#topnav li").hoverIntent(config);

});

