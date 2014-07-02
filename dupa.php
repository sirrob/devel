<?php 

/**
 * Skrypt odpowiedzalny za organizację wyświetlania strony
 * @author Michał Bzowy
 * @copyright Copyright (c) 2008, Michał Bzowy
 * @since 2008-08-12 12:36:48
 * @link www.imt-host.pl
 */

/**
 * Autoryzacja
 */
define('SMDESIGN', true);

/**
 * Załączenie bibliotek
 */
include_once("incphp/mb_pliki.php");
/**
 * Początek przetwarzania
 */
include_once("incphp/mb_common_begin.php");


function test($id) {
    global $_GLOBAL, $db;

    $q = 'SELECT do_id FROM mm_dokument_dane WHERE do_ko_nip = '.$id;
    $rows = $db->get_all($q);
    $documentIDs = array();
    foreach ($rows as $value) {
        array_push($documentIDs, $value['do_id']);
    }

    $q = 'SELECT dt_ma_id, ma_nazwa, dt_pr_id, dt_indeks, dt_pr_nazwa, dt_at_nazwa, dt_ilosc FROM mm_dokument_towar LEFT JOIN mm_magazyn ON mm_magazyn.ma_id = mm_dokument_towar.dt_ma_id WHERE dt_do_id IN ('.implode(', ', $documentIDs).') ORDER BY dt_ma_id';
    $rows = $db->get_all($q);

    $messages['bok'] = array('to' => 'sklep@gomez.pl', 'message' => '', 'count' => 0);
    $messages['men'] = array('to' => 'men@gomez.pl', 'message' => '', 'count' => 0);
    $messages['women'] = array('to' => 'women@gomez.pl', 'message' => '', 'count' => 0);
    $messages['shoes'] = array('to' => 'shoes@gomez.pl', 'message' => '', 'count' => 0);
    $messages['magazyn'] = array('to' => 'magazyn@gomez.pl', 'message' => '', 'count' => 0);

    foreach ($rows as $key => $value) {
        $message = 'Produkt: '.$value['dt_pr_nazwa'] . '['.$value['dt_indeks'].']<br>';
        $message .= 'Rozmiar: '.$value['dt_at_nazwa'].'<br>';
        $message .= 'Liczba sztuk: '.$value['dt_ilosc'].'<br>';
        $message .= 'Magazyn: '.$value['ma_nazwa'].'<br><br>';

        $messages['bok']['message'] .= $message;
        $messages['bok']['count']++;

        switch($value['dt_ma_id']) {
            case 1 : // magazyn głowny
                $messages['magazyn']['message'] .= $message;
                $messages['magazyn']['count']++;
                break;            
            case 2 :// meski
                $messages['men']['message'] .= $message;
                $messages['men']['count']++;
                break;
            case 3 :// damski
                $messages['women']['message'] .= $message;
                $messages['women']['count']++;
                break;
            case 6 :// obowie
                $messages['shoes']['message'] .= $message;
                $messages['shoes']['count']++;
                break;
            case 7 :// obowie męskie
                $messages['shoes']['message'] .= $message;
                $messages['shoes']['count']++;
                break;
            case 8 :// foto
                $messages['magazyn']['message'] .= $message;
                $messages['magazyn']['count']++;
                break;
        }        
    }

    echo "<pre>";
    print_r($messages);
    echo "</pre>";
}

test(69896);



// Zakończenie przetwarzania i wyświetlenie wyniku
include_once("incphp/mb_common_end.php");

?>
