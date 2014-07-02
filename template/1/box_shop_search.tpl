<form action="sklep,szukaj.htm" method="get">
<input type="hidden" name="a" value="s" />
<select name="ska" id="szska" class="txt">
<option value="-1" id="szska0">-{T_KATEGORIA}-</option>
{KATEGORIA}
</select><br/>

<select name="spd" id="szspd" class="txt">
<option value="-1" id="szspd0">-{T_PRODUCENT}-</option>
{PRODUCENT}
</select>
<!--
<select name="sro" id="szsro"  class="txt">
<option value="-1" id="szsro0">-{T_ROZMIAR}-</option>
</select>
-->
<input type="text" name="sna" maxlength="30" value="{SNA}"  class="txt" />
<input type="submit" name="s_" value="{T_SZUKAJ}" class="push" />
</form>