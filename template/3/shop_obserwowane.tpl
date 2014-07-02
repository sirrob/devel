<!--{IT_BRAK}-->
{PANELMENU}
<div class="separator"></div>
<div class="message">{T_BRAK_OBSERWOWANYCH_PRODUKTOW}</div>
<!--/{IT_BRAK}-->
<!--{IT_LISTA}-->
{PANELMENU}
<table class="basket" border="1" style="width: 100%;">
<tr>
    <td><strong>{T_LP}</strong></td>
    <td colspan="2"><strong>{T_PRODUKT}</strong></td>
    <td style="text-align: center"><strong>{T_KOSZYK_KWOTA}</strong></td>
    <td style="text-align: center"><strong>{T_NL_USUN}</strong></td>
    <td style="padding-right: 0px;text-align: right"><strong>{T_ZOBACZ}</strong></td>
</tr>

<!--{IT_ITEM}-->
<tr class="r{MOD}">
    <td style="width: 20px;">{LP}</td>
    <td style="width: 90px;padding:0px;"><a href="{URL}{LANG}/produkt/{ID}/"><img src="{IMG}" alt="" style="width: 80px;"></a></td>
    <td style="width: 220px;">
        {NAZWA} <strong>(roz. {ATRYBUT})</strong><br/>
	{INFO}<br/>
	<!--{T_CENA}: {CENA1} zł
	 {CENA2} -->
	<input type="hidden" id="cena_{ID}_{AT_ID}" value="{CENAFORM}">
    </td>
    <td id="wartosc_{ID}_{AT_ID}" style="text-align: center">{WARTOSC} PLN</td>
    <td style="text-align: center;"><strong><a href="{URL}{LANG}/uzytkownik/obserwowane/{ID}/"><img src="{URL}template/3/images/but_drop.jpg" alt=""></a></strong></td>
    <td style="width: 20px;padding-left: 14px;padding-right: 0px;text-align: right"><a href="{URL}{LANG}/produkt/{ID}/"><img src="{URL}template/3/images/but_go.jpg" alt=""></a></td>
</tr>
<!--/{IT_ITEM}-->
</table>
<!--
<table class="naw">
<tr>
<td>{T_PRO_LICZBA} {ILOSC}</td>
<td>{T_IDZDO} {IDZDO}</td>
<td class="strony">{STRONY}</td>
</tr>
</table> -->
<!--/{IT_LISTA}-->