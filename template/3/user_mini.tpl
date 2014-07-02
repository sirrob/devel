<!--{IT_FORM}-->


<div id="forget-container"> <!-- a=haslo -->
    <a href="{URL}{LANG}/uzytkownik/haslo/">{T_ZAPOMNIALEM_HASLA}</a> / <a href="{URL}{LANG}/uzytkownik/">{T_ZAREJESTRUJ_SIE}</a>
</div>
<form action="" method="post" class="jqTransform"  id="login-form">
<input type="hidden" name="a" value="l" />
<div id="login-input-container"><input id="login-input" type="text" name="lo_login" value="{T_PODAJ_SWOJ_EMAIL}" onfocus="if(this.value=='{T_PODAJ_SWOJ_EMAIL}')this.value=''" onblur="if(this.value=='')this.value='{T_PODAJ_SWOJ_EMAIL}'" /></div>
<div id="password-input-container"><input id="passowrd-input" type="password" name="lo_haslo" value="**********" onfocus="if(this.value=='**********')this.value=''" /></div>
<!-- <input class="push" name="s_go" type="submit" value="" /> -->
<input type="image" src="{URL}images/login_submit_normal.png" name="submit" alt="Zaloguj" style="display: none" />
<a id="submit-pretender" class="submit" href="javascript:userminilogin();"></a>
<!-- <span class="bordeaux"><a href="javascript:userminilogin();" class="submit">a</a></span> -->
</form>

<!--/{IT_FORM}-->
<!--{IT_MENU}-->
<div id="forget-container">
<div class="userm">
{GOMEZCLUB}                             <!-- index.php?a=lo -->
<strong>{USER}</strong> |  <a href="{URL}{LANG}/uzytkownik/wyloguj/">{T_WYLOGUJ_SIE}</a> | 
<a href="{URL}{LANG}/uzytkownik/">{T_MOJE_KONTO}</a> | 
<a href="{URL}{LANG}/zamowienie/">{T_LISTA_ZAMOWIEN}</a>
</div>
</div>
<!--/{IT_MENU}-->