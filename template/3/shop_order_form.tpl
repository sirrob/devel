<script type="text/javascript">
    function step2()
    {
        //alert($('input[name=do_id]').val());
        return true;
    }
    
    function checkdoid(c)
    {
        //alert($('input[name=do_platnosc]:checked').val());
        $('input[name=do_platnosc]:checked').removeAttr('checked');
        //$('input[value='+c+']').attr('checked', 'checked');
        $('#dp'+c+'').attr('checked', 'checked');
    }        //alert($('#dp'+c+'').val());

</script>

<div class="steps">
<div class="step1">{T_KROK_1}</div>
<div class="step2">{T_KROK_2}</div>
<div class="step1">{T_KROK_3}</div>
<div class="step1">{T_KROK_4}</div>
</div>

<div class="separator"></div>
<strong>GOMEZ FREE DELIVERY</strong> - {T_WYSYLKA_DHL_GRATIS__} <a href="{URL}{LANG}/przesylka_dhl_gratis/">{T_CZYTAJ_WIECEJ}</a>
<div class="separator"></div>
<strong>{T_DARMOWA_WYSYLKA_DHL}</strong> - {T_BRAKUJE_CI_TYLKO} {BRAKHDL} PLN {T_BRAKUJE_CI_TYLKO_MORE}
<br><br>

<form action="{URL}{LANG}/summary/{ADDURL}" method="post" class="jqTransform" id="step2" onsubmit="return step2()">
{DOSMSG}
<div class="platnosc-box">
    
<div class="platnosc">
    <strong>{T_PRZELEW_NA_KONTO}</strong><br><br>
    <div style="line-height: 24px;"><input type="radio" name="do_id" value="4" {DOID7} onclick="checkdoid(2)"><label onclick="checkdoid(2)">{T_KURIER_DHL} - {DHLPRICE} PLN</label></div>
    <div style="line-height: 24px;"><input type="radio" name="do_id" value="5" {DOID6} onclick="checkdoid(2)"><label onclick="checkdoid(2)">{T_POCZTA_POLSKA} - 16 PLN</label></div><br><br>
    <div style="line-height: 24px;"><input type="radio" name="do_id" value="8" {DOID8} onclick="checkdoid(2)"><label onclick="checkdoid(2)">{T_PACZKOMAT_INPOST} - XX PLN</label></div><br><br>

    <img src="{URL}template/3/images/ico_krok2-1.jpg" alt="">

</div>
<div class="platnosc">
    <strong>{T_KARTA_PRZELEWY_24}{T_PLATNOSC_INTERNETOWA} </strong><br><br>
    <div style="line-height: 24px;"><input type="radio" name="do_id" value="4" {DOID4} onclick="checkdoid(3)"><label onclick="checkdoid(3)">{T_KURIER_DHL} - {DHLPRICE} PLN</label></div>
    <div style="line-height: 24px;"><input type="radio" name="do_id" value="5" {DOID5} onclick="checkdoid(3)"><label onclick="checkdoid(3)">{T_POCZTA_POLSKA} - 16 PLN</label></div><br>
    <img src="{URL}template/3/images/ico_krok2-2.jpg" alt="">
</div>
<div class="platnosc">
    <strong>{T_PLATNOSC_PRZY_ODBIORZE}</strong><br><br>
    <div style="line-height: 24px;"><input type="radio" name="do_id" value="3" {DOID3} onclick="checkdoid(1)"><label onclick="checkdoid(1)">{T_KURIER_DHL} - {DHLPRICE2} PLN</label></div>
    <div style="line-height: 24px;"><input type="radio" name="do_id" value="2" {DOID2} onclick="checkdoid(1)"><label onclick="checkdoid(1)">{T_POCZTA_POLSKA} - 22 PLN</label></div><br><br>
    <img src="{URL}template/3/images/ico_krok2-3.jpg" alt="">
</div>
<div class="platnosc">
    <strong>{T_ODBIUR_W_SKLEPIE}</strong><br><br>
    <div style="line-height: 24px;"><input type="radio" name="do_id" value="1" {DOID1} onclick="checkdoid(1)"><label onclick="checkdoid(1)"> 0 PLN</label></div><br><br><br>
    <img src="{URL}template/3/images/ico_krok2-4.jpg" alt="">
</div>
</div>
<br>



<h4 class="krok2-title">{T_ADRES_ODBIORCY}</h4><br>
{ERROR}

<input type="hidden" name="a" value="adres" />

{JAKIEDANE}

<br>
<div id="dadres" {DOH}>
<table class="rej" >
<tr>
    <td>{T_P_IMIE}<!--Nazwa odbiorcy-->:</td>
    <td><div class="form-element"><input type="text" name="do_nazwa" value="{DO_NAZWA}" maxlength="50"  class="txtl"/> <span>{T_KO_REQ}</span></div></td>
</tr>
<tr>
    <td>{T_P_NAZWISKO}<!--Nazwa odbiorcy-->:</td>
    <td><div class="form-element"><input type="text" name="do_nazwa2" value="{DO_NAZWA2}" maxlength="50"  class="txtl"/> <span>{T_KO_REQ}</span></div></td>
</tr>

<tr>
    <td>{T_MIASTO}:</td>
    <td><div class="form-element"><input type="text" name="do_miasto" value="{DO_MIASTO}" maxlength="50" class="txtl"/> <span>{T_KO_REQ}</span></div></td>
</tr>
<tr>
    <td>{T_KOD}:</td>
    <td><div class="form-element"><input type="text" name="do_kod" maxlength="15" value="{DO_KOD}" class="txts" style="width: 50px;" /> <span>{T_KO_REQ}</span></div></td>
</tr>
<tr>
    <td>{T_ULICA_NUMER}:</td>
    <td><div class="form-element"><input type="text" name="do_adres" maxlength="100" value="{DO_ADRES}" class="txtl" /> <span>{T_KO_REQ}</span></div></td>
</tr>
<tr>
    <td>E-mail:</td>
    <td><div class="form-element"><input type="text" name="dot_email" maxlength="100" value="{DO_EMAIL}" class="txtl" /> <span>{T_KO_REQ}</span></div></td>
</tr>
<tr>
    <td>{T_TELEFON}:</td>
    <td><div class="form-element"><input type="text" name="dot_tel" maxlength="100" value="{DO_TEL}" class="txtl" /> <span>{T_KO_REQ}</span></div></td>
</tr>
<tr>
    <td>{T_KRAJ}:</td>
    <td> <!-- style="width: 300px;width: 146px;" -->
        <div class="form-element">
            <select name="do_kraj"  class="txtl" size="1" style="width: 146px;z-index: 99999">{KRAJ}</select> <span>{T_KO_REQ}</span>
        </div>
    </td>
</tr>
   <table>
    <tr>

        <td><div class="form-element"><input type="checkbox" name="dot_reg" value="tak"/> TAK, zapoznałem się i akceptuję <a href="www.gomez.pl/pl/regulamin">warunki regulaminu.</a></div></td>
    </tr>
   </table>

</table>
</div>

<div class="form-element" style="z-index: 9">        
<h5 class="krok2-title">{T_UWAGI_DO_ZAMOWIENIA}</h5>
<textarea name="do_uwagi" style="height:100px; width:350px; font-size:11px;" cols="60" rows="7" id="do_uwagi" class="txtl">{DO_UWAGI}</textarea>
</div>
<div style="display: none">
<h5 class="krok2-title">{T_FORMY_PLATNOSCI}</h5><br>
<!--{IT_FORMA}-->
<div style="line-height: 24px;"><input type="radio" name="do_platnosc" id="dp{ID}" value="{ID}" {CHK} /><span class="text">{NAZWA}</span><br/><span class="text">{OPIS}</span></div>
<!--/{IT_FORMA}-->
</div>
<div class="form-element" style="text-align: right">
<input type="submit" name="s_w" value="{T_WSTECZ}" onclick="location='{URL}{LANG}/cart/'" class="pushb"/> 
<input type="submit" name="s_" value="{T_DALEJ}" class="pushb" />
</div>
</form><br>
<script type="text/javascript">
//document.getElementById('dadres').style.height='0px';
</script>