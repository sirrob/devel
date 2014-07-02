<h1>{TYTUL}</h1>
<div class="mapa" style="clear:both">{T_TU_JESTES}: {LOCAL}</div>
<div class="produkt_pr">
<form action="#" method="post">
{HIDDEN}
<input type="hidden" name="id" value="{PRID}" />
<table class="pr_w">
      <tr>
           <td class="pr_img">
<div class="primg">
<a href="javascript:;" onclick="m_sklep_foto({PRID},{IMNR});"><img src="{IMG}" alt="{ALT}"/></a>
</div>
<div class="primg2">
<!--{IT_IMG}--><a href="javascript:;" onclick="m_sklep_foto({PRID},{IMNR});"><img src="{IMG}" alt="" /><!--/{IT_IMG}--></div>

<a href="javascript:;" onclick="m_druk('produkt',{PRID});" class="pushlink">{T_DRUK}</a>
<a href="javascript:;" onclick="m_zapytaj('produkt','{NAZWA}','{URL}');"  class="pushlink">{T_ZAPYTAJO}</a>
<a href="javascript:;" onclick="m_polec('produkt','{NAZWA}','{URL}');"  class="pushlink">{T_POLEC}</a>
<!--{IT_DOKOSZYKA1}--><input type="submit" name="submit_dokoszyka" value="{T_DOKOSZYKA}" class="pushlink"><!--/{IT_DOKOSZYKA1}-->
</td>
           <td  class="pr_opis">
           <h5>{NAZWA}</h5>
          <strong>Producent: <a href="{URL_PRODUCENT}">{PRODUCENT}</a></strong><br/>
<strong>Cena: <span>{CENA}</span> {WALUTA}</strong> <!--{IT_DOKOSZYKA2}-->&nbsp;&nbsp;<input type="image" src="files/cms/6/koszyk.gif" name="submit_dokoszyka_2"><!--/{IT_DOKOSZYKA2}--><br/>
<!--{IT_ROZMIAR}--><strong>{T_ROZMIAR}</strong>: {ROZMIAR}<br/><!--/{IT_ROZMIAR}-->
{T_KOLOR}: {KOLOR}<br/>
{T_STAN}: {STAN}<br/>
Indeks: {INDEKS}<br/>
{OPIS}<br/>


<!--{IT_ETYKIETA}--><img src="{IMG}" alt="{ET_TXT}" title="{ET_TXT}" /> <!--/{IT_ETYKIETA}--><br/>
<!--{IT_TABELA}--><b>Tabela rozmiarów:</b><br/><img src="{IMG}" alt="" title="" /> <!--/{IT_TABELA}-->
 
           </td>
      </tr>
</table>
</form>
</div>

<!--{IT_PODOBNE}-->
<div class="produkt">
<h2>{T_ZOBACZ_TAKZE}</h2>
<!--{PODOBNE}-->m<!--/{PODOBNE}-->
</div>
<!--/{IT_PODOBNE}-->