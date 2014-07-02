<div class="ramka">
<h1>{T_PRZYPOMNIENIE_HASLA}</h1>
<!--{IT_FORM}-->
{ERROR}
<form action="{URL}uzytkownik/" method="post" class="jqTransform">
<input type="hidden" name="a" value="haslo" class="txtm"/>
<div><br>
    <span style="float: left;font-family: tahoma;font-size: 12px;line-height: 20px">{T_ABY_OTRZYMAC_HASLO}: </span>
    <input type="text" name="email" class="txtm"/>
    <input type="submit" value="ok" class="push" />
</div>
</form>
<!--/{IT_FORM}-->
<!--{IT_POTWIERDZ}-->
<div class="message">{T_NOWE_HASLO_WYSLANE}</div>
<!--/{IT_POTWIERDZ}-->
<div class="clear"></div>
</div>