<!--{IT_STATUS}-->
<table class="zamowienie">
<tr><td style="width:130px"><strong>Numer zamówienia: </strong></td><td ><strong>{NUMER}</strong></td></tr>
<tr><td ><strong>Data zamówienia:</strong></td><td ><strong>{DATA}</strong></td></tr>
<tr><td ><strong>Status zamówienia: </strong></td><td ><strong>{STATUS}</strong></td></tr>
</table>
<!--/{IT_STATUS}-->


<h2>Zamawiający</h2>
{NAZWA}<br/>
<!--{IT_NIP}--}NIP: {NIP}<br/><!--/{IT_NIP}-->
{ADRES}
<h2>Adres dostawy</h2>
{DO_NAZWA}<br/>
{DO_ADRES}
<h2>Treść zamówienia</h2>
<table class="zamowienie">
<tr><td style="width:30px;"><strong>L.p.</strong></td><td><strong>Produkt</strong></td><td><strong>Ilość</strong></td><td><strong>Wartość</strong></td></tr>
<!--{IT_ITEM}-->
<tr class="r{MOD}"><td>{LP}</td><td>
{NAZWA} (roz. {ATRYBUT})<br/>{INFO}<br/>{T_CENA}: {CENA} zł
</td><td>{ILE}</td><td align="right">{WARTOSC} zł</td></tr>
<!--/{IT_ITEM}-->

<tr><td colspan="3">Sposób dostawy: {DOSTAWA}</td>
<td id="wartosc_do" align="right">{WARTOSC_DO} zł</td></tr>
<tr><td colspan="3">Forma płatności: {PLATNOSC}</td><td align="right">-</td></tr>

<tr><td colspan="3"><strong>{T_KOSZYK_SUMA}:</strong></td><td id="suma" align="right">{SUMA} zł</td></tr>
</table>
<!--{IT_ACTION}-->
<form action="zakup.htm" method="post">
<input type="hidden" name="a" value="podsumowanie" />
<input type="submit" name="s2" value="{T_WYSLIJ}"  class="pushb" style="float:right; width:100px;" /> 
<input type="button" name="s1" value="{T_WSTECZ}" onclick="window.location='zakup.htm'"class="pushb" style="float:right; width:100px; margin-right:10px;"/> 
</form>
<!--/{IT_ACTION}-->
{IT_POST_PAY}