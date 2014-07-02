<!--{IT_STATUS}-->
<table class="zamowienie">
<tr><td style="width:130px"><strong>{T_NUMER_ZAMOWIENIA}: </strong></td><td ><strong>{NUMER}</strong></td></tr>
<tr><td ><strong>{T_DATA_ZAMOWIENIA}:</strong></td><td ><strong>{DATA}</strong></td></tr>
<tr><td ><strong>{T_STATUS_ZAMOWIENIA}: </strong></td><td ><strong>{STATUS}</strong></td></tr>
</table>
<!--/{IT_STATUS}-->

{STEPS}

<div class="separator"></div>

<h5 class="ztitle">{T_ZAMAWIAJACY}</h5>
<span class="ztxt">
{NAZWA}<br/>
<!--{IT_NIP}-->NIP: {NIP}<br/><!--/{IT_NIP}-->
{ADRES} </span>
<h5 class="ztitle">{T_ADRES_DOSTAWY}</h5>
<span class="ztxt">
{DO_NAZWA}<br/>
{DO_ADRES} </span>
<h5 class="ztitle">{T_TRESC_ZAMOWIENIA}</h5>
<table class="zamowienie">
    <tr><td style="width:30px;"><strong>{T_LP}</strong></td><td><strong>{T_PRODUKT}</strong></td><td><strong>{T_KOSZYK_ILE}</strong></td><td class="last"><strong>{T_KOSZYK_KWOTA}</strong></td></tr>
<!--{IT_ITEM}-->
<tr class="r{MOD}"><td>{LP}</td><td>
        {NAZWA} <strong>({ROZMIAR_LABEL} {ATRYBUT})</strong><br/>{INFO}<br/>{T_CENA}: {CENA} PLN
    </td><td>{ILE}</td><td align="right" class="last">{WARTOSC} PLN</td></tr>
<!--/{IT_ITEM}-->

<tr><td colspan="3">{T_SPOSOB_DOSTAWY}: {DOSTAWA}</td>
    <td id="wartosc_do" align="right" class="last">{WARTOSC_DO} PLN</td></tr>
<tr><td colspan="3" align="right">{DROPDOWN}</td></tr>
<tr><td colspan="3">{T_FORMA_PLATNOSCI}: {PLATNOSC}</td><td align="right">-</td></tr>

<tr><td colspan="3"><strong>{T_KOSZYK_SUMA}:</strong></td><td id="suma" class="last" align="right">{SUMA} PLN</td></tr>
</table>
<!--{IT_ACTION}-->
<form action="{URL}{LANG}/finalizing/{ADDURL}" method="post" class="jqTransform">
<input type="hidden" name="a" value="podsumowanie" />
<div class="form-element" style="text-align: right">
<input type="submit" name="s1" value="{T_WSTECZ}" onclick="location.href='{URL}{LANG}/summary/{ADDURL}'" class="pushb"/> 
<input type="submit" name="s2" value="{T_WYSLIJ}"  class="pushb" /> 
</div>
</form><br><br>
<!--/{IT_ACTION}-->
{IT_POST_PAY}