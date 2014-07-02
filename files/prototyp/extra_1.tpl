<!doctype html>
<!--[if lt IE 7]> <html class="no-js ie6 oldie" lang="pl"> <![endif]-->
<!--[if IE 7]>    <html class="no-js ie7 oldie" lang="pl"> <![endif]-->
<!--[if IE 8]>    <html class="no-js ie8 oldie" lang="pl"> <![endif]-->
<!--[if gt IE 8]> <html class="no-js" lang="pl" dir="ltr" xmlns="http://www.w3.org/1999/xhtml"> <![endif]-->
<head>
    <!-- monobrands -->
    <meta charset="utf-8"/>
    <title>{TITLE}</title>
    {METATAGS}
    <meta name="revisit-after" content="2 days"/>
    <meta name="classification" content="global,all"/>
    <meta name="description" content="{DESCRIPTION}" />
    <meta name="keywords" content="{KEYWORDS}" />
    <meta name="author" content="Michał Bzowy, Maciej Szczachor, Marcin Borowski" />
    <meta http-equiv="pragma" content="no-cache" />    
    <meta name="SKYPE_TOOLBAR" content="SKYPE_TOOLBAR_PARSER_COMPATIBLE" />
    <meta name="viewport" content="initial-scale=1.0 maximum-scale=1.0 user-scalable=no" />
    <meta name="HandheldFriendly" content="True"/> 

    <link href="http://gomez.pl/template/3/css/monobrands/screen.css" media="all" rel="stylesheet" type="text/css" /> 
    <link href="http://gomez.pl/template/3/css/monobrands/print.css" media="print" rel="stylesheet" type="text/css" />
    <link href="http://gomez.pl/template/3/css/common/topnav.css" media="all" rel="stylesheet" type="text/css" />

    <link rel="stylesheet" href="{URL}template/{TEMPLATE}/css/fc-webicons.css">

    <script type="text/javascript" src="http://gomez.pl/template/3/js/jquery-1.8.0.min.js"></script>
    <script src="http://gomez.pl/template/3/js/common/jquery.cookie.js" type="text/javascript" language="javascript" charset="utf-8"></script>    
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
                <a class="fc-webicon facebook small grayed" target="_blank" href="http://www.facebook.com/profile.php?id=170840899634520">Facebook</a>
                <a class="fc-webicon pinterest small grayed" target="_blank" href="http://pinterest.com/gomezstore/">Pinterest</a>
                <a class="fc-webicon twitter small grayed" target="_blank" href="https://twitter.com/GomezStore">Twitter</a>
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
                            <input type="text" id="recomend-email-input" name="sna" value="{T_SZUKAJ_Value}" onblur="if(this.value=='')this.value='{T_SZUKAJ_Value}'" onfocus="if(this.value=='{T_SZUKAJ_Value}')this.value=''" />
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
var gaJsHost = (("https:" == document.location.protocol) ? "https://ssl.": "http://www.");
document.write(unescape("%3Cscript src='" + gaJsHost + "google-analytics.com/ga.js' type='text/javascript'%3E%3C/script%3E"));
</script>
<script type="text/javascript">
var pageTracker = _gat._getTracker("UA-4863156-1");
pageTracker._initData();
pageTracker._trackPageview();
</script>


<script>
    // Modernizr with SVG detection required. http://modernizr.com
    ;window.Modernizr=function(a,b,c){function B(a){j.cssText=a}function C(a,b){return B(m.join(a+";")+(b||""))}function D(a,b){return typeof a===b}function E(a,b){return!!~(""+a).indexOf(b)}function F(a,b){for(var d in a)if(j[a[d]]!==c)return b=="pfx"?a[d]:!0;return!1}function G(a,b,d){for(var e in a){var f=b[a[e]];if(f!==c)return d===!1?a[e]:D(f,"function")?f.bind(d||b):f}return!1}function H(a,b,c){var d=a.charAt(0).toUpperCase()+a.substr(1),e=(a+" "+o.join(d+" ")+d).split(" ");return D(b,"string")||D(b,"undefined")?F(e,b):(e=(a+" "+p.join(d+" ")+d).split(" "),G(e,b,c))}var d="2.5.3",e={},f=!0,g=b.documentElement,h="modernizr",i=b.createElement(h),j=i.style,k,l={}.toString,m=" -webkit- -moz- -o- -ms- ".split(" "),n="Webkit Moz O ms",o=n.split(" "),p=n.toLowerCase().split(" "),q={svg:"http://www.w3.org/2000/svg"},r={},s={},t={},u=[],v=u.slice,w,x=function(a,c,d,e){var f,i,j,k=b.createElement("div"),l=b.body,m=l?l:b.createElement("body");if(parseInt(d,10))while(d--)j=b.createElement("div"),j.id=e?e[d]:h+(d+1),k.appendChild(j);return f=["&#173;","<style>",a,"</style>"].join(""),k.id=h,m.innerHTML+=f,m.appendChild(k),l||(m.style.background="",g.appendChild(m)),i=c(k,a),l?k.parentNode.removeChild(k):m.parentNode.removeChild(m),!!i},y=function(b){var c=a.matchMedia||a.msMatchMedia;if(c)return c(b).matches;var d;return x("@media "+b+" { #"+h+" { position: absolute; } }",function(b){d=(a.getComputedStyle?getComputedStyle(b,null):b.currentStyle)["position"]=="absolute"}),d},z={}.hasOwnProperty,A;!D(z,"undefined")&&!D(z.call,"undefined")?A=function(a,b){return z.call(a,b)}:A=function(a,b){return b in a&&D(a.constructor.prototype[b],"undefined")},Function.prototype.bind||(Function.prototype.bind=function(b){var c=this;if(typeof c!="function")throw new TypeError;var d=v.call(arguments,1),e=function(){if(this instanceof e){var a=function(){};a.prototype=c.prototype;var f=new a,g=c.apply(f,d.concat(v.call(arguments)));return Object(g)===g?g:f}return c.apply(b,d.concat(v.call(arguments)))};return e});var I=function(c,d){var f=c.join(""),g=d.length;x(f,function(c,d){var f=b.styleSheets[b.styleSheets.length-1],h=f?f.cssRules&&f.cssRules[0]?f.cssRules[0].cssText:f.cssText||"":"",i=c.childNodes,j={};while(g--)j[i[g].id]=i[g];e.touch="ontouchstart"in a||a.DocumentTouch&&b instanceof DocumentTouch||(j.touch&&j.touch.offsetTop)===9},g,d)}([,["@media (",m.join("touch-enabled),("),h,")","{#touch{top:9px;position:absolute}}"].join("")],[,"touch"]);r.touch=function(){return e.touch},r.rgba=function(){return B("background-color:rgba(150,255,150,.5)"),E(j.backgroundColor,"rgba")},r.backgroundsize=function(){return H("backgroundSize")},r.borderradius=function(){return H("borderRadius")},r.textshadow=function(){return b.createElement("div").style.textShadow===""},r.opacity=function(){return C("opacity:.55"),/^0.55$/.test(j.opacity)},r.cssgradients=function(){var a="background-image:",b="gradient(linear,left top,right bottom,from(#9f9),to(white));",c="linear-gradient(left top,#9f9, white);";return B((a+"-webkit- ".split(" ").join(b+a)+m.join(c+a)).slice(0,-a.length)),E(j.backgroundImage,"gradient")},r.csstransitions=function(){return H("transition")},r.svg=function(){return!!b.createElementNS&&!!b.createElementNS(q.svg,"svg").createSVGRect},r.inlinesvg=function(){var a=b.createElement("div");return a.innerHTML="<svg/>",(a.firstChild&&a.firstChild.namespaceURI)==q.svg};for(var J in r)A(r,J)&&(w=J.toLowerCase(),e[w]=r[J](),u.push((e[w]?"":"no-")+w));return B(""),i=k=null,function(a,b){function g(a,b){var c=a.createElement("p"),d=a.getElementsByTagName("head")[0]||a.documentElement;return c.innerHTML="x<style>"+b+"</style>",d.insertBefore(c.lastChild,d.firstChild)}function h(){var a=k.elements;return typeof a=="string"?a.split(" "):a}function i(a){var b={},c=a.createElement,e=a.createDocumentFragment,f=e();a.createElement=function(a){var e=(b[a]||(b[a]=c(a))).cloneNode();return k.shivMethods&&e.canHaveChildren&&!d.test(a)?f.appendChild(e):e},a.createDocumentFragment=Function("h,f","return function(){var n=f.cloneNode(),c=n.createElement;h.shivMethods&&("+h().join().replace(/\w+/g,function(a){return b[a]=c(a),f.createElement(a),'c("'+a+'")'})+");return n}")(k,f)}function j(a){var b;return a.documentShived?a:(k.shivCSS&&!e&&(b=!!g(a,"article,aside,details,figcaption,figure,footer,header,hgroup,nav,section{display:block}audio{display:none}canvas,video{display:inline-block;*display:inline;*zoom:1}[hidden]{display:none}audio[controls]{display:inline-block;*display:inline;*zoom:1}mark{background:#FF0;color:#000}")),f||(b=!i(a)),b&&(a.documentShived=b),a)}var c=a.html5||{},d=/^<|^(?:button|form|map|select|textarea)$/i,e,f;(function(){var a=b.createElement("a");a.innerHTML="<xyz></xyz>",e="hidden"in a,f=a.childNodes.length==1||function(){try{b.createElement("a")}catch(a){return!0}var c=b.createDocumentFragment();return typeof c.cloneNode=="undefined"||typeof c.createDocumentFragment=="undefined"||typeof c.createElement=="undefined"}()})();var k={elements:c.elements||"abbr article aside audio bdi canvas data datalist details figcaption figure footer header hgroup mark meter nav output progress section summary time video",shivCSS:c.shivCSS!==!1,shivMethods:c.shivMethods!==!1,type:"default",shivDocument:j};a.html5=k,j(b)}(this,b),e._version=d,e._prefixes=m,e._domPrefixes=p,e._cssomPrefixes=o,e.mq=y,e.testProp=function(a){return F([a])},e.testAllProps=H,e.testStyles=x,g.className=g.className.replace(/(^|\s)no-js(\s|$)/,"$1$2")+(f?" js "+u.join(" "):""),e}(this,this.document),function(a,b,c){function d(a){return o.call(a)=="[object Function]"}function e(a){return typeof a=="string"}function f(){}function g(a){return!a||a=="loaded"||a=="complete"||a=="uninitialized"}function h(){var a=p.shift();q=1,a?a.t?m(function(){(a.t=="c"?B.injectCss:B.injectJs)(a.s,0,a.a,a.x,a.e,1)},0):(a(),h()):q=0}function i(a,c,d,e,f,i,j){function k(b){if(!o&&g(l.readyState)&&(u.r=o=1,!q&&h(),l.onload=l.onreadystatechange=null,b)){a!="img"&&m(function(){t.removeChild(l)},50);for(var d in y[c])y[c].hasOwnProperty(d)&&y[c][d].onload()}}var j=j||B.errorTimeout,l={},o=0,r=0,u={t:d,s:c,e:f,a:i,x:j};y[c]===1&&(r=1,y[c]=[],l=b.createElement(a)),a=="object"?l.data=c:(l.src=c,l.type=a),l.width=l.height="0",l.onerror=l.onload=l.onreadystatechange=function(){k.call(this,r)},p.splice(e,0,u),a!="img"&&(r||y[c]===2?(t.insertBefore(l,s?null:n),m(k,j)):y[c].push(l))}function j(a,b,c,d,f){return q=0,b=b||"j",e(a)?i(b=="c"?v:u,a,b,this.i++,c,d,f):(p.splice(this.i++,0,a),p.length==1&&h()),this}function k(){var a=B;return a.loader={load:j,i:0},a}var l=b.documentElement,m=a.setTimeout,n=b.getElementsByTagName("script")[0],o={}.toString,p=[],q=0,r="MozAppearance"in l.style,s=r&&!!b.createRange().compareNode,t=s?l:n.parentNode,l=a.opera&&o.call(a.opera)=="[object Opera]",l=!!b.attachEvent&&!l,u=r?"object":l?"script":"img",v=l?"script":u,w=Array.isArray||function(a){return o.call(a)=="[object Array]"},x=[],y={},z={timeout:function(a,b){return b.length&&(a.timeout=b[0]),a}},A,B;B=function(a){function b(a){var a=a.split("!"),b=x.length,c=a.pop(),d=a.length,c={url:c,origUrl:c,prefixes:a},e,f,g;for(f=0;f<d;f++)g=a[f].split("="),(e=z[g.shift()])&&(c=e(c,g));for(f=0;f<b;f++)c=x[f](c);return c}function g(a,e,f,g,i){var j=b(a),l=j.autoCallback;j.url.split(".").pop().split("?").shift(),j.bypass||(e&&(e=d(e)?e:e[a]||e[g]||e[a.split("/").pop().split("?")[0]]||h),j.instead?j.instead(a,e,f,g,i):(y[j.url]?j.noexec=!0:y[j.url]=1,f.load(j.url,j.forceCSS||!j.forceJS&&"css"==j.url.split(".").pop().split("?").shift()?"c":c,j.noexec,j.attrs,j.timeout),(d(e)||d(l))&&f.load(function(){k(),e&&e(j.origUrl,i,g),l&&l(j.origUrl,i,g),y[j.url]=2})))}function i(a,b){function c(a,c){if(a){if(e(a))c||(j=function(){var a=[].slice.call(arguments);k.apply(this,a),l()}),g(a,j,b,0,h);else if(Object(a)===a)for(n in m=function(){var b=0,c;for(c in a)a.hasOwnProperty(c)&&b++;return b}(),a)a.hasOwnProperty(n)&&(!c&&!--m&&(d(j)?j=function(){var a=[].slice.call(arguments);k.apply(this,a),l()}:j[n]=function(a){return function(){var b=[].slice.call(arguments);a&&a.apply(this,b),l()}}(k[n])),g(a[n],j,b,n,h))}else!c&&l()}var h=!!a.test,i=a.load||a.both,j=a.callback||f,k=j,l=a.complete||f,m,n;c(h?a.yep:a.nope,!!i),i&&c(i)}var j,l,m=this.yepnope.loader;if(e(a))g(a,0,m,0);else if(w(a))for(j=0;j<a.length;j++)l=a[j],e(l)?g(l,0,m,0):w(l)?B(l):Object(l)===l&&i(l,m);else Object(a)===a&&i(a,m)},B.addPrefix=function(a,b){z[a]=b},B.addFilter=function(a){x.push(a)},B.errorTimeout=1e4,b.readyState==null&&b.addEventListener&&(b.readyState="loading",b.addEventListener("DOMContentLoaded",A=function(){b.removeEventListener("DOMContentLoaded",A,0),b.readyState="complete"},0)),a.yepnope=k(),a.yepnope.executeStack=h,a.yepnope.injectJs=function(a,c,d,e,i,j){var k=b.createElement("script"),l,o,e=e||B.errorTimeout;k.src=a;for(o in d)k.setAttribute(o,d[o]);c=j?h:c||f,k.onreadystatechange=k.onload=function(){!l&&g(k.readyState)&&(l=1,c(),k.onload=k.onreadystatechange=null)},m(function(){l||(l=1,c(1))},e),i?k.onload():n.parentNode.insertBefore(k,n)},a.yepnope.injectCss=function(a,c,d,e,g,i){var e=b.createElement("link"),j,c=i?h:c||f;e.href=a,e.rel="stylesheet",e.type="text/css";for(j in d)e.setAttribute(j,d[j]);g||(n.parentNode.insertBefore(e,n),m(c,0))}}(this,document),Modernizr.load=function(){yepnope.apply(window,[].slice.call(arguments,0))};
    </script>

<script>
$(document).ready(function(){
    var lang = ('{LANG}'==='pl')?0:1 ;
    var links = $('#lang-list').find('li');
    links[lang].style.borderColor = '#a71F23';
    links[lang].children[0].style.color= '#a71F23';
});
</script>

<!-- Google Code for Remarketing -->
<script type="text/javascript">
/* <![CDATA[ */
var google_conversion_id = 1011367281;
var google_custom_params = window.google_tag_params;
var google_remarketing_only = true;
/* ]]> */
</script>
<script type="text/javascript" src="//www.googleadservices.com/pagead/conversion.js"></script>
<noscript>
<div style="display:inline;">
<img height="1" width="1" style="border-style:none;" alt="" src="//googleads.g.doubleclick.net/pagead/viewthroughconversion/1011367281/?value=0&amp;guid=ON&amp;script=0"/>
</div>
</noscript>

<!--[if !IE 6]><!-->
<script src="http://gomez.pl/template/3/js/monobrands/monobrands.js"></script>
<!--<![endif]-->


{DEBUG}        

</body>
</html>