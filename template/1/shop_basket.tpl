<!--{IT_EMPTY}-->
<div class="message">{T_KOSZYK_PUSTY}</div>
<!--/{IT_EMPTY}-->

<!--{IT_FORM}-->
<script type="text/javascript">
var error = false;
var GRATIS = Array();
{GRATIS}
var KOSZT = Array();
{KOSZT}
function m_przelicz() {
  var obj = document.getElementById('koszyk');
  var suma=0;   
  for(i=0; i<obj.elements.length; i++){
    if(obj.elements[i].id.substr(0,7)=='ko_ile_') {
      id = obj.elements[i].id.substr(7);
      if(isNaN(obj.elements[i].value) || obj.elements[i].value.indexOf(".")>=0 || obj.elements[i].value.indexOf("-")>=0) {
        obj.elements[i].className="iloscerror";
        alert('Nieprawidłowy format ilości produktów');
        error = true;
        obj.elements[i].focus();
        return;
      } else {
        obj.elements[i].className="ilosc";
        error = false;
        wartosc = obj.elements[i].value*document.getElementById('cena_'+id).value;
        document.getElementById('wartosc_'+id).innerHTML = conv2num(wartosc)+' zł';
        suma+=wartosc;
      }
    }
  }
    var do_koszt = 0;
    if(document.getElementById("do_id").selectedIndex!=0) {
      dostawa = document.getElementById("do_id").options[document.getElementById("do_id").selectedIndex].value;
      if(suma<GRATIS[dostawa]) {
        suma = suma + KOSZT[dostawa];
        do_koszt = KOSZT[dostawa];
      }
    }

    document.getElementById('suma').innerHTML = '<strong>'+conv2num(suma)+' zł</strong>';
    document.getElementById('wartosc_do').innerHTML = conv2num(do_koszt)+' zł';
}

function m_submit() {
  if(error) {
    alert('Aby przejść do kolejnego kroku wypełnij poprawnie ilości zamawianych produktów.');
    return false;
  }
  if(document.getElementById("do_id").selectedIndex==0) {
    alert('Aby przejść do kolejnego kroku wybierz formę dostawy.');
    return false;
  }
  
  return true;
}

</script>
{ERROR}
<form action="zakup.htm" method="post" id="koszyk">
<input type="hidden" name="a" value="koszyk" />
<table class="basket">
<tr><td><strong>L.p.</strong></td><td><strong>Produkt</strong></td><td><strong>Ilość</strong></td><td><strong>Wartość</strong></td><td><strong>Usuń</strong></td></tr>

<!--{IT_ITEM}-->
<tr class="r{MOD}"><td>{LP}</td><td>
<a href="{URL}"><img src="{IMG}" alt="" /></a> {NAZWA} (roz. {ATRYBUT})<br/>{INFO}<br/>{T_CENA}: {CENA} zł
<input type="hidden" id="cena_{ID}_{AT_ID}" value="{CENA}" />
</td><td><input type="text" name="ko_ile_{ID}_{AT_ID}" id="ko_ile_{ID}_{AT_ID}" class="ilosc{ERROR}" value="{ILE}" maxlength="3" onblur="m_przelicz()" /></td><td id="wartosc_{ID}_{AT_ID}">{WARTOSC} zł</td><td><input type="checkbox" name="ko_usun_{ID}_{AT_ID}" /></tr>
<!--/{IT_ITEM}-->

<tr><td colspan="5" style="text-align:right;"><input type="submit" value="przelicz" name="submit_przelicz" onclick="document.getElementById('koszyk').action='koszyk.htm'" class="pushb" /> 
<input type="submit" value="usuń zaznaczone" name="submit_usun" onclick="document.getElementById('koszyk').action='koszyk.htm'"  class="pushb"/>
</td></tr>
<tr><td colspan="3">Wybierz sposób dostawy: 
<select name="do_id" id="do_id" onchange="m_przelicz()">
<option value="-1">wybierz sposób dostawy</option>
{DOSTAWA}
</select>
</td><td id="wartosc_do">{WARTOSC_DO} zł</td><td></td></tr>

<tr><td colspan="3" style="text-align:right;"><strong>{T_KOSZYK_SUMA}:</strong></td><td id="suma"><strong>{SUMA} zł</strong></td><td></td></tr>
</table>

<input type="submit" value="dalej" name="s_" onclick="return m_submit()" class="pushb" style="float:right; margin-top:5px; width:100px;"/> 
</form>
<!--/{IT_FORM}-->