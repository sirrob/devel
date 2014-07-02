<!DOCTYPE html>
<!--[if lt IE 7]>      <html class="no-js lt-ie9 lt-ie8 lt-ie7"> <![endif]-->
<!--[if IE 7]>         <html class="no-js lt-ie9 lt-ie8"> <![endif]-->
<!--[if IE 8]>         <html class="no-js lt-ie9"> <![endif]-->
<!--[if gt IE 8]><!--> <html class="no-js"> <!--<![endif]-->
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <title>GOMEZ.PL</title>
        <meta name="description" content="">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <link rel="shortcut icon" href="favicon.ico">
        <link rel="apple-touch-icon-precomposed" sizes="144x144"
              href="apple-touch-icon-144-precomposed.png">
        <link rel="apple-touch-icon-precomposed" sizes="114x114"
              href="apple-touch-icon-114-precomposed.png">
        <link rel="apple-touch-icon-precomposed" sizes="72x72"
              href="apple-touch-icon-72-precomposed.png">
        <link rel="apple-touch-icon-precomposed"
              href="apple-touch-icon-57-precomposed.png">

        <link rel="stylesheet" href="{URL}template/{TEMPLATE}/css/normalize.css">
        <link rel="stylesheet" href="{URL}template/{TEMPLATE}/css/main.css">
        <link rel="stylesheet" href="{URL}template/{TEMPLATE}/css/bootstrap-theme.css">
        <link rel="stylesheet" href="{URL}template/{TEMPLATE}/css/bootstrap.css">
        <link rel="stylesheet" href="{URL}template/{TEMPLATE}/css/dropkick.css">
        <link rel="stylesheet" href="{URL}template/{TEMPLATE}/css/yamm.css">
        <link rel="stylesheet" href="{URL}template/{TEMPLATE}/css/theme.css">


        <link href='http://fonts.googleapis.com/css?family=Open+Sans:300italic,400italic,600italic,700italic,800italic,400,300,600,700,800' rel='stylesheet' type='text/css'>

        <script type="text/javascript" src="{URL}template/{TEMPLATE}/js/modernizr.min.js"></script>
        <script type="text/javascript" src="{URL}template/{TEMPLATE}/js/jquery-2.1.1.min.js"></script>

    </head>
    <body>
        <!--[if lt IE 7]>
            <p class="browsehappy">You are using an <strong>outdated</strong> browser. Please <a href="http://browsehappy.com/">upgrade your browser</a> to improve your experience.</p>
        <![endif]-->


        <header class="container">
            <div class="row" id="top-bar">
                <nav id="language-menu" class="col-md-3 col-sm-2 col-xs-3">
                    <ul>
                        <li>PL</li>
                        <li><a href="#">EN</a></li>
                        <li><a href="#">HR</a></li>
                        <li><a href="#">SR</a></li>
                    </ul>
                </nav>
                <div class="navbar-header visible-xs col-xs-3" id="login-button">
                    <button type="button"  class="navbar-toggle2 btn btn-default btn-xs" data-toggle="collapse" data-target="#login-form-collapse">
                        Zaloguj się
                    </button>
                </div>
                <div class="col-md-8 col-md-offset-1 col-sm-10 col-xs-6 center-vertical" id="user-basket-bar">

                   <div id="user-basket-info">

                  <div id="login-form-collapse" class="navbar-collapse collapse">
                    <form id="login-form" role="form">
                       <input type="text" name='login-email' class="form-control" placeholder="- podaj swój email -">
                       <input type="password" name='login-password' class="form-control" placeholder="- hasło -">
                       <button type="button" class="btn btn-gomez">
                           <span class="glyphicon glyphicon-play"></span>
                       </button>
                       <button type="button" class="btn btn-gomez visible-xs" data-toggle="collapse" data-target="#login-form-collapse">
                           <span class="glyphicon glyphicon-remove"></span>
                       </button>
                    </form>
                    <div id="registry-remember">
                         <a href ="#">Zapomniałem hasła</a> / <a href ="#">Zarejestruj się</a>
                    </div>
                   </div>
                    <div id="basket-info">
                       <span>KOSZYK</span>
                    </div>
                  <a href="#"> <div id="basket-info-num"><span>3</span></div></a>
                    <div id="basket-info-sum"><span>3240,50 zł</span></div>
                   </div>
                </div>
            </div>
            <div class="row" id="top-bar2">
                <div class="col-md-2 col-sm-2 col-xs-3" id="media-social-icons-top">
                <a href="#">    <svg version="1.1" id="gomez-facebook" xmlns="http://www.w3.org/2000/svg"  x="0px" y="0px"
                         width="56.693px" height="56.693px" viewBox="0 0 56.693 56.693" enable-background="new 0 0 56.693 56.693" xml:space="preserve">
                         <path fill="#7F7F7F" d="M40.43,21.739h-7.645v-5.014c0-1.883,1.248-2.322,2.127-2.322c0.877,0,5.395,0,5.395,0V6.125l-7.43-0.029
                            c-8.248,0-10.125,6.174-10.125,10.125v5.518h-4.77v8.53h4.77c0,10.947,0,24.137,0,24.137h10.033c0,0,0-13.32,0-24.137h6.77
                            L40.43,21.739z"/>
                    </svg>
                </a>

                    <a href="#"><svg version="1.1" id="gomez-pinterest" xmlns="http://www.w3.org/2000/svg" x="0px" y="0px"
                         width="56.693px" height="56.693px" viewBox="0 0 56.693 56.693" enable-background="new 0 0 56.693 56.693" xml:space="preserve">
                    <path fill="#7F7F7F" d="M30.374,4.622c-13.586,0-20.437,9.74-20.437,17.864c0,4.918,1.862,9.293,5.855,10.922c0.655,0.27,1.242,0.01,1.432-0.715
                        c0.132-0.5,0.445-1.766,0.584-2.295c0.191-0.717,0.117-0.967-0.412-1.594c-1.151-1.357-1.888-3.115-1.888-5.607
                        c0-7.226,5.407-13.695,14.079-13.695c7.679,0,11.898,4.692,11.898,10.957c0,8.246-3.649,15.205-9.065,15.205
                        c-2.992,0-5.23-2.473-4.514-5.508c0.859-3.623,2.524-7.531,2.524-10.148c0-2.34-1.257-4.292-3.856-4.292
                        c-3.058,0-5.515,3.164-5.515,7.401c0,2.699,0.912,4.525,0.912,4.525s-3.129,13.26-3.678,15.582
                        c-1.092,4.625-0.164,10.293-0.085,10.865c0.046,0.34,0.482,0.422,0.68,0.166c0.281-0.369,3.925-4.865,5.162-9.359
                        c0.351-1.271,2.011-7.859,2.011-7.859c0.994,1.896,3.898,3.562,6.986,3.562c9.191,0,15.428-8.379,15.428-19.595
                        C48.476,12.521,41.292,4.622,30.374,4.622z"/>
                    </svg> </a>

                   <a href=""> <svg version="1.1" id="gomez-twitter" xmlns="http://www.w3.org/2000/svg"  x="0px" y="0px"
                         width="56.693px" height="56.693px" viewBox="0 0 56.693 56.693" enable-background="new 0 0 56.693 56.693" xml:space="preserve">
                    <path fill="#7F7F7F" class="twitter" d="M52.837,15.065c-1.811,0.805-3.76,1.348-5.805,1.591c2.088-1.25,3.689-3.23,4.444-5.592c-1.953,1.159-4.115,2-6.418,2.454
                        c-1.843-1.964-4.47-3.192-7.377-3.192c-5.581,0-10.106,4.525-10.106,10.107c0,0.791,0.089,1.562,0.262,2.303
                        c-8.4-0.422-15.848-4.445-20.833-10.56c-0.87,1.492-1.368,3.228-1.368,5.082c0,3.506,1.784,6.6,4.496,8.412
                        c-1.656-0.053-3.215-0.508-4.578-1.265c-0.001,0.042-0.001,0.085-0.001,0.128c0,4.896,3.484,8.98,8.108,9.91
                        c-0.848,0.23-1.741,0.354-2.663,0.354c-0.652,0-1.285-0.063-1.902-0.182c1.287,4.015,5.019,6.938,9.441,7.019
                        c-3.459,2.711-7.816,4.327-12.552,4.327c-0.815,0-1.62-0.048-2.411-0.142c4.474,2.869,9.786,4.541,15.493,4.541
                        c18.591,0,28.756-15.4,28.756-28.756c0-0.438-0.009-0.875-0.028-1.309C49.769,18.873,51.483,17.092,52.837,15.065z"/>
                    </svg></a>
                </div>
                <div class="col-md-4 col-md-offset-2 col-sm-4 col-sm-offset-2 col-xs-6" id="logo-top">
                    <div class="svg-container">
                        <object type="image/svg+xml" data="../../template/3/images/gomez.svg" class="svg-content">
                        </object>
                    </div>
                </div>
                <div class="col-md-2 col-md-offset-2 col-sm-3 col-sm-offset-1 col-xs-3" id="newsletter-top">
                    <a href="#">kontakt</a> / <a href="#">newsletter</a>
                </div>
            </div>
        </header>

        <nav class="container">
            <div class="row" id="main-menu">
                <div class="col-md-10 col-sm-12 col-xs-2">
                    <nav class="navbar yamm navbar-default" role="navigation">
                        <div class="navbar-header">
                            <button type="button" data-toggle="collapse" data-target="#menu-collapse" class="navbar-toggle" id="button-menu">
                                <span class="icon-bar"></span>
                                <span class="icon-bar"></span>
                                <span class="icon-bar"></span>
                            </button>
                        </div>
                        <div id="menu-collapse" class="navbar-collapse collapse ">
                       <ul class="nav navbar-nav">
                        <li class="dropdown short">
                            <a  href="#" class="dropdown" data-toggle="dropdown">WOMEN</a>
                            <ul class="dropdown-menu">
                                <li>
                                <div class="yamm-content">
                                    <div class="row">
                                        <ul class="col-md-2 col-sm-3 col-xs-12">
                                            <li class="menu-category"><a href="#">ODZIEŻ</a></li>
                                            <li><a href="#">BLUZY/DRESY</a></li>
                                            <li><a href="#">BLUZKI</a></li>
                                            <li><a href="#">JEANSY/SPODNIE</a></li>
                                            <li><a href="#">KOSZULE</a></li>
                                            <li><a href="#">KURTKI/PŁASZCZE</a></li>
                                            <li><a href="#">MARYNARKI/ŻAKIETY</a></li>
                                            <li><a href="#">POLO/T-SHIRTY</a></li>
                                            <li><a href="#">SPÓDNICE</a></li>
                                            <li><a href="#">SUKIENKI/TUNIKI</a></li>
                                            <li><a href="#">SWETRY</a></li>
                                            <li><a href="#">SZORTY</a></li>
                                            <li><a href="#">STROJE KĄPIELOWE</a></li>
                                        </ul>
                                        <ul class="col-md-2 col-sm-3 col-xs-12">
                                            <li class="menu-category"><a href="#">OBUWIE</a></li>
                                            <li><a href="#">BALERINY</a></li>
                                            <li><a href="#">BOTKI</a></li>
                                            <li><a href="#">JAPONKI/KLAPKI</a></li>
                                            <li><a href="#">KALOSZE/ŚNIEGOWCE</a></li>
                                            <li><a href="#">KOTURNY/ESPADRYLE</a></li>
                                            <li><a href="#">KOZAKI</a></li>
                                            <li><a href="#">MOKASYNY</a></li>
                                            <li><a href="#">OBUWIE DOMOWE</a></li>
                                            <li><a href="#">SANDAŁY</a></li>
                                            <li><a href="#">SZPILKI/CZÓŁENKA</a></li>
                                            <li><a href="#">TRAMPKI/SNEAKERSY</a></li>
                                            <li><a href="#">PÓŁBUTY</a></li>
                                        </ul>
                                        <ul class="col-md-2 col-sm-3 col-xs-12">
                                            <li class="menu-category"><a href="#">AKCESORIA</a></li>
                                            <li><a href="#">TOREBKI/PLECAKI</a></li>
                                            <li><a href="#">PORTFELE/SASZETKI</a></li>
                                            <li><a href="#">BIŻUTERIA</a></li>
                                            <li><a href="#">BRELOKI</a></li>
                                            <li><a href="#">PASKI</a></li>
                                            <li><a href="#">CZAPKI</a></li>
                                            <li><a href="#">SZALE/APASZKI</a></li>
                                            <li><a href="#">RĘKAWICZKI</a></li>
                                            <li><a href="#">WALIZKI</a></li>
                                            <li><a href="#">RĘCZNIKI</a></li>
                                        </ul>
                                        <ul class="col-md-2 col-sm-3  col-xs-12">
                                            <li class="menu-category"><a href="#">BIELIZNA</a></li>
                                            <li><a href="#">BIUSTONOSZE</a></li>
                                            <li><a href="#">MAJTKI</a></li>
                                            <li><a href="#">PIŻAMY</a></li>
                                            <li><a href="#">RAJSTOPY</a></li>
                                            <li><a href="#">SKARPETKI</a></li>
                                        </ul>
                                    </div>

                                </div>

                                </li>

                            </ul>

                        </li>
                        <li class="dropdown short">
                            <a href="#" class="dropdown" data-toggle="dropdown">MEN</a>
                            <ul class="dropdown-menu">
                                <li>
                                    <div class="yamm-content">
                                        <div class="row">
                                            <ul class="col-md-2 col-sm-3 col-xs-12">
                                                <li class="menu-category"><a href="#">ODZIEŻ</a></li>
                                                <li><a href="#">BLUZY</a></li>
                                                <li><a href="#">GARNITURY</a></li>
                                                <li><a href="#">JEANSY/SPODNIE</a></li>
                                                <li><a href="#">KOSZULE</a></li>
                                                <li><a href="#">KURTKI/PŁASZCZE</a></li>
                                                <li><a href="#">LONGSLEEVY</a></li>
                                                <li><a href="#">MARYNARKI</a></li>
                                                <li><a href="#">POLO</a></li>
                                                <li><a href="#">T-SHIRTY</a></li>
                                                <li><a href="#">SWETRY</a></li>
                                                <li><a href="#">SZORTY/SPODENKI</a></li>
                                                <li><a href="#">ODZIEŻ SPORTOWA</a></li>
                                            </ul>
                                            <ul class="col-md-2 col-sm-3 col-xs-12">
                                                <li class="menu-category"><a href="#">OBUWIE</a></li>
                                                <li><a href="#">JAPONKI/KLAPKI</a></li>
                                                <li><a href="#">KOZAKI/SZTYBLETY</a></li>
                                                <li><a href="#">MOKASYNY</a></li>
                                                <li><a href="#">OBUWIE DOMOWE</a></li>
                                                <li><a href="#">PÓŁBUTY</a></li>
                                                <li><a href="#">SNEAKERSY</a></li>
                                                <li><a href="#">TRAMPKI</a></li>
                                                <li><a href="#">TRAPERY</a></li>
                                            </ul>
                                            <ul class="col-md-2 col-sm-3 col-xs-12">
                                                <li class="menu-category"><a href="#">AKCESORIA</a></li>
                                                <li><a href="#">TOREBKI/PLECAKI</a></li>
                                                <li><a href="#">PORTFELE/AKCESORIA</a></li>
                                                <li><a href="#">PASKI</a></li>
                                                <li><a href="#">KRAWATY</a></li>
                                                <li><a href="#">RECZNIKI</a></li>
                                                <li><a href="#">RĘKAWICZKI</a></li>
                                                <li><a href="#">WALIZKI</a></li>
                                                <li><a href="#">RĘCZNIKI</a></li>
                                                <li><a href="#">CZAPKI</a></li>
                                                <li><a href="#">SZALE</a></li>
                                            </ul>
                                            <ul class="col-md-2 col-sm-3 col-xs-12">
                                                <li class="menu-category"><a href="#">BIELIZNA</a></li>
                                                <li><a href="#">SKARPETKI</a></li>
                                                <li><a href="#">BOKSERKI</a></li>
                                                <li><a href="#">SLIPY</a></li>
                                                <li><a href="#">PODKOSZULKI</a></li>
                                                <li><a href="#">PIŻAMY</a></li>
                                                <li><a href="#">KĄPIELÓWKI</a></li>
                                            </ul>
                                        </div>

                                    </div>

                                </li>

                            </ul>
                        </li>
                        <li class="dropdown short">
                            <a href="#" class="dropdown">KIDS</a>
                        </li>
                        <li class="dropdown short">
                            <a href="#" class="dropdown">SHOES</a>
                        </li>
                        <li class="dropdown short">
                            <a href="#" class="dropdown">BAGS</a>
                        </li>
                        <li class="dropdown middle">
                            <a href="#" class="dropdown">BRANDS</a>
                        </li>
                        <li class="dropdown long">
                            <a href="#" class="dropdown">GOMEZ CLUB</a>
                        </li>
                        <li class="dropdown long">
                            <a href="#" class="dropdown">OUR WORLD</a>
                        </li>
                        <li class="dropdown short">
                            <a href="#" class="dropdown">SALE</a>
                        </li>
                    </ul>
                            </div>
                    </nav>
                </div>
                <div class="input-group col-md-2 col-sm-12 col-xs-10" id="gomez-search">
                    <input type="text" class="form-control" placeholder="Szukaj..."/>
                    <div class="input-group-btn">
                        <button class="btn btn-default" type="submit">
                            <span class="sr-only">Search</span>
                            <span class="glyphicon glyphicon-search"aria-hidden="true"></span>
                        </button>
                    </div>
                </div>
            </div>

        </nav>

        <section class="container">
            <div class="row fluid" id="big-image">
                <div class="col-md-12 fluid">
                  <img src="{URL}template/{TEMPLATE}/images/lacoste-front.jpg">
                </div>
            </div>
            <div class="row fluid" id="small-image">
                <div class="col-xs-7 col-sm-12 col-md-4 fluid left">
                    <div id="left-image-1">
                        <img class="img-responsive" src="{URL}template/{TEMPLATE}/images/denim-front.jpg">
                    </div>
                    <div id="left-image-2">
                        <img  class="img-responsive" src="{URL}template/{TEMPLATE}/images/guess-front.jpg">
                    </div>
                </div>
                <div class="col-xs-5 col-sm-5 col-md-3 fluid center">
                    <img  class="img-responsive"  src="{URL}template/{TEMPLATE}/images/furla-front.jpg">
                </div>
                <div class="col-xs-12 col-sm-7 col-md-5 fluid right">
                    <img  class="img-responsive"  src="{URL}template/{TEMPLATE}/images/hugo-boss.jpg">
                </div>
            </div>
        </section>

        <footer class="container">
            <div class="row" id="footer" class="col-md-12">
                <ul class="list-inline hidden-xs">
                    <li><a href="#">STREFA KLIENTA</a></li>
                    <li><a href="#">KONTAKT</a></li>
                    <li><a href="#">SALONY GOMEZ</a></li>
                    <li><a href="#">O NAS</a></li>
                    <li><a href="#">REGULAMIN</a></li>
                    <li><a href="#">GWARANCJE/REKLAMACJE</a></li>
                    <li><a href="#">DOSTAWA</a></li>
                    <li><a href="#">WYMIANY/ZWROTY</a></li>
                    <li><a href="#">PRACA</a></li>
                    <li><a href="#">FAQ</a></li>
                </ul>
                <div class="pull-right" id="copyright">Copyright &#169;2014 GOMEZ.PL Created By Gomez</div>
            </div>
        </footer>

        <!-- Google Analytics: change UA-XXXXX-X to be your site's ID. -->
        <script>
            (function (b, o, i, l, e, r) {
                b.GoogleAnalyticsObject = l;
                b[l] || (b[l] =
                        function () {
                            (b[l].q = b[l].q || []).push(arguments)
                        });
                b[l].l = +new Date;
                e = o.createElement(i);
                r = o.getElementsByTagName(i)[0];
                e.src = '//www.google-analytics.com/analytics.js';
                r.parentNode.insertBefore(e, r)
            }(window, document, 'script', 'ga'));
            ga('create', 'UA-XXXXX-X');
            ga('send', 'pageview');
        </script>
        <script type="text/javascript" src="{URL}template/{TEMPLATE}/js/grids.min.js"></script>
        <script type="text/javascript" src="{URL}template/{TEMPLATE}/js/bootstrap.min.js"></script>
        <script type="text/javascript" src="{URL}template/{TEMPLATE}/js/plugins.js"></script>
        <script type="text/javascript" src="{URL}template/{TEMPLATE}/js/jquery.transit.js"></script>
        <script type="text/javascript" src="{URL}template/{TEMPLATE}/js/jquery.dropkick-min.js"></script>
        <script type="text/javascript" src="{URL}template/{TEMPLATE}/js/jquery.mousewheel.min.js"></script>
        <script type="text/javascript" src="{URL}template/{TEMPLATE}/js/jquery.touchSwipe.min.js"></script>
        <script type="text/javascript" src="{URL}template/{TEMPLATE}/js/holder.js"></script>
        <script type="text/javascript" src="{URL}template/{TEMPLATE}/js/skroller.js"></script>
        <script type="text/javascript" src="{URL}template/{TEMPLATE}/js/whcookies.js"></script>
    </body>
</html>