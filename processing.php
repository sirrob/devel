<?php
//session_start();
define('SMDESIGN', true);


include_once("incphp/mb_pliki.php");

//lista kategorii
if((isset($_POST)) && ($_POST['search_kat']))
{
    $CAT = array();
    $query = "select pr.pr_id,pr.pr_nazwa,pr_plik,pr_punkt,pr_cena_a_brutto,pr_etykieta,pr_cena_w_brutto pr_cena_w_brutto, pd_nazwa,pd_id,pd_plik,kp_ka_id, IF ((pr_cena_a_brutto - pr_cena_w_brutto)>0,1,0) AS upust
              from mm_sklep_produkt pr,mm_produkt p1,  mm_sklep_kategoria_produkt, mm_sklep_producent
              where pr.pr_id=p1.pr_id 
              and pr.pr_id=kp_pr_id 
              and pr_pd_id=pd_id
              and 
                   pr_stan = '1' and
                   pr_widoczny='1' and 
                   kp_widoczna='1' and
                   pd_id=" . (int)$_POST['search_kat'] . "
              order by upust asc, pr.pr_image_upload_timestamp desc, pr.pr_id desc;";

    $res = $db->query($query);
    while($item = $db->fetch($res)) $CAT[$item['kp_ka_id']] = $item['kp_ka_id'];

    //echo '<select size="1" name="ska" id="szska" onchange="fszska(this.value)">';
    echo '<select size="1" name="kategoria" id="szska" onchange="fszska(this.value)">';
    echo '<option value="-1" id="szska0">- wybierz kategorię -</option>';
//kategorie        

        
    $query = "select * from " . dn('sklep_kategoria') . " where ka_widoczna>0 and ka_ka_id=" . (int)$_POST['ckatid'] . " order by ka_pozycja;";
    $res = $db->query($query);
    while($item = $db->fetch($res)) 
    {
        echo '<option value="' . $item['ka_id'] . '">' . $item['ka_nazwa'] . '</option>';
        $query = "select * from " . dn('sklep_kategoria') . " where ka_widoczna>0 and ka_ka_id=" . (int)$item['ka_id'] . " order by ka_pozycja;";
        $res2 = $db->query($query);
        while($item2 = $db->fetch($res2)) 
        {
            if(isset($CAT[$item2['ka_id']]))
            echo '<option value="' . $item2['ka_id'] . '">&nbsp;&nbsp;&nbsp;&nbsp;' . $item2['ka_nazwa'] . '</option>';
        }
    }

    echo '</select>';
}

//search_ro - lista rozmiarów
if((isset($_POST)) && ($_POST['search_ro']))
{
    $CAT = array();
    
    $Q[] = "kp_widoczna='1'";
    $Q[] = "pr_widoczny='1'";
    $Q[] = "pr_stan>0";
    $lj = '';

    if(($_POST['search_rokat']!="") && ($_POST['search_rokat'] != -1))
    {
        $Q[] = "(pr_pd_id = '".a($_POST['search_rokat'])."')";
    }

    if($_POST['search_ro']!="" and test_int($_POST['search_ro']) and ($_POST['search_ro']!= -1)) 
    {
        $Q[] = "kp_ka_id=".(int)$_POST['search_ro'];
    }
    
    $q = "select pr.pr_id,pr.pr_nazwa,pr_plik,pr_punkt,pr_cena_a_brutto,pr_etykieta,";
    $q.= get_cena_w()." pr_cena_w_brutto,pd_nazwa,pd_plik,kp_ka_id, pr_gt_id, IF ((pr_cena_a_brutto - pr_cena_w_brutto)>0,1,0) AS upust
                 from ".dn("sklep_produkt")." pr,".dn("produkt")." p1,".dn("sklep_kategoria_produkt").",".dn("sklep_producent")." ";
    $q.= "where pr.pr_id=p1.pr_id ";
    $q.= "and pr.pr_id=kp_pr_id ";
    $q.= "and pr_pd_id=pd_id ".$lj;
    if(count($Q)>0) $q .= ' and ';
    $q.= " ".join(" and ",$Q)." group by kp_pr_id ORDER BY upust asc, pr.pr_image_upload_timestamp desc, pr.pr_id desc;";
      
    $nr = $db->query($q);

    $CAT = array();
    while($T = $db->fetch($nr)) 
    {
        //rozmiary                
                                            // and ms_ma_id<>".$_GLOBAL["program_magazyn"]."
        $q ="select ms_at_id,at_nazwa 
             from ".dn("magazyn_stan").", ".dn("produkt_atrybut")." ";
        $q.="where ms_at_id=at_id and ms_pr_id=".$T["pr_id"]." 
             group by at_nazwa having sum(ms_ilosc)>0 order by replace(at_nazwa,'Pusty','_____')";
        $nrr = $db->query($q);
        while($Tr = $db->fetch($nrr)) 
        {
            if(!empty($Tr[1])) 
            {
                //$CAT[$Tr[0]] = $Tr[1];
                if(is_numeric(substr($Tr['at_nazwa'],0,1))) $CAT['size'][0][$Tr['ms_at_id']] = trim($Tr['at_nazwa']);
                else $CAT['size'][1][$Tr['ms_at_id']] = trim($Tr['at_nazwa']);
            }
        }                
    }
            
    //if(is_array($CAT)) asort($CAT);
    if((count($CAT['size'][0])>0)) asort($CAT['size'][0],SORT_REGULAR);
    if((count($CAT['size'][1])>0)) asort($CAT['size'][1],SORT_REGULAR);  
    //echo '<select size="1" name="sro" id="szsro" onchange="fszsro(this.value)">';
    echo '<select size="1" name="size[]" id="szsro" onchange="fszsro(this.value)">';
    echo '<option value="-1" id="szska0">- wybierz rozmiar -</option>';
    
    
    $ROZ = array("XXS","XS","S","M","L","XL","XXL","XXXL","XXXXL");
    foreach ($ROZ as $key => $value) {
        if(in_array($value, $CAT['size'][1])){
            $klucz = '';
            $klucz = array_search($value,  $CAT['size'][1]);
            echo '<option value="' . $klucz . '">' . $value . '</option>'."\n";
        }
    }
    
    if(count($CAT['size'][0])>0)
    {
        foreach ($CAT['size'][0] as $key => $value) {
            echo '<option value="' . $key . '">' . $value . '</option>'."\n";
        }
    }
    echo '</select>';
}


if(isset($_POST['getProducts']))
{
    include_once("incphp/class_shop.php");
    $prdList = '';
    //$_SESSION[SID]['szopsz']
    //[spd] => 68 -> producent
    //[ska] => 9  -> kategoria
    //[sro] => 4  -> rozmiar
    //[sna] => qwe
    //echo '<h1>' . SID . '</h1>';
    $_SESSION[SID]['shopsz']['spd'] = $_POST['spd'];
    $_SESSION[SID]['shopsz']['ska'] = $_POST['ska'];
    $_SESSION[SID]['shopsz']['sro'] = $_POST['sro'];
    $_SESSION[SID]['shopsz']['sna'] = $_POST['sna'];
    if((empty($_POST['spd'])) || ($_POST['spd']==-1)) unset($_SESSION[SID]['shopsz']['spd']);
    if((empty($_POST['ska'])) || ($_POST['ska']==-1)) unset($_SESSION[SID]['shopsz']['ska']);
    if((empty($_POST['sro'])) || ($_POST['sro']==-1)) unset($_SESSION[SID]['shopsz']['sro']);
    if((empty($_POST['sna'])) || ($_POST['sna']==-1) || ($_POST['sna']=='undefined')) unset($_SESSION[SID]['shopsz']['sna']);   
    
    $A = array();
    $A[1] = 'szukaj';
    $A[2] = 'szukaj';

    include "admin/language/utf-8.php";
    $prd = new shop($A);
    $prdList = $prd->get_kategoria_ajax((int)$_POST['ckatid']);
    
    if(count($LANG)>0)
    {
        foreach ($LANG as $key => $value) {
            $prdList = str_replace('{'.$key.'}', $value, $prdList);
        }
    }
    
    //$LANG['T_KAT_PUSTA'] = 'Brak produktów spełniających kryteria wyszukiwania';
    $prdList = str_replace('{T_KAT_PUSTA}', 'Brak produktów spełniających kryteria wyszukiwania', $prdList);
    echo $prdList;
}

if(isset($_POST['obserwowane']))
{
    $query = "select * from " . dn('obserwowane') . " where pr_id=" . (int)$_POST['pid'] . " and ko_id=" . (int)$_POST['uid'] . ";";
    $res = $db->query($query);
    if($db->num_rows($res)>0)
    {
		if($_GLOBAL['langid'] == 1)
			echo 'Ten produkt jest już dodany do obserwowanych';
		else
			echo 'Product already in the Observed tab';
    } else
    {
        $query = "insert into " . dn('obserwowane') . " (pr_id,ko_id) values (" . (int)$_POST['pid'] . "," . (int)$_POST['uid'] . ");";
        $res = $db->query($query);
		if($_GLOBAL['langid'] == 1)
			echo 'Produkt został dodany do obserwowanych';
		else 
			echo 'Product added to the Observed tab';
    }
}

?>