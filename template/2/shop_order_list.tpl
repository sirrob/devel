<!--{IT_BRAK}-->
<div class="message">Brak zamówień</div>
<!--/{IT_BRAK}-->
<!--{IT_LISTA}-->
<table class="lista">
<tr class="rh"><td style="width:50px">L.p.</td><td>Numer</td><td>Data</td><td>Status</td><td style="width:100px">Wartość brutto</td><td style="width:50px">Zobacz</td></tr>

<!--{IT_ITEM}-->
<tr class="r{MOD}"><td style="text-align:center">{LP}</td>
<td style="text-align:center"><a href="zamowienie.htm?id={ID}">{NUMER}</a></td>
<td style="text-align:center">{DATA}</td><td style="text-align:center">{STATUS}</td>
<td style="text-align:right">{WARTOSC}</td>
<td style="text-align:center"><a href="zamowienie.htm?id={ID}">&raquo;</a></td></tr>
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