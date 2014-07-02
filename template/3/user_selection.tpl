<div class="ramka">

<div class="regbez">
    <h1 class="cms-title2">{T_ZAKUPY_BEZ_REJESTRACJI}</h1>
    {T_KONTYNUUJ_SKLADANIE_ZAMOWIENIA_BEZ__}<br><br>
    <!-- <a href="{URL}{LANG}/zakup/bez/"><div class="but_bez_rejestracji">{T_KONTYNUUJ_BEZ_REJESTRACJI}</div></a> -->
    <a href="{URL}{LANG}/paymant/bez/"><div class="but_bez_rejestracji">{T_KONTYNUUJ_BEZ_REJESTRACJI}</div></a>
</div>
    
<div class="reglogin">
<h1 class="cms-title1">{T_MAM_JUZ_KONTO}</h1>
{ERRORL}
<form action="{URL}{LANG}/uzytkownik/selection/" method="post" class="jqTransform">
<input type="hidden" name="a" value="l" />
<input type="hidden" name="url" value="{FURL}"/>
<table class="rej" style="margin-bottom: 0px;">
<tr>
  <td><label>{T_EMAIL}:</label></td>
  <td>
     <!-- <div class="form-element"> -->
          <input type="text" class="txtm" name="lo_login" id="rejmail" value="{T_PODAJ_SWOJ_EMAIL}" onfocus="if(this.value=='{T_PODAJ_SWOJ_EMAIL}')this.value=''" onblur="if(this.value=='')this.value='{T_PODAJ_SWOJ_EMAIL}'"/>
     <!-- </div> -->
  </td>
  <td></td></tr>
<tr>
  <td><label>{T_HASLO}:</label></td>
  <td>
     <!-- <div class="form-element"> -->
          <input type="password" class="txtm" name="lo_haslo" id="rejpass" value="**********" onfocus="if(this.value=='**********')this.value=''" />
      <!-- </div> -->
  </td>
  <td>
      <div class="form-element"><input class="pushb" name="s_go" type="submit" value="ok" /></div>
  </td>
 </tr></table>
</form>
<label>{T_JEZELI_ZAPOMNIALES_PRZYPOMNIEC2}</label>
</div>

</div>
<div class="regmenu">
    <h1 class="cms-title2">{T_KORZYSCI_Z_REJESTRACJI}: </h1>
    <ul class="rejlist" style="margin-top: 0px;line-height: 18px;">
        <li>{T_HISTORIA_ZAMOWIEN}</li>
        <li>{T_OSZCZEDNOSC_CZASU__}</li>
        <li>{T_MOZESZ_OBSERWOWAC_PRODUKTY}</li>
    </ul>
    <h1 class="cms-title2">{T_PODATKOWO_PRZYSTEPUJAC_DO_GOMEZ_CLUB}: </h1>
    <ul class="rejlist" style="margin-top: 0px;line-height: 18px;">
        <li>{T_ATRAKCYJNY_SYSTEM_ZNIZEK}</li>
        <li>{T_GORZYSCI_AJKOSCIOWE}</li>
    </ul>
    <div class="regselectionmoregc"><a href="{URL}{LANG}/gomez_club/">{T_WIECEJ}</a></div>
</div>

<div class="regreg">
    <h1 class="cms-title1">{T_NIE_MASZ_KONTA}</h1>
    {T_ZAREJESTRUJ_SIE_ABY_SKORZYSTAC_Z__}
</div>

</div>