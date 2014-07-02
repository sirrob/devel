<!doctype html>
<!--[if lt IE 7]> <html class="no-js ie6 oldie" lang="pl"> <![endif]-->
<!--[if IE 7]>    <html class="no-js ie7 oldie" lang="pl"> <![endif]-->
<!--[if IE 8]>    <html class="no-js ie8 oldie" lang="pl"> <![endif]-->
<!--[if gt IE 8]> <html class="no-js" lang="pl" dir="ltr" xmlns="http://www.w3.org/1999/xhtml"> <![endif]-->
<head>
    <!-- monobrands -->
    <meta charset="utf-8"/>
    <title>{TITLE}</title>
    <meta name="robots" content="index,follow,all"/>
    <meta name="revisit-after" content="2 days"/>
    <meta name="classification" content="global,all"/>
    <meta name="description" content="{DESCRIPTION}" />
    <meta name="keywords" content="{KEYWORDS}" />
    <meta name="author" content="Michał Bzowy, Maciej Szczachor, Marcin Borowski" />
    <meta http-equiv="pragma" content="no-cache" />    
    <meta name="SKYPE_TOOLBAR" content="SKYPE_TOOLBAR_PARSER_COMPATIBLE" />
    <meta name="viewport" content="initial-scale=1.0 maximum-scale=1.0 user-scalable=no" />
    <meta name="HandheldFriendly" content="True"/> 


    <link href="http://gomez.pl/template/3/css/monobrands/tommy-hilfiger/screen.css" media="screen, projection" rel="stylesheet" type="text/css" /> 
    <link href="http://gomez.pl/template/3/css/monobrands/tommy-hilfiger/print.css" media="print" rel="stylesheet" type="text/css" />

    <link rel="stylesheet" type="text/css" media="all" href="http://gomez.pl/template/3/css/common/topnav.css" />

    <script type="text/javascript" src="http://gomez.pl/template/3/js/jquery-1.8.0.min.js"></script>
    <script type="text/javascript" src="http://gomez.pl/template/3/js/jquery.mousewheel-3.0.6.pack.js"></script>


    <link rel="shortcut icon" href="/favicon.ico" />
    <link rel="apple-touch-icon" href="/apple-touch-icon.png" />

    <!--[if IE]> <script src="http://html5shiv.googlecode.com/svn/trunk/html5.js"></script> <![endif]-->
    <!--[if lt IE 7]> <script src="http://ie7-js.googlecode.com/svn/version/2.1(beta4)/IE7.js"></script> <![endif]-->
    <!--[if lt IE 8]> <script src="http://ie7-js.googlecode.com/svn/version/2.1(beta4)/IE8.js"></script> <![endif]-->
    <!--[if lt IE 9]> <script src="http://ie7-js.googlecode.com/svn/version/2.1(beta4)/IE9.js"></script> <![endif]-->

    <!-- Modernizr -->
    <!--[if !IE 6]><!-->
    <script src="http://gomez.pl/template/3/js/modernizr.custom.js"></script>
    <!--<![endif]-->

    <!-- Fancybox: form lightbox -->
    <!--[if !IE 6]><!-->
    <!-- // <script type="text/javascript" src="http://gomez.pl/template/3/js/jquery.fancybox.pack.js"></script> -->
    <!-- <link rel="stylesheet" href="http://gomez.pl/template/3/css/jquery.fancybox.css" type="text/css" media="screen" /> -->
    <!--<![endif]-->

    <script src="http://gomez.pl/template/3/js/common/jquery.hoverIntent.minified.js" type="text/javascript" language="javascript" charset="utf-8"></script>

    <!-- jqtransform -->
    <link rel="stylesheet" href="http://gomez.pl/template/3/css/common/gray.jqtransform.css" type="text/css" media="all" />
    <script type="text/javascript" src="http://gomez.pl/template/3/js/common/jquery.jqtransform.js" ></script>

    <!--[if !IE 6]><!-->
    <script src="http://gomez.pl/template/3/js/tommy-hilfiger.js"></script>
    <!--<![endif]-->
<script type="text/javascript">
  var _gaq = _gaq || [];
  _gaq.push(['_setAccount', 'UA-18509018-1']);
  _gaq.push(['_trackPageview']);

  (function() {
    var ga = document.createElement('script'); ga.type = 'text/javascript'; ga.async = true;
    ga.src = ('https:' == document.location.protocol ? 'https://ssl' : 'http://www') + '.google-analytics.com/ga.js';
    var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(ga, s);
  })();

</script>
<script type="text/javascript">
{JAVASCRIPT}
  </script>

{HEAD}

</head>
<body>
    <div id="fb-root"></div>
    <script type="text/javascript">
        (function(d, s, id) {
            var js, fjs = d.getElementsByTagName(s)[0];
            if (d.getElementById(id)) {return;}
            js = d.createElement(s); js.id = id;
            js.src = "//connect.facebook.net/pl_PL/all.js#xfbml=1";
            fjs.parentNode.insertBefore(js, fjs);
	}(document, 'script', 'facebook-jssdk'));
    </script>

    <div id="top">
        <div class="wrapper">
            <div id="top-left">
                <a href="http://gomez.pl" target="_self">gomez.pl</a>
                <div id="fb-like-button" class="fb-like" data-href="http://www.facebook.com/profile.php?id=170840899634520" data-send="false" data-layout="button_count" data-width="50" data-show-faces="false" data-font="tahoma"></div>
            </div>

            <div id="top-right">        
                {USER_MINI}
            </div>
            <div class="clearfix"></div>
        </div>
    </div>

    <div id="container" class="wrapper">
        <header id="header">
            <div id="logotype">
                <a href="http://gomez.pl/" target="_self"><img src="http://gomez.pl/template/3/images/spacer.gif" width="184" height="69" alt="Gomez" /></a>
            </div>

            <div id="top-cart">
                <div id="header-breadcrumb">
                    <a href="{URL}{LANG}/kontakt/">{T_KONTAKT}</a> / <a href="{URL}{LANG}/regulamin/">{T_REGULAMIN}</a> / <a href="{URL}{LANG}/newsletter/">{T_NEWSLETTER}</a>
		</div>
                <div id="cart-box">
                    <div id="cart-content" class="gray-bordered">
                        <div id="cart-header">{T_ZAWARTOSC_KOSZYKA}</div>
			<div>{KOSZYK_MINI}</div>                        
                    </div>
		</div>
            </div>

            <div class="clearfix"></div>
            <div id="main-bar">
                <nav id="main-menu">
                    <ul id="topnav">
                            {$mainmenu}
                    </ul>			
                </nav>

                <div id="recomend-container">
                    <form id="recomend-form" action="http://gomez.pl/pl/wyszukiwarka/" method="get" class="jqTransform">
                        <div id="recomend-email-input-container">
                            <input type="text" id="recomend-email-input" name="sna" value="Szukaj..." onblur="if(this.value=='')this.value='Szukaj...'" onfocus="if(this.value=='Szukaj...')this.value=''" />
                            <input type="image" src="http://gomez.pl/template/3/images/but_search.jpg" alt="gomez_club_icon" class="butsearch" />
			</div>
			<input type="hidden" name="a" value="s" />
                    </form>
                </div>
                <div class="clearfix"></div>
            </div>
        </header>

        <div id="content">
            <!-- początek zawartości -->
            {STRONA}
            <!-- koniec zawartości -->
        </div>

        <footer id="footer" >
            <div id="bottom-buttons">
		<a id="first-bottom-button" class="bottom-button" href="{URL}{LANG}/zwroty_wymiany/"><span>{T_ZWROTY_WYMIANY}</span></a>
		<a id="second-bottom-button" class="bottom-button" href="{URL}{LANG}/bezpieczne_zakupy/"><span>{T_BEZPIECZNE_ZAKUPY}</span></a>
		<a id="third-bottom-button" class="bottom-button" href="{URL}{LANG}/kontakt/"><span>{T_PERSONALNY_KONTAKT}</span></a>
		<a id="fourth-bottom-button" class="bottom-button" href="{URL}{LANG}/brands/"><span>{T_TOP_BRANDS}</span></a>
		<a id="fiveth-bottom-button" class="bottom-button" href="{URL}{LANG}/przesylka_dhl_gratis/"><span>{T_PRZESYLKA_DHL_GRATIS}</span></a>
		<a id="sixth-bottom-button" class="bottom-button" href="{URL}{LANG}/zamowienia_24h/"><span> {T_ZAMOWIENIA_24H}</span></a>
            </div>

            <div class="clearfix"></div>					

            <div id="footer-navigation">
		<div class="navigation-box">
                    <h3 class="footer-header">{menubottomtitle1}</h3>
                    <ul>
                        {menubottomlist1}
                    </ul>
		</div>
		<div class="navigation-box">
                    <h3 class="footer-header">{menubottomtitle2}</h3>
                    <ul>
                        {menubottomlist2}
                    </ul>
		</div>
		<div class="navigation-box">
                    <h3 class="footer-header">{menubottomtitle3}</h3>
                    <ul>
                        {menubottomlist3}
			<li><a href="{URL}{LANG}/brands/">{T_WIECEJ2}</a></li>
                    </ul>
		</div>
		<div class="navigation-box">
                    <h3 class="footer-header">{menubottomtitle4}</h3>
                    <ul>
                        {menubottomlist4}
                    </ul>
		</div>
		<div class="navigation-box no-border">
                    <h3 class="footer-header">{menubottomtitle5}</h3>
                    <ul>
                        {menubottomlist5}
                    </ul>
		</div>
		<div class="clearfix"></div>
		<div id="end">
                    <div id="copyright">Copyright © 2010-2011 GOMEZ.pl. Created by <a href="http://freshstudio.pl" target="_blank">Fresh Studio</a></div>
                    <div id="footer-links">
                        <a href="{URL}{LANG}/kontakt/">{T_KONTAKT}</a> / <a href="{URL}{LANG}/regulamin/">{T_REGULAMIN}</a> / <a href="{URL}{LANG}/polityka_prywatnosci/">{T_POLITYKA_PRYWATNOSCI}</a>
                    </div>
                    <div class="clearfix"></div>
		</div>
            </div>
        </footer>
    </div>

<script type="text/javascript">
$(document).ready(function() {
    function megaHoverOver(){
        $(this).find(".sub").stop().fadeTo('fast', 1).show();
    }

    function megaHoverOut(){
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

</script>

<script type="text/javascript">
var gaJsHost = (("https:" == document.location.protocol) ? "https://ssl.": "http://www.");
document.write(unescape("%3Cscript src='" + gaJsHost + "google-analytics.com/ga.js' type='text/javascript'%3E%3C/script%3E"));
</script>
<script type="text/javascript">
var pageTracker = _gat._getTracker("UA-4863156-1");
pageTracker._initData();
pageTracker._trackPageview();
</script>


<!-- Google Code for Aktywacja Remarketing List -->
<script type="text/javascript">
	/* <![CDATA[ */
	var google_conversion_id = 1011367281;
	var google_conversion_language = "en";
	var google_conversion_format = "3";
	var google_conversion_color = "ffffff";
	var google_conversion_label = "B8W8CNfrjwMQ8fqg4gM";
	var google_conversion_value = 0;
	/* ]]> */
</script>

<script type="text/javascript" src="http://www.googleadservices.com/pagead/conversion.js"></script>
<noscript>
	<div style="display:inline;">
		<img height="1" width="1" style="border-style:none;" alt="" src="http://www.googleadservices.com/pagead/conversion/1011367281/?label=B8W8CNfrjwMQ8fqg4gM&amp;guid=ON&amp;script=0"/>
	</div>
</noscript>


{DEBUG}        

</body>
</html>