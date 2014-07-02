<!--{IT_EMPTY}-->
<div class="message">{T_KOSZYK_PUSTY}</div>
<!--/{IT_EMPTY}-->

<script type="text/javascript">
    $(function(){

        $('#koszyk').submit(function() {
            var checkAcceptance = $('input[name=withAcceptRules]').val();
            var tmp = $('input[name=acceptRules]').is(':checked')

            if (checkAcceptance == 1) {
                if (tmp) {
                    return true;
                } else {
                    alert('{T_RULES_MESSAGE}');
                    return false;
                }
            } else {
                return true;
            }
        });
    });
</script>

<!--{IT_FORM}-->
<script type="text/javascript">
var error = false;
var GRATIS = Array();
{GRATIS}
var KOSZT = Array();
{KOSZT}
function conv2num2(str) 
{
    var len = str.length;
    if(len==2) return "0."+str;
    else if(len==1) return "0.0"+str;
    return str.substr(0,len-2)+"."+str.substr(len-2);
}

function m_przelicz() 
{
    var obj = document.getElementById('koszyk');
    var suma=0;   
    for(i=0; i<obj.elements.length; i++)
    {
        if(obj.elements[i].id.substr(0,7)=='ko_ile_') 
    {
            id = obj.elements[i].id.substr(7);
            if(isNaN(obj.elements[i].value) || obj.elements[i].value.indexOf(".")>=0 || obj.elements[i].value.indexOf("-")>=0) 
            {
                obj.elements[i].className="iloscerror";
                alert('Nieprawidłowy format ilości produktów');
                error = true;
                obj.elements[i].focus();
                return;
            } else 
            {
                obj.elements[i].className="ilosc";
                error = false;
                wartosc = (1*obj.elements[i].value)*(1*document.getElementById('cena_'+id).value)*100;
                wartosc = Math.round(wartosc);
                suma=Math.round(suma+wartosc);
                //wartosc = (wartosc/100);
                document.getElementById('wartosc_'+id).innerHTML = conv2num2(wartosc.toString())+' zł';
            }
        }
    }

    document.getElementById('suma').innerHTML = '<strong>'+conv2num2(suma.toString())+' zł</strong>';
    document.getElementById('wartosc_do').innerHTML = conv2num2(do_koszt.toString())+' zł';
}

function m_submit() {    
    $('#a').val('zakup');
    $('#koszyk').submit();
    document.getElementById('koszyk').submit();
    return true;
}


</script>

<div class="steps">
<div class="step2">{T_KROK_1}</div>
<div class="step1">{T_KROK_2}</div>
<div class="step1">{T_KROK_3}</div>
<div class="step1">{T_KROK_4}</div>
</div>
{ERROR}

<form action="{URL}{LANG}/paymant/" method="post" id="koszyk" class="jqTransform">
    <input type="hidden" name="a" id="a" value="koszyk" />
    <table class="basket" border="1" style="width: 100%;">
        <tr>
            <td><strong>{T_LP}</strong></td>
            <td colspan="2"><strong>{T_PRODUKT}</strong></td>
            <td style="text-align: center"><strong>{T_KOSZYK_ILE}</strong></td>
            <td style="text-align: center"><strong>{T_KOSZYK_KWOTA}</strong></td>
            <td style="text-align: center"><strong>{T_RABAT_GC}</strong></td>
            <td style="text-align: center"><strong>{T_TWOJA_CENA}</strong></td>
            <td style="padding-right: 0px;text-align: right"><strong>{T_NL_USUN}</strong></td>
        </tr>

        <!--{IT_ITEM}-->
        <tr class="r{MOD}">
            <td style="width: 20px;">{LP}</td>
            <td style="width: 90px;padding:0px;"><a href="{URL}"><img src="{IMG}" alt="" style="width: 80px;"></a></td>
            <td style="width: 220px;">
                {NAZWA} <strong>({ROZMIAR_LABEL} {ATRYBUT})</strong><br/>
                {INFO}<br/>
                {T_CENA}: {CENA1} PLN
                <input type="hidden" id="cena_{ID}_{AT_ID}" value="{CENAFORM}"/>
            </td>
            <td style="width: 34px;text-align: center"><input type="text" name="ko_ile_{ID}_{AT_ID}" id="ko_ile_{ID}_{AT_ID}" class="ilosc{ERROR}" value="{ILE}" maxlength="3" onblur="m_przelicz()" style="width: 20px;" /></td>
            <td id="wartosc_{ID}_{AT_ID}" style="text-align: center">{WARTOSC} PLN</td>
            <td style="text-align: center"><strong>{CLUBRABAT}</strong></td>
            <td style="text-align: center;"><strong>{WARTOSPORABACIE} PLN</strong></td>
            <td style="width: 20px;padding-left: 14px;padding-right: 0px;text-align: right"><input type="checkbox" name="ko_usun_{ID}_{AT_ID}"/></td>
        </tr>
        <!--/{IT_ITEM}-->

        <tr>
            <td colspan="8" style="text-align:right;padding-right: 0px;">
                <input type="submit" value="{T_USUN_ZAZNACZONE}" name="submit_usun" onclick="document.getElementById('koszyk').action='{URL}{LANG}/cart/'" class="pushb" />
                <input type="submit" value="{T_PRZELICZ}" name="submit_przelicz" onclick="document.getElementById('koszyk').action='{URL}{LANG}/cart/'" class="pushb" />
            </td>
        </tr>
    </table>
    
    <div class="basket-discount-code">
        <h3>{T_KOD_RABATOWY}</h3>
        <p>{T_KOD_RABATOWY_INFO}</p>
        <input type="text" name="discount_code" value="{CODE}" style="width: 100px;" />
        <input type="submit" name="submit_validate_code" value="{T_SPRAWDZ_KOD_RABATOWY}" onclick="document.getElementById('koszyk').action='{URL}{LANG}/cart/'" class="pushb" />
        <input type="submit" name="submit_reset_code" value="{T_RESETUJ_KOD_RABATOWY}" onclick="document.getElementById('koszyk').action='{URL}{LANG}/cart/'" class="pushb" />
        <h4>{T_AKTYWNY_KOD_RABATOWY} {CODE_NAME}</h4>
    </div>

    <div class="basketsummary">
        <div class="row">
            <div class="label"><strong>{T_WARTOSC_ZAKUPIONYCH_TOWAROW}: </strong></div>
            <div class="price"><strong>{SUMAORG} PLN</strong></div>
        </div>
        <div class="row">
            <div class="label"><strong>{T_RABAT_DZIEKI_GOMEZ_CLUB}:</strong></div>
            <div class="price"><strong>{SUMARGC} PLN</strong></div>
        </div>
        {DISCOUNT_SET}
        <div class="row">
            <div class="label"><strong>{T_KOSZYK_SUMA}:</strong></div>
            <div class="price" id="suma"><strong>{SUMA} PLN</strong></div>
        </div>
    </div>    

    <div class="clearfix"></div>
    
    <div class="separator"></div>

    <div class="acceptRulesContainer" style="display:{SHOW_CHECK_RULES}">
        <input type="hidden" name="withAcceptRules" value="{CHECK_RULES}"/>
        <input type="checkbox" name="acceptRules"/><label>{T_RULES_AKCEPTUJE} <a href="{URL}{LANG}/regulamin_promocji/">{T_RULES_REGULAMIN}</a> {T_RULES_PROMOCJI}</label>
    </div>
    <div class="form-element" style="text-align:right; margin-bottom: 20px;">
        <input id="submitBasket" type="submit" value="{T_DALEJ}" name="s_" style="width:100px;" />
    </div>
</form>
<!--/{IT_FORM}-->
