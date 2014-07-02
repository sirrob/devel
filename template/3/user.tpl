<div class="ramka">
<!--{IT_POTWIERDZ}-->
<h1>{T_NOWE_KONTO}</h1>
<div class="message"><br>{T_DZIEKUJEMY_PO_REJESTRACJI}<br></div>
<!--/{IT_POTWIERDZ}-->
<!--{IT_LOGIN}-->
<h1>{T_MAM_JUZ_KONTO}</h1>
{ERRORL}
<form action="{URL}{LANG}/uzytkownik/" method="post" class="jqTransform">
<input type="hidden" name="a" value="l" />
<input type="hidden" name="url" value="{URLform}"/>
<table class="rej">
<tr>
  <td><label>{T_EMAIL}:</label></td>
  <td>
      <div class="form-element">
          <input type="text" class="txtm" name="lo_login" id="rejmail" value="{T_PODAJ_SWOJ_EMAIL}" onfocus="if(this.value=='{T_PODAJ_SWOJ_EMAIL}')this.value=''" onblur="if(this.value=='')this.value='{T_PODAJ_SWOJ_EMAIL}'"/>
      </div>
  </td>
  <td></td></tr>
<tr>
  <td><label>{T_HASLO}:</label></td>
  <td>
      <div class="form-element">
          <input type="password" class="txtm" name="lo_haslo" id="rejpass" value="**********" onfocus="if(this.value=='**********')this.value=''" />
      </div>
  </td>
  <td>
      <div class="form-element"><input class="pushb" name="s_go" type="submit" value="ok" /></div>
  </td>
 </tr></table>
</form>
<div>
<label>{T_JEZELI_ZAPOMNIALES_PRZYPOMNIEC}</label>
</div>
<!--/{IT_LOGIN}-->
<!--{IT_FORM}-->
<h1 style="margin-top:15px;">{TYTUL_FORM}</h1>
{PANELMENU}
{ERRORR}
<!--{IT_INTRO}--><div style="padding-top: 12px">
<label><strong>{T_DZIEKI_REJESTRACJI}:</strong></label>
<ul class="rejlist">
    <li>{T_BEDZIESZ_MOGL_SPRAWDZIC_}</li>
    <li>{T_ZAOSZCZEDZISZ_CZAS_PODCZAS_}</li>
</ul>
</div><!--/{IT_INTRO}-->
<form action="{URL}{LANG}/uzytkownik/" method="post" class="jqTransform" >
    <input type="hidden" name="a" value="zachowaj" />
    <input type="hidden" name="url" value="{URLform}" />
    <table  class="rej" border="1">
    <tr>
        <td><label>{T_KO_EMAIL}:</label></td>
        <td>
            <div class="form-element">
                <input type="text" name="ko_email" id="ko_email" value="{KO_EMAIL}" maxlengtd="30" class="txtm" /> <span>{T_KO_REQ}</span>{EMAIL_MSG}
            </div>
        </td>
    </tr>
<!--{IT_HASLO_OLD}-->
    <tr>
        <td><label>{T_POPRZEDNIE_HASLO}:</label></td>
        <td>
            <div class="form-element">
                <input type="password" name="ol_haslo" id="ol_haslo" value="" maxlengtd="15" class="txtm" />{PASS_MSG}
            </div>
        </td>
    </tr><!--/{IT_HASLO_OLD}-->
    <tr>
        <td><label>{T_HASLO}:</label></td>
        <td>
            <div class="form-element">
                <input type="password" name="ko_haslo" id="ko_haslo" value="" maxlengtd="15" class="txtm" /> <!--{IT_HASLO_REQ}--><span>{T_KO_REQ}</span><!--/{IT_HASLO_REQ}-->{PASS_MSG}
            </div>
        </td>
    </tr>
    <tr>
        <td><label>{T_POWTORZ_HASLO}:</label></td>
        <td>
            <div class="form-element">
                <input type="password" name="re_haslo" id="re_haslo" value="" maxlengtd="15" class="txtm" /> <!--{IT_HASLO2_REQ}--><span>{T_KO_REQ}</span><!--/{IT_HASLO2_REQ}-->{PASS_MSG}
            </div>
        </td>
    </tr>
    <tr>
        <td><label>{T_KO_IMIE}:</label></td>
        <td>
            <div class="form-element">
                <input type="text" name="ko_nazwa" id="ko_nazwa" value="{KO_NAZWA}" maxlengtd="50" class="txtl" /> <span>{T_KO_REQ}</span>{NAZWA_MSG}
            </div>
        </td>
    </tr>
    <tr>
        <td><label>{PLEC}</label></td>
        <td>
            <div class="form-element" style="float: left">
                <input type="radio" name="ko_sex" value="k" {ko_sexk}> <label style="float: left;line-height: 24px;">{T_KOBIETA}</label>
                <input type="radio" name="ko_sex" value="m" {ko_sexm}> <label style="float: left;line-height: 24px;">{T_MEZCZYZNA}</label>
                <span class="sreq">{T_KO_REQ}</span>{PLEC_MSG}
            </div>
        </td>
    </tr>
    <tr>
        <td><label>{T_DATA_URODZENIA}: </label></td>
        <td>
            <div class="form-element">
                <input type="text" name="ko_year" id="ko_year" value="{KO_YEAR}" />
                <input type="text" name="ko_month" id="ko_month" value="{KO_MONTH}" />
                <input type="text" name="ko_day" id="ko_day" value="{KO_DAY}" />
            </div>
        </td>
    </tr>
    <tr>
        <td><label>{T_FIRMA}:</label></td>
        <td>
            <div class="form-element">
                <input type="text" name="ko_firma" id="ko_firma" value="{KO_FIRMA}" maxlengtd="50" class="txtl"/>
            </div>
        </td>
    </tr>
    <tr>
        <td><label>{T_NIP}:</label></td>
        <td>
            <div class="form-element">
                <input type="text" name="ko_nip" id="ko_nip" value="{KO_NIP}" maxlengtd="15" class="txtm" /> 
            </div>
        </td>
    </tr>
    <tr>
        <td><label>{T_MIASTO}:</label></td>
        <td>
            <div class="form-element">
                <input type="text" name="ko_miasto" id="ko_miasto" value="{KO_MIASTO}" maxlengtd="50" class="txtm" /> <span>{T_KO_REQ}</span>{MIASTO_MSG}
            </div>
        </td>
    </tr>
    <tr>
        <td><label>{T_KOD}:</label></td>
        <td>
            <div class="form-element">
                <input type="text" name="ko_kod" id="ko_kod" value="{KO_KOD}" maxlengtd="20" class="txts" /> <span>{T_KO_REQ}</span>{KOD_MSG}
            </div>
        </td>
    </tr>
    <tr>
        <td><label>{T_ULICA}:</label></td>
        <td style="width: 500px">
            <div class="form-element" style="float: left">
                <input type="text" name="ko_ulica" id="ko_ulica" value="{KO_ULICA}" maxlengtd="50" class="txtm" /> 
            </div>
            <div class="form-element" style="float: left">
                <div style="float:left;padding-left:12px;padding-right: 12px">{T_DOM} </div><input type="text" name="ko_ulica_dom" id="ko_ulica_dom" value="{KO_ULICA_DOM}" maxlengtd="10" class="txts" /> 
            </div>
            <div class="form-element" style="float: left">
                <div style="float:left;padding-left:12px;padding-right: 12px">{T_LOK_} </div><input type="text" name="ko_ulica_lok" id="ko_ulica_lok" value="{KO_ULICA_LOK}" maxlengtd="10" class="txts" /> <span>{T_KO_REQ}</span>
            </div>{ULICA_MSG}
        </td>
    </tr>
    <tr>
        <td><label>{T_KRAJ}</label></td>
        <td>
            <div class="form-element">
                <select name="ko_kraj" class="txtl" size="1" style="width: 156px">
                    {KO_KRAJ}
                </select> 
                <span>{T_KO_REQ}</span>
            </div>
        </td>
    </tr>
    <tr>
        <td><label>{T_TELEFON}:</label></td>
        <td>
            <div class="form-element">
                <input type="text" name="ko_telefon" id="ko_telefon" value="{KO_TELEFON}" maxlengtd="30"  class="txtm"/> <span>{T_KO_REQ}</span>{TELEFON_MSG}
            </div>
        </td>
    </tr>
<!--{IT_SKAD}--><tr>
        <td style="vertical-align: top;"><label>{T_TRAFILES_DO_NAS_Z}:</label></td>
        <td>
            <div class="form-element" style="width: 600px;">
                <select name="ko_skad" class="txtm" size="1" style="width: 156px;" onchange="jskoskadinne(this.value);">
                    <option value='-1'>{WYBIERZ}</option>
                    {KO_SKAD}
                </select>
                <span>{T_KO_REQ}</span>{SKAD_MSG}
            </div>
            <div class="" id="koskadinne"><br><label style="float: left">{KO_SKAD_INNE_JAKIE}? </label><input type="text" name="ko_skad_inne" value="{KO_SKAD_INNE}"></div>
        </td>
    </tr><!--/{IT_SKAD}-->
    
    <tr {STYLE_GC_LOCK}>
        <td><label>{T_NUMER_KARTY_GC}:</label></td>
        <td>
            <div class="form-element">
                <input type="text" name="ko_CLUB_NUMER" id="ko_CLUB_NUMER" value="{KO_CLUB_NUMER}" maxlengtd="30"  class="txtm"/>
            </div>
        </td>
    </tr>
	
	<tr>
        <td><label>{T_JEZYK_KOMUNIKACJI}</label></td>
        <td>
            <div class="form-element">
                <select name="ko_lang" class="txtl" size="1" style="width: 156px">
                    {KO_JEZYK_KOMUNIKACJI}
                </select> 
                <span>{T_KO_REQ}</span>
            </div>
        </td>
    </tr>
	
    </table>

<div id="sort-bottom" class="clear" {STYLE_GC_LOCK}>
    <table class="rej" border="1">
    <tr>
	<td colspan="1" style="width: 400px;">
            <!-- <div class="form-element" style="border: solid 1px red;margin:0px;"> -->
                <input type="checkbox" name="ko_gomezclub" id="ko_gomezclub" {KO_GOMEZCLUB} value="y"> <img src="{URL}template/3/images/gomez_club_icon.png" alt="Gomez Club"> <label style="float: left;">{T_PROGRAM_GC_SYSTEM_RABATOWY} </label>
            <!-- </div> -->
	</td>
    </tr>
	
    </table>
<!-- info o gomez club -->                            
</div><!-- sort-top --> 

<table class="rej">
<tr>
    <td colspan="2" style="width: 700px;">
        <input type="checkbox" name="ko_zgoda" id="ko_zgoda" {KO_ZGODA}/>
        <label style="width: 700px;">{T_TAK_ZAPOZNALEM_REGULAMIN_}. {ZGODA_MSG}</label>
    </td>
</tr>
<tr>
    <td colspan="2" style="padding-left: 240px;padding-top: 12px"><input type="submit" value="{T_WYSLIJ}" class="push" /></td>
</tr>
</table>


</form><br><br>
<!--/{IT_FORM}-->
<div class="clear"></div>
</div>