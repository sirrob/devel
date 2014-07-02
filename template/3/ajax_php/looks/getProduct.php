<?php

$product_index = $_GET["p_id"];
$language_id = $_GET['langid'];

$con = mysql_connect('sql.gomez.iq.pl', 'admin_www', 'home2008');
if ($con) {
	$db_selected = mysql_select_db('admin_www', $con);
	
	mysql_query("SET NAMES 'latin2'");
	
	$sql = "SELECT * FROM mm_produkt p
	LEFT JOIN mm_sklep_produkt s ON p.pr_id = s.pr_id
	LEFT JOIN mm_sklep_producent pr ON s.pr_pd_id = pr.pd_id
	WHERE pr_indeks LIKE '".$product_index."'";
	
	$result = mysql_query($sql);
	$row = mysql_fetch_array($result);

    if((int)$language_id != 1) {
        $query = "select * from mm_sklep_product_translation where name='nazwa' AND pr_id=" . $row['pr_id'] . " and langid=" . (int)$language_id . ";";
		$result = mysql_query($query);
		$translation_row = mysql_fetch_array($result);
		$product_name = $translation_row['description'];       
    } else {
		$product_name = $row['pr_nazwa'];
    }

    $q = "SELECT co_wartosc FROM mm_config WHERE co_klucz LIKE 'omitted_stores'";
    $result = mysql_query($q);
    $omittedStores = mysql_fetch_array($result);

	$produkt_index_q = substr_replace($product_index, '_', 10, 1);
			
	$query = 'SELECT COUNT(*) AS ilosc_r, ms_ilosc, pr_id, pr_indeks
                    FROM mm_magazyn_stan
                    LEFT JOIN mm_produkt on ms_pr_id = pr_id
                    WHERE ms_ilosc > 0 AND pr_indeks LIKE \''.$produkt_index_q.'\' AND ms_ma_id NOT IN(' .  $omittedStores['co_wartosc'] . ')';
					
	$result = mysql_query($query);
	$dane = mysql_fetch_array($result);
		
	$cena = "";	
	$link = "";
	if((int)$dane['ilosc_r'] <= 0){
		if((int)$language_id != 1){
			$cena = "NOT AVAILABLE";
		} else {
			$cena = "BRAK PRODUKTU";
		}
	} else {
		$cena = strtoupper($row['pr_cena_w_brutto']) .' PLN';	
		if((int)$language_id != 1) {
			$link = 'http://gomez.pl/en/produkt/'.$row['pr_id'];
		} else {
			$link = 'http://gomez.pl/pl/produkt/'.$row['pr_id'];
		}
	}
		
	$tooltip = "";
	$tooltip = $tooltip . '<div class="BrandContent"> '. str_replace(array('ą','ę', 'ó', 'ś', 'ł', 'ż', 'ź', 'ć', 'ń', '\\\''),array('Ą','Ę', 'Ó', 'Ś', 'Ł', 'Ż', 'Ź', 'Ć', 'Ń', '\''), strtoupper($row['pd_nazwa'])) .' </div>';
	$tooltip = $tooltip . '<div class="NameContent"> '. str_replace(array('ą','ę', 'ó', 'ś', 'ł', 'ż', 'ź', 'ć', 'ń', '\\\''),array('Ą','Ę', 'Ó', 'Ś', 'Ł', 'Ż', 'Ź', 'Ć', 'Ń', '\''), strtoupper($product_name)) .' </div>';
	$tooltip = $tooltip . '<div class="PriceContent"> '. $cena .'</div>';
	
	$result = json_encode(array('tooltip' => $tooltip, 'link' => $link), JSON_FORCE_OBJECT);
	echo $result;
	
	mysql_close($con);
} else {
	echo '<div class="BrandContent"> ERROR </div>';
	echo '<div class="NameContent"> ERROR </div>';
	echo '<div class="PriceContent"> ERROR </div>';
}

?>