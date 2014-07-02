<!--{IT_TYTUL}--><h1>{NAZWA}</h1><!--/{IT_TYTUL}-->
<!--{IT_LOCAL}--><div class="mapa" style="clear:both">{T_TU_JESTES}: {LOCAL}</div><!--/{IT_LOCAL}-->
<!--{IT_ELEMENT}-->
<div class="katelement">
<!--Rozne warianty wyswietlenia przyciskow-->
<!--{IT_EL_BIG}--><div style="text-align:center;margin-bottom:4px;"><a href="{URL1}">{SRC1}</a></div><!--/{IT_EL_BIG}-->
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
<td><a href="{URL}w=s&amp;ppp=32" class="w1">W1</a> <a href="{URL}w=m&amp;ppp=21"  class="w2">W2</a> <a href="{URL}w=l&amp;ppp=14"  class="w3">W3</a></td>
<td>{T_PRO_LICZBA} {ILOSC}</td>
<td>{T_IDZDO} {IDZDO}</td>
<td> <select name="s" onchange="window.location='{URL}o='+this.options[this.selectedIndex].value+'';"><option value="d">{T_SORT}</option>
<option value="cena" {OP_CENA}>{T_SCENA}</option>
<option value="nazwa" {OP_NAZWA}>{T_SNAZWA}</option>
<option value="data" {OP_DATA}>{T_SDATA_DODANIA}</option>
</select></td>
<td class="strony">{STRONY}</td>
</tr></table><!--/{IT_NAWI_G}-->

<div class="produkt">
<!--{IT_PRODUKT_S}-->
<div class="small"><!--{PRODUKT_S}-->4<!--/{PRODUKT_S}--><div class="clear"></div></div>
<!--/{IT_PRODUKT_S}-->

<!--{IT_PRODUKT_M}-->
<div class="medium"><!--{PRODUKT_M}-->3<!--/{PRODUKT_M}--><div class="clear"></div></div>
<!--/{IT_PRODUKT_M}-->

<!--{IT_PRODUKT_L}-->
<div class="large"><!--{PRODUKT_L}-->2<!--/{PRODUKT_L}--><div class="clear"></div></div>
<!--/{IT_PRODUKT_L}-->
<div class="clear"></div>
</div>


<!--{IT_NAWI_D}-->
<table class="naw">
<tr>
<td><a href="{URL}w=s&amp;ppp=32" class="w1">W1</a> <a href="{URL}w=m&amp;ppp=21"  class="w2">W2</a> <a href="{URL}w=l&amp;ppp=10"  class="w3">W3</a></td>
<td>{T_PRO_LICZBA} {ILOSC}</td>
<td>{T_IDZDO} {IDZDO}</td>
<td><select name="s" onchange="window.location='{URL}o='+this.options[this.selectedIndex].value+'';"><option value="d">{T_SORT}</option>
<option value="cena" {OP_CENA}>{T_SCENA}</option>
<option value="nazwa" {OP_NAZWA}>{T_SNAZWA}</option>
<option value="data" {OP_DATA}>{T_SDATA_DODANIA}</option>
</select></td>
<td class="strony">{STRONY}</td>
</tr>
</table>
<!--/{IT_NAWI_D}-->
<!--/{IT_LISTA}-->
<!--{IT_BRAK}-->
<div class="message">{T_KAT_PUSTA}</div>
<!--/{IT_BRAK}-->