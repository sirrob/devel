<!-- item product -->
<script type="text/javascript">
    function roz_wybierz(id, nazwa) {
        if (document.getElementById("rozmiar").value != "") {
            document.getElementById("roz" + document.getElementById("rozmiar").value).className = "tabroz";
        }

        document.getElementById("rozmiar").value = id;
        document.getElementById("rozwyb").innerHTML = nazwa;

        document.getElementById("roz" + document.getElementById("rozmiar").value).className = "tabroza";
        $("#size-list li").removeClass("selected");
        $("div#roz" + id).parent().addClass("selected");
    }

    function roz_powiadom(id, nazwa) {
        window.open(Purl + "pop_produkt_ask_size.php?id=" + id + "&t={NAZWA}&s=" + nazwa + "&k=produkt&i={LANG}/produkt/{PRID}/", "polec", "width=655,height=490,left=20,top=10,scrollbars=yes,resizable=yes,status=yes");
    }


    function m_validate() {
        <!--{IT_JS_VAL}-->return true;<!--/{IT_JS_VAL}-->

        if (document.getElementById('rozmiar').value == "") {
            if ('{LANG}' == 'pl')
                alert('Wybierz rozmiar!');
            else
                alert('Choose size!');
            return false;
        }

        return true;
    }

    var Purl = '{URL}';
    var Plang = '{LANG}';
    var scr = 0;
    var lastimg = 0;
    var countimg = 5;
    var maximg = 4;
    var minimg = 0;

    function scrmvup(f, t) {
        $('#midi-container').scrollTop(f);
        f++;
        if (f <= t) setTimeout('scrmvup(' + f + ',' + t + ')', 3);
    }

    function scrmvdown(f, t) {
        $('#midi-container').scrollTop(f);
        f--;
        if (f >= t) setTimeout('scrmvdown(' + f + ',' + t + ')', 3);
    }

    function tmbsrc(val) {
        scr = $('#midi-container').scrollTop();//(val*115)-115;
        c = 0;
        $('#thumblist li').each(function () {
            c++;
        });

        if (val == maximg) {
            if (val < (c - 1)) {
                scr = $('#midi-container').scrollTop() + 117;
                maximg++;
                minimg++;
            }

            if ($('#midi-container').scrollTop() > scr) scrmvdown($('#midi-container').scrollTop(), scr);
            else scrmvup($('#midi-container').scrollTop(), scr);

        }
        if (val == minimg) {
            if ($('#midi-container').scrollTop() > 0) {
                scr = $('#midi-container').scrollTop() - 117;
                if (scr < 0) scr = 0;
                maximg--;
                minimg--;
            } else {
                src = 0;
                maximg = 4;
                minimg = 0;
            }

            if ($('#midi-container').scrollTop() > scr) scrmvdown($('#midi-container').scrollTop(), scr);
            else scrmvup($('#midi-container').scrollTop(), scr);
        }
    }
</script>


<div id="tabsizer" title="{T_TABELA_ROZMIAROW}" style="display: none">

    <div class="tabs">
        <ul class="zakladki">
            <li>
                <div class="tabl"></div>
                <a href="#tabwoman">{T_TABELA}</a>

                <div class="tabr"></div>
            </li>
            <li>
                <div class="tabl"></div>
                <a href="#jakzmierzyc">{T_JAK_MIERZYC}</a>

                <div class="tabr"></div>
            </li>
        </ul>

        <div id="tabwoman" class="table-size">
            {TABLESIZERWOMAN}
        </div>
        <div id="jakzmierzyc">
            {JAKMIERZYC}
        </div>
    </div>
</div>
<div id="msg-tool"></div>
<form method="post" onsubmit="return m_validate()" class="jqTransform">
    {HIDDEN}
    <input type="hidden" name="id" value="{PRID}"/>

    <div id="body" class="unit">
        <div id="body-container" class="container wrapper">

            <div id="body-columns" class="columns">
                <div id="midi-column" class="column fixed">
                    <div id="midi-container" class="container">
                        <ul id="thumblist">
                            <!--{IT_IMG_WIECEJ}--> <!-- style="width: 110px; height: 109px;" -->
                            <!--{IT_IMG}-->
                            <li><a href="javascript:tmbsrc({LP});" title="{ALT}"
                                   rel="{gallery: 'gal1', smallimage: '{IMG_L}',largeimage: '{IMG_X}'}"><img
                                            src="{IMG_S}" alt="" class="fileimgmini" onclick="tmbsrc({LP})"/></a></li>
                            <!--/{IT_IMG}-->
                            <!--/{IT_IMG_WIECEJ}-->
                        </ul>
                    </div>
                    <!-- midi-container -->
                </div>
                <!-- midi-column -->

                <div id="preview-column" class="column fixed">
                    <div id="preview-container" class="container">
                        <!-- main photo -->
                        <a href="{IMG_X}" title="{ALT}" rel="gal1" class="jqzoom"><img src="{IMG_L}" alt="{ALT}"
                                                                                       id="prewphoto"/></a>
                    </div>
                    <!-- preview-container -->
                </div>
                <!-- preview-column -->

                <div id="main-column" class="column fixed">
                    <div id="main-container" class="container">

                        <div id="product-details">
                            <div id="product-details-main">
                                <h1 class="with-tooltip" title="{NAZWA}">{NAZWA}</h1>

                                <div id="product-details-price">{T_CENA}: <span>{CENA} {WALUTA}</span></div>
                                <div id="product-details-delivery">{T_KOSZTY_DOSTAWY}</div>
                                <script>

                                    var zwrot = false;

                                    if (
                                    document.location.href.indexOf('stringi') == -1
                                    &&
                                    document.location.href.indexOf('bikini') == -1
                                    &&
                                    document.location.href.indexOf('figi') == -1
                                    &&
                                    document.location.href.indexOf('stroj_kapielowy') == -1
                                    &&
                                    document.location.href.indexOf('bokserki') == -1
                                    &&
                                    document.location.href.indexOf('slipy') == -1
                                    &&
                                    document.location.href.indexOf('kapielowki') == -1
                                    &&
                                    document.location.href.indexOf('majtki') == -1
                                        )
                                         zwrot = true;
                                    if (document.location.href.indexOf('bikini') > -1 && document.location.href.indexOf('gora') > -1)
                                         zwrot = true;



                                    if ( zwrot == true ){
                                       document.write('<div id="product-details-returns">{T_ZWROT_WYMIANA}</div>');
                                    }
                                    else if ( zwrot == false ){
                                        document.write('<div id="product-details-returns"><a href="http://gomez.pl/pl/brak_mozliwosci_zwrotu/">Brak możliwości zwrotu, zobacz szczegóły</a></div>');
                                    }
                                </script>
                              <!--  <div id="product-details-returns">{T_ZWROT_WYMIANA}</div> -->


                            </div>
                            <!-- product-details-main -->

                            <div id="product-details-logotype">
                                <a href="{URL_PRODUCENT}">{PRODUCENT}</a>
                            </div>
                            <!-- product-details-logotyp -->

                            <div id="product-details-rest">
                                <input type="image" src="{URL}files/cms/6/koszyk{LANG}.gif" name="submit_dokoszyka_2">

                                <div id="product-details-sizes{RS}" class="scroll-pane">{ROZMIAR}</div>

                                <div id="product-details-colors">
                                    <div style="float: left;adding-right: 12px;">{KOLORYLABEL} </div>
                                    <span>{KOLORY}</span></div>

                                <div class="product-details-more-left">{T_MARKA}: <span
                                            class="brand-name">{PRODUCENTNAME}</span></div>
                                <div class="product-details-more-left">{T_MODEL}: <span>{MODEL}</span></div>
                                <div class="product-details-more-left">{T_KOLOR}: <span>{KOLOR}</span></div>
                                <div class="product-details-more-left">{T_SKLAD}: <span>{TKANINA}</span></div>
                                <div class="product-details-more-left">{T_KOLEKCJA}: <span>{KOLEKCJA}</span></div>
                                <div class="product-details-more-left">{T_INDEKS}: <span>{INDEKS}</span></div>

                                <div id="product-details-description" class="scroll-pane" style="height: 270px;">
                                    <div id="product-details-description-container">{OPIS}</div>
                                </div>
                            </div>
                        </div>
                        <!-- product-details -->

                        <div id="product-buttons">
                            <a href="javascript:;" onclick="m_obserwuj({PRID}, {UID});" class="pushlink_obserwuj">
                                <div>{T_OBSERWUJ}</div>
                            </a>
                            <a href="javascript:;" onclick="m_druk('produkt', {PRID}, {LANGID});"
                               class="pushlink_print">
                                <div>{T_DRUKUJ}</div>
                                <!--{T_DRUK}--></a>
                            <a href="javascript:;" onclick="m_polec('produkt', '{NAZWA}', '{URL}', {LANGID});"
                               class="pushlink_recomend">
                                <div>{T_POLEC_ZNAJOMEMU}</div>
                                <!--{T_POLEC}--></a>
                            <!-- <div id="prd-like-button" class="fb-like" data-href="http://www.facebook.com/profile.php?id=100000482816449" data-send="false" data-layout="button_count" data-width="50" data-show-faces="false" data-font="tahoma"></div> -->
                            <!-- fb-like-button -->
                            <div id="prd-pinit-button">
                                <a href="http://pinterest.com/pin/create/button/?url={PINIT_URL}&media={PINIT_IMG_URL}&description={PINIT_DESC}"
                                   rel="nofollow" class="pin-it-button" count-layout="none"><img border="0"
                                                                                                 src="//assets.pinterest.com/images/PinExt.png"
                                                                                                 title="Pin It"/></a>
                            </div>
                            <div id="prd-like-button" class="fb-like" data-href="{PRDURL}" data-send="false"
                                 data-layout="button_count" data-width="55" data-show-faces="false"
                                 data-font="tahoma"></div>
                        </div>
                    </div>
                    <!-- main-container -->
                </div>
                <!-- main-column -->
            </div>
            <!-- body-columns -->
            <br>
            <!--{IT_PODOBNE}-->
            <div id="product-recommended-list">
                <ul id="product-recommended-list-carousel" class="jcarousel-skin-recommended"
                    onmouseover="tooltip_go()">
                    <!--{PODOBNE}-->s<!--/{PODOBNE}-->
                </ul>
            </div>
            <!--/{IT_PODOBNE}-->

        </div>
        <!-- body-container -->
    </div>
    <!-- body -->