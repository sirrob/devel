<!--{IT_POTWIERDZ}-->
<h1>Nowe konto</h1>
<div class="message">Gratulujemy! Twoje dane zostały zarejestrowanie, aby aktywować konto i dokonać zakupu, kliknij na link, który został wysłany na podany przez ciebie adres e-mail.</div>
<!--/{IT_POTWIERDZ}-->
<!--{IT_LOGIN}-->
<h1>Mam już konto</h1>
{ERRORL}
<form action="uzytkownik.htm" method="post">
<input type="hidden" name="a" value="l" />
<input type="hidden" name="url" value="{URL}"/>
<table class="rej">
<tr>
  <td>e-mail:</td>
  <td><input type="text" class="txtm" name="lo_login" value=" - podaj swój e-mail - " onfocus="if(this.value==' - podaj swój e-mail - ')this.value=''" onblur="if(this.value=='')this.value=' - podaj swój e-mail - '"/></td>
  <td></td></tr>
<tr>
  <td>hasło:</td>
  <td><input type="password" class="txtm" name="lo_haslo" value="**********" onfocus="if(this.value=='**********')this.value=''" /></td>
  <td><input class="pushb" name="s_go" type="submit"value="ok" /></td>
 </tr></table>
</form>
<div>
Jeżeli zapomniałeś jakie masz hasło, możemy je Tobie <a href="uzytkownik.htm?a=haslo">przypomnieć</a>.
</div>
<!--/{IT_LOGIN}-->
<!--{IT_FORM}-->
<h1 style=margin-top:15px;>{TYTUL_FORM}</h1>
{ERRORR}
<!--{IT_INTRO}--><div>
<b>Dzięki rejestracji:</b>
<ul>
<li>będziesz mógł sprawdzać stan realizacji swoich zamówień</li>
<li>zaoszczędzisz czas podczas następnych zakupów,<br/>bo nie będziesz już musiał podawać swoich danych</li>
</ul>
</div><!--/{IT_INTRO}-->
<form action="uzytkownik.htm" method="post" >
<input type="hidden" name="a" value="zachowaj" />
<input type="hidden" name="url" value="{URL}" />
<table  class="rej">
<tr><td>E-mail:</td>
<td><input type="text" name="ko_email" id="ko_email" value="{KO_EMAIL}" maxlengtd="30" class="txtm" /> <span>wymagane</span></td>
</tr>
<!--{IT_HASLO_OLD}-->
<tr><td>Poprzednie hasło: </td>
<td><input type="password" name="ol_haslo" id="ol_haslo" value="" maxlengtd="15" class="txtm" /></td>
</tr><!--/{IT_HASLO_OLD}-->
<tr><td>Hasło:</td>
<td><input type="password" name="ko_haslo" id="ko_haslo" value="" maxlengtd="15" class="txtm" /> <!--{IT_HASLO_REQ}--><span>wymagane</span><!--/{IT_HASLO_REQ}--></td>
</tr>
<tr><td>Powtórz hasło:</td>
<td><input type="password" name="re_haslo" id="re_haslo" value="" maxlengtd="15" class="txtm" /> <!--{IT_HASLO2_REQ}--><span>wymagane</span><!--/{IT_HASLO2_REQ}--></td>
</tr>


<tr><td>Imię i nazwisko:</td>
<td><input type="text" name="ko_nazwa" id="ko_nazwa" value="{KO_NAZWA}" maxlengtd="50" class="txtl" /> <span>wymagane</span></td>
</tr>
<tr><td>Firma:</td>
<td><input type="text" name="ko_firma" id="ko_firma" value="{KO_FIRMA}" maxlengtd="50" class="txtl"/> </td>
</tr>
<tr><td>NIP:</td>
<td><input type="text" name="ko_nip" id="ko_nip" value="{KO_NIP}" maxlengtd="15" class="txtm" /> </td>
</tr>
<tr><td>Miasto:</td>
<td><input type="text" name="ko_miasto" id="ko_miasto" value="{KO_MIASTO}" maxlengtd="50" class="txtm" /> <span>wymagane</span></td>
</tr>
<tr><td>Kod:</td>
<td><input type="text" name="ko_kod" id="ko_kod" value="{KO_KOD}" maxlengtd="15" class="txts" /> <span>wymagane</span></td>
</tr>
<tr><td>Ulica:</td>
<td><input type="text" name="ko_ulica" id="ko_ulica" value="{KO_ULICA}" maxlengtd="50" class="txtm" /> dom <input type="text" name="ko_ulica_dom" id="ko_ulica_dom" value="{KO_ULICA_DOM}" maxlengtd="10" class="txts" /> lok.<input type="text" name="ko_ulica_lok" id="ko_ulica_lok" value="{KO_ULICA_LOK}" maxlengtd="10" class="txts" /> <span>wymagane</span></td>
</tr>
<tr><td>Kraj</td>
<td><select name="ko_kraj" class="txtl" >
{KO_KRAJ}
</select>
 <span>wymagane</span></td>
</tr>
<tr><td>Telefon:</td>
<td><input type="text" name="ko_telefon" id="ko_telefon" value="{KO_TELEFON}" maxlengtd="30" /> <span>wymagane</span></td>
</tr>
<!--{IT_SKAD}--><tr><td>Trafiłeś do nas z:</td>
<td><select name="ko_skad" class="txtm"><option value='-1'>-wybierz-</option>
{KO_SKAD}
</select>
 <span>wymagane</span></td>
</tr><!--/{IT_SKAD}-->

<tr><td colspan="2"><input type="checkbox" name="ko_zgoda" id="ko_zgoda" {KO_ZGODA}/>
 TAK, zapoznałem się i akceptuję <a href="/strona.php?st_id=13" target="_self">warunki regulaminu</a>.</td>
</tr>
</table>



<input type="submit" value="{T_WYSLIJ}" class="pushb" />
</form>
<!--/{IT_FORM}-->