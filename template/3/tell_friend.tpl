<div class="zdj_pow">{T_TELL_FRIEND}</div>

<div class="polec_d">
<!--{IT_ERROR}--><div class="messerr">{MESS}</div><!--/{IT_ERROR}-->
<!--{IT_FORM}-->
<form action="#" id="fpowiadom" name="fpowiadom" method="post" class="jqTransform">
<input type="hidden" name="a" value="po" />
<input type="hidden" name="po_url" id="po_url" value="{PO_URL}" />

<table class="polec_t">
<tr class="lista1"><td colspan='2'><strong>{T_TELL_FRIEND_YOUR_DATA}</strong></td>
</tr>
<tr><td class="blue">{T_TELL_FRIEND_FROM}</td>
<td><input type="edit" name="po_od" value="{PO_OD}" maxlength="text"   class="txt" /></td>
</tr>
<tr class="lista"><td class="blue">{T_TELL_FRIEND_YOUR_EMAIL}</td>
<td><input type="edit" name="po_od_email" value="{PO_OD_EMAIL}" maxlength="text"   class="txt" /></td>
</tr>
<tr><td colspan='2'><strong>{T_TELL_FRIEND_RECIPIENT_DATA}</strong></td>
</tr>
<tr><td class="blue">{T_TELL_FRIEND_TO}</td>
<td><input type="edit" name="po_do" value="{PO_DO}" maxlength="text"   class="txt" /></td>
</tr>
<tr  class="lista"><td class="blue">{T_TELL_FRIEND_RECIPIENT_EMAIL}</td>
<td><input type="edit" name="po_do_email" value="{PO_DO_EMAIL}" maxlength="text"   class="txt" /></td>
</tr>
<tr class="lista"><td colspan='2'><strong>{T_TELL_FRIEND_RECOMMENDED_LINK}:</strong> <span> {PO_URL}</span></td>

<tr><td class="blue">{T_TELL_FRIEND_COMMENTS}</td>
<td><textarea class="txta" name="po_info" id="po_info">{PO_INFO}</textarea>
</td>
</tr>
</table>

<input name="c_" value="{T_TELL_FRIEND_SEND}" class="pushb" type="submit"/><input name="c_" value="{T_TELL_FRIEND_CANCEL}" class="pushb" type="button" onclick="opener.focus();window.close();"/>

</form>
<!--/{IT_FORM}-->
<!--{IT_POTWIERDZ}--><br/><br/><center>{T_TELL_FRIEND_CONFIRMATION}</center><br/><br/>
<form action="#"><center><input type="button" onclick="opener.focus();window.close();" value="{T_TELL_FRIEND_CLOSE}" class="pushb" id="close"/></center></form><!--/{IT_POTWIERDZ}-->
</div>