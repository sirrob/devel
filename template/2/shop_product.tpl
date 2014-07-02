<script type="text/javascript">
function roz_wybierz(id,nazwa) {
  if(document.getElementById("rozmiar").value!="") {
    document.getElementById("roz"+document.getElementById("rozmiar").value).className="tabroz";
  }
  
  document.getElementById("rozmiar").value=id;
  document.getElementById("rozwyb").innerHTML=nazwa;
  
  document.getElementById("roz"+document.getElementById("rozmiar").value).className="tabroza";
}
function m_validate() {
  
  <!--{IT_JS_VAL}-->return true;<!--/{IT_JS_VAL}-->
  
  if(document.getElementById('rozmiar').value=="") {
    alert('Wybierz rozmiar!');
    return false;
  }
  
  return true;
}

</script>
<div class="ramka">
<h1>{TYTUL} <div class="mapa" style="clear:both">{T_TU_JESTES}: {LOCAL}</div></h1>

<div class="produkt_pr">
<form action="#" method="post" onsubmit="return m_validate()">
{HIDDEN}
<input type="hidden" name="id" value="{PRID}" />
<table class="pr_w">
<tr>
	<td class="pr_img">
		<div class="primg"><a href="{IMG_X}" title="{ALT}" rel="lightbox[roadtrip]"><img src="{IMG}" alt="{ALT}"/></a></div>
<!--{IT_IMG_WIECEJ}--><div class="primg2">
<!--{IT_IMG}--><a href="{IMG_X}" title="{ALT}" rel="lightbox[roadtrip]"><img src="{IMG}" alt="" /></a><!--/{IT_IMG}--></div><!--/{IT_IMG_WIECEJ}-->

<a href="javascript:;" onclick="m_druk('produkt',{PRID});" class="pushlink">{T_DRUK}</a>
<a href="javascript:;" onclick="m_zapytaj('produkt','{NAZWA}','{URL}');"  class="pushlink">{T_ZAPYTAJO}</a>
<a href="javascript:;" onclick="m_polec('produkt','{NAZWA}','{URL}');"  class="pushlink">{T_POLEC}</a>
<!--{IT_DOKOSZYKA1}--><input type="submit" name="submit_dokoszyka" value="{T_DOKOSZYKA}" class="pushlink"><!--/{IT_DOKOSZYKA1}-->
	</td>
	<td  class="pr_opis">
		<a href="{URL_PRODUCENT}" class="produc">{PRODUCENT}</a>
        <h5>{NAZWA}</h5>
<!--{IT_RABAT}--> <a href="{SA_URL}" class="promocja"><img src="{SA_PLIK}" alt=""/></a><!--/{IT_RABAT}-->
          
<strong>Cena: <span>{CENA}</span> {WALUTA}</strong> <!--{IT_DOKOSZYKA2}-->&nbsp;&nbsp;<input type="image" src="files/cms/6/koszyk.gif" name="submit_dokoszyka_2"><!--/{IT_DOKOSZYKA2}--> 
<br/>
<!--{IT_ROZMIAR}--><strong>{T_ROZMIAR}</strong>: {ROZMIAR}<br/><!--/{IT_ROZMIAR}-->
{T_KOLOR}: {KOLOR}<br/>
{T_STAN}: {STAN}<br/>
<!--{IT_ATRYBUT}-->{NAZWA}: {WARTOSC}<br/><!--/{IT_ATRYBUT}-->
Indeks: {INDEKS}<br/>
Opis produktu:<br/>
{OPIS}<br/>


<!--{IT_ETYKIETA}--><img src="{IMG}" alt="{ET_TXT}" title="{ET_TXT}" /> <!--/{IT_ETYKIETA}--><br/>
           </td>
      </tr>
</table>
<!--{IT_TABELA}--><img src="{IMG}" alt="" title="" /> <!--/{IT_TABELA}-->

</form>
<div class="clear"></div>
</div>

<!--{IT_PODOBNE}-->

<h1>Proponujemy</h1>
<div class="produkt">
<div class="medium">


<!--{PODOBNE}-->m<!--/{PODOBNE}-->
<div class="clear"></div>
</div>
</div>
<!--/{IT_PODOBNE}-->

<div class="clear"></div>

</div>