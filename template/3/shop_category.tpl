<!-- miejsce gdzie jest wyświetlana lista produktów -->
<!-- shop_category -->
<div class="ramka">
{BANERCATEGORY}

<!--{IT_ELEMENT}-->
<div class="katelement">
<!--Rozne warianty wyswietlenia przyciskow-->
<!--{IT_EL_1}--><div style="text-align:center;margin-bottom:4px;display:none"><a href="{URL1}">{SRC1}</a></div><!--/{IT_EL_1}-->
<!--{IT_EL_2}--><div style="text-align:center;margin-bottom:4px;display:none"><table><tr><td class="pr"><a href="{URL1}">{SRC1}</a></td><td class="pl"><a href="{URL2}">{SRC2}</a></td></tr></table></div><!--/{IT_EL_2}-->
<!--{IT_EL_3}--><div style="text-align:center;margin-bottom:4px;"><table><tr><td rowspan="2" class="pr"><a href="{URL1}">{SRC1}</a></td><td class="pl"><a href="{URL2}">{SRC2}</a></td></tr>
<tr><td class="pl"><a href="{URL3}">{SRC3}</a></td></tr>
</table></div><!--/{IT_EL_3}-->
<!--{IT_EL_4}--><div style="text-align:center;margin-bottom:4px;"><table><tr><td rowspan="2" class="pr"><a href="{URL1}">{SRC1}</a></td><td rowspan="2" class="pb"><a href="{URL2}">{SRC2}</a></td><td class="pl"><a href="{URL3}">{SRC3}</a></td></tr>
<tr><td class="pl"><a href="{URL4}">{SRC4}</a></td></tr>
</table></div><!--/{IT_EL_4}-->
<!--{IT_EL_5}--><div style="text-align:center;margin-bottom:4px;"><table><tr><td rowspan="2" class="pr"><a href="{URL1}">{SRC1}</a></td><td class="pb"><a href="{URL2}">{SRC2}</a></td><td class="pl"><a href="{URL3}">{SRC3}</a></td></tr>
<tr><td class="pb"><a href="{URL4}">{SRC4}</a></td><td class="pl"><a href="{URL5}">{SRC5}</a></td></tr>
</table></div><!--/{IT_EL_5}-->
<!--{IT_EL_6}--><div style="text-align:center;margin-bottom:4px;"><table><tr><td class="pr"><a href="{URL1}">{SRC1}</a></td><td class="pb"><a href="{URL2}">{SRC2}</a></td><td class="pl"><a href="{URL3}">{SRC3}</a></td></tr>
<tr><td class="pr"><a href="{URL4}">{SRC4}</a></td><td class="pb"><a href="{URL5}">zxc{SRC5}</a></td><td class="pl"><a href="{URL6}">{SRC6}</a></td></tr>
</table></div><!--/{IT_EL_6}-->
{ELEMENT}
</div><!--/{IT_ELEMENT}-->

<!--{IT_TRESC}-->
<div class="tresc">
{TRESC}
</div><!--/{IT_TRESC}-->

<!--{IT_LISTA}-->
<!--{IT_NAWI_G}-->
<table class="naw">
<tr>
 <td class="sort">
     <form class="sort-form jqTransform" method="post" id="navig">
        <div class="sort-type-container">
            <label class="top-sort-type-label" for="top-sort-type-select">{T_SORTUJ}</label>
            <div class="sort-type">
                <div class="form-element">
                <select id="top-sort-type-select" size="1" name="o" class="top-sort-type" onchange="$('#navig').submit()">
                    <option value="cena" {OP_CENA}>{T_OP_CENA}</option>
                    <option value="nazwa" {OP_NAZWA}>{T_OP_NAZWA}</option>
                    <option value="data" {OP_DATA}>{T_OP_DATA}</option>
                </select>
                </div>
            </div>
        </div>
        <div class="page-items-container">
            <label class="top-page-items-label" for="top-page-items-select">{T_POKAZ}</label>
            <div class="page-items">
                <div class="form-element">
                <select id="top-page-items-select" size="1" name="ppp" class="top-page-items" onchange="$('#navig').submit()">
                    <option value="20" {PPP_20}>20</option>
                    <option value="40" {PPP_40}>40</option>
                    <option value="80" {PPP_80}>80</option>
                    <option value="0"  {PPP_0}>{T_OP_WSZYSTKIE}</option>
		</select>
                </div>
            </div><!-- page-items -->
	</div><!-- page-items-container -->
        <div class="page-items-container" style="padding-top: 8px;padding-left: 7px;"> z {ILOSC}</div>
    </form><!-- sort-form -->                                        
 </td>
<td class="strony">{STRONY}</td>
</tr></table>{BANERCATEGORYBRANDS}<!--/{IT_NAWI_G}-->

<div class="produkt" id="list">
<!--{IT_PRODUKT_S}--> 
<div class="small"><!--{PRODUKT_S}-->4<!--/{PRODUKT_S}--><div class="clear"></div></div>
<!--/{IT_PRODUKT_S}-->

<!--{IT_PRODUKT_M}--> 
<div class="medium"><!--{PRODUKT_M}-->3<!--/{PRODUKT_M}--><div class="clear"></div></div>
<!--/{IT_PRODUKT_M}-->

<!--{IT_PRODUKT_L}--> 
<div class="large"><!--{PRODUKT_L}-->4<!--/{PRODUKT_L}--><div class="clear"></div></div>
<!--/{IT_PRODUKT_L}-->
<div class="clear"></div>
</div>

<!--{IT_NAWI_D}-->
<div id="sort-bottom" class="clear">
<table class="naw">
<tr>
    <td class="sort">
        <form class="sort-form jqTransform" method="post"  id="navid">
         <div class="sort-type-container">
            <label class="top-sort-type-label" for="bottom-sort-type-select" style="padding-top: 1px;">{T_SORTUJ}</label>
            <div class="sort-type">
                <select id="bottom-sort-type-select" size="1" name="o" class="top-sort-type NFOnChange" onchange="$('#navid').submit()">
                    <option value="cena" {OP_CENA}>{T_OP_CENA}</option>
                    <option value="nazwa" {OP_NAZWA}>{T_OP_NAZWA}</option>
                    <option value="data" {OP_DATA}>{T_OP_DATA}</option>
                </select>
            </div>
        </div>
        <div class="page-items-container">
            <label class="top-page-items-label" for="bottom-page-items-select" style="padding-top: 1px">{T_POKAZ}</label>
            <div class="page-items">
                <select id="bottom-page-items-select" size="1" name="ppp" class="top-page-items"  onchange="$('#navid').submit()">
                    <option value="20" {PPP_20}>20</option>
                    <option value="40" {PPP_40}>40</option>
                    <option value="80" {PPP_80}>80</option>
                    <option value="0"  {PPP_0}>{T_OP_WSZYSTKIE}</option>
                </select>
            </div><!-- page-items -->
	</div><!-- page-items-container -->
        <div class="page-items-container" style="padding-top: 0px;padding-left: 7px;line-height: 22px;"> z {ILOSC}</div>
        </form><!-- sort-form -->
    </td>
    <td class="strony">{STRONY}</td>
</tr>
</table>
</div>
<!--/{IT_NAWI_D}-->
<!--/{IT_LISTA}-->
<!--{IT_BRAK}-->
<div class="message">{T_KAT_PUSTA}</div>
<!--/{IT_BRAK}-->

</div>