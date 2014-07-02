/*
 * Skrypt wyświetlający okienko z informacją o wykorzystaniu ciasteczek (cookies)
 */

function WHCreateCookie(name, value, days) {
    var date = new Date();
    date.setTime(date.getTime() + (days*24*60*60*1000));
    var expires = "; expires=" + date.toGMTString();
	document.cookie = name+"="+value+expires+"; path=/";
}
function WHReadCookie(name) {
	var nameEQ = name + "=";
	var ca = document.cookie.split(';');
	for(var i=0; i < ca.length; i++) {
		var c = ca[i];
		while (c.charAt(0) == ' ') c = c.substring(1, c.length);
		if (c.indexOf(nameEQ) == 0) return c.substring(nameEQ.length, c.length);
	}
	return null;
}

window.onload = WHCheckCookies;

function WHCheckCookies() {
    if(WHReadCookie('cookies_accepted') != 'T') {
        var message_container = document.createElement('div');
        message_container.id = 'cookies-message-container';
        var html_code = '<div id="cookies-message" style="padding: 10px 0px; font-size: 12px; line-height: 16px; border-top: 1px solid #cccccc; text-align: center; position: fixed; bottom: 0px; background-color: #ffffff; width: 100%; z-index: 999;"><div style="width: 970px; margin: 0px auto;">Gomez uses cookies to give you the best experience. With the cookies we allow you to make full use of the online shopping and personalized features available on our website. View our <a href="http://http://gomez.pl/en/polityka_prywatnosci/" style="color: rgb(167,15,33);">Privacy Policy</a> to learn more about this.<a href="javascript:WHCloseCookiesWindow();" id="accept-cookies-checkbox" name="accept-cookies" style="background-color: rgb(167,15,33); padding: 2px 8px; color: #FFF; border-radius: 2px; -moz-border-radius: 2px; -webkit-border-radius: 2px; display: inline-block; margin-left: 10px; text-decoration: none; cursor: pointer;">Close</a></div>';
        message_container.innerHTML = html_code;
        document.body.appendChild(message_container);
    }
}

function WHCloseCookiesWindow() {
    WHCreateCookie('cookies_accepted', 'T', 365);
    document.getElementById('cookies-message-container').removeChild(document.getElementById('cookies-message'));
}
