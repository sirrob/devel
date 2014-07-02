<h5>Adres odbiorcy</h5>
{ERROR}
<form action="zakup.htm" method="post">
<input type="hidden" name="a" value="adres" />
<input type="radio" name="do_odb" id="do1" value="1"{DO1} onclick="document.getElementById('dadres').style.display='none';"><label for="do1"> Tak, jak w moich danych</label></br/>
<input type="radio" name="do_odb" id="do2"{DO2} value="2"onclick="document.getElementById('dadres').style.display='block';"><label for="do2"> Użyj innych danych</label><br/>
<div id="dadres" {DOH}>
<table class="rej">
<tr><td>Nazwa odbiorcy:</td><td><input type="text" name="do_nazwa" value="{DO_NAZWA}" maxlength="50"  class="txtl"/> <span>wymagane</span></td></tr>
<tr><td>Kraj:</td><td><select name="do_kraj"  class="txtl">{KRAJ}</select> <span>wymagane</span></td></tr>
<tr><td>Miasto:</td><td><input type="text" name="do_miasto" value="{DO_MIASTO}" maxlength="50"  class="txtl"/> <span>wymagane</span></td></tr>
<tr><td>Kod:</td><td><input type="text" name="do_kod" maxlength="15" value="{DO_KOD}"  class="txts"/></td></tr>
<tr><td>Ulica, numer:</td><td><input type="text" name="do_adres" maxlength="100" value="{DO_ADRES}" class="txtl" /> <span>wymagane</span></td></tr>
</table>
</div>
<h5>Uwagi do zamówienia</h5>
<textarea name="do_uwagi" style="height:100px; width:350px; font-size:11px;" class="txtl">{DO_UWAGI}</textarea>

<h5>Forma płatności</h5>
<!--{IT_FORMA}--><input type="radio" name="do_platnosc" value="{ID}"{CHK}/>{NAZWA}<br/>{OPIS}<!--/{IT_FORMA}-->
<input type="submit" name="s_" value="{T_DALEJ}" class="pushb" style="float:right; " />
<input type="button" name="s_w" value="{T_WSTECZ}" onclick="window.location='koszyk.htm'" class="pushb" style="float:right; margin-right:10px;"/> 
</form>