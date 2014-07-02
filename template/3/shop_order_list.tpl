<!--{IT_BRAK}-->
{PANELMENU}
<div class="separator"></div>
<div class="message">{T_BRAK_ZAMOWIEN}</div>
<!--/{IT_BRAK}-->
<!--{IT_LISTA}-->
{PANELMENU}
<table class="lista">
<tr class="rh">
    <td style="width:50px"><strong>{T_LP}</strong></td>
    <td><strong>{T_NUMER}</strong></td>
    <td><strong>{T_OP_DATA}</strong></td>
    <td><strong>{T_STATUS}</strong></td>
    <td style="width:100px"><strong>{T_WARTOSC_BRUTTO}</strong></td>
    <td style="width:50px"><strong>{T_ZOBACZ}</strong></td>
</tr>

<!--{IT_ITEM}-->
<tr class="r{MOD}"><td style="text-align:center">{LP}</td>
<td style="text-align:center"><a href="{URL}{LANG}/zamowienie/id/{ID}/">{NUMER}</a></td>
<td style="text-align:center">{DATA}</td><td style="text-align:center">{STATUS}</td>
<td style="text-align:right">{WARTOSC}</td>
<td style="text-align:center"><a href="{URL}{LANG}/zamowienie/id/{ID}">&raquo;</a></td></tr>
<!--/{IT_ITEM}-->
</table>

<table class="naw">
<tr>
<td>{T_PRO_LICZBA} {ILOSC}</td>
<td>{T_IDZDO} {IDZDO}</td>
<td class="strony">{STRONY}</td>
</tr>
</table>
<!--/{IT_LISTA}-->