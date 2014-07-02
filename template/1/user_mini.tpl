<!--{IT_FORM}-->

<form action="" method="post">
<input type="hidden" name="a" value="l" />
<table>
<tr>
<td>LOGOWANIE</td>
<tr>
  <td><input type="text" class="txt" name="lo_login" value=" - podaj swój e-mail - " onfocus="if(this.value==' - podaj swój e-mail - ')this.value=''" onblur="if(this.value=='')this.value=' - podaj swój e-mail - '"/><input type="password" class="txt" name="lo_haslo" value="**********" onfocus="if(this.value=='**********')this.value=''" /><input class="push" name="s_go" type="submit"value="ok" /></td>
 </tr>
 <tr><td><a href="uzytkownik.htm">Zarejestruj się</a><a href="uzytkownik.htm?a=haslo">Zapomniałem hasła</a>
</td></tr></table>
</form>

<!--/{IT_FORM}-->
<!--{IT_MENU}-->
<table class="usert">
<tr>
<td>
Moje konto<br/>
<strong>{USER}</strong><br/>
<a href="index.php?a=lo">Wyloguj się</a>
</td>
<td>
<a href="uzytkownik.htm">Moje dane</a><br/>
<a href="zamowienie.htm">Lista zamówień</a>
</td></tr></table>
<!--/{IT_MENU}-->