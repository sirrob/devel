<div class="zdj_pow">Poleć znajomemu</div>

<div class="polec_d">
<!--{IT_ERROR}--><div class="messerr">{MESS}</div><!--/{IT_ERROR}-->
<!--{IT_FORM}-->
<form action="#" id="fpowiadom" name="fpowiadom" method="post">
<input type="hidden" name="a" value="po" />
<input type="hidden" name="po_url" id="po_url" value="{PO_URL}" />

<table class="polec_t">
<tr class="lista1"><td colspan='2'><strong>Twoje dane</strong></td>
</tr>
<tr><td class="blue">Od:</td>
<td><input type="edit" name="po_od" value="{PO_OD}" maxlength="text"   class="txt" /></td>
</tr>
<tr class="lista"><td class="blue">Twój e-mail:</td>
<td><input type="edit" name="po_od_email" value="{PO_OD_EMAIL}" maxlength="text"   class="txt" /></td>
</tr>
<tr><td colspan='2'><strong>Dane odbiorcy</strong></td>
</tr>
<tr><td class="blue">Do:</td>
<td><input type="edit" name="po_do" value="{PO_DO}" maxlength="text"   class="txt" /></td>
</tr>
<tr  class="lista"><td class="blue">E-mail odbiorcy:</td>
<td><input type="edit" name="po_do_email" value="{PO_DO_EMAIL}" maxlength="text"   class="txt" /></td>
</tr>
<tr class="lista"><td colspan='2'><strong>Polecany link</strong><br /><span>{PO_URL}</span></td>

<tr><td class="blue">Komentarz:</td>
<td><textarea class="txta" name="po_info" id="po_info">{PO_INFO}</textarea>
</td>
</tr>
</table>
<div class="przyc">
<p class="ppush"><input name="c_" value="anuluj" class="pushb" type="button" onclick="opener.focus();window.close();"/>&nbsp;<input name="c_" value="wyślij" class="pushb" type="submit"/></p></form>
<!--/{IT_FORM}-->
<!--{IT_POTWIERDZ}--><br/><br/><center>Wiadomość została wysłana do odbiorcy.</center><br/><br/>
<form action="#"><center><input type="button" onclick="opener.focus();window.close();" value="zamknij &rsaquo;" class="pushb" id="close"/></center></form><!--/{IT_POTWIERDZ}--></div>
</div>