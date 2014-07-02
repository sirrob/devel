<?php
/**
 * Funkcje pomocnicze
 *
 *
 * @author    Michał Bzowy
 * @copyright Copyright (c) 2008, Michał Bzowy
 * @since     2008-08-04 12:36:48
 * @link      www.imt-host.pl
 */

/**
 * Autoryzacja
 */
if (!defined('SMDESIGN')) {
    die("Hacking attempt");
}

/**
 * Do projektu
 *
 * @param int $kat
 * @return string
 */
function get_cena_w($kat = 0) {
    if (defined('SHOP_RABAT')) return '(pr_cena_w_brutto*(100-' . SHOP_RABAT . ')/100)';

    return 'pr_cena_w_brutto';
}

/**
 * Funkcja zwraca część URLa bez domeny
 *
 * @param string $str
 * @return string
 */
function get_dirfile($str) {
    if (substr($str, 0, 4) == "http") {
        $str = substr($str, 8);
        $str = substr($str, strpos($str, "/") + 1);
    }

    return $str;
}


function mail_utf8($to, $from_user, $from_email, $subject = '(No subject)', $message = '') {
    $from_user = "=?UTF-8?B?" . base64_encode($from_user) . "?=";
    $subject   = "=?UTF-8?B?" . base64_encode($subject) . "?=";

    $headers = "From: $from_user <$from_email>\r\n" .
        "MIME-Version: 1.0" . "\r\n" .
        "Content-type: text/plain; charset=UTF-8" . "\r\n";

    return mail($to, $subject, $message, $headers);
}

function zamowienie_ticket($id) {
    global $_GLOBAL, $db;

    $separator = "==========================================\n";

    // zamówienie	
    $q = "select za.*, pl_nazwa, do_nazwa ";
    $q .= "from " . dn("sklep_zamowienie") . " za, " . dn("sklep_platnosc") . "," . dn("sklep_dostawa") . " ";
    $q .= "where za_do_id=do_id and za_pl_id=pl_id and za_id=" . $id;
    $T = $db->onerow($q);

    //za_ko_id
    if ($T['za_ko_id'] == 0) {
        $query       = "select * from " . dn('sklep_zamowienie_dane') . " where za_id=" . (int)$id . ";";
        $res         = $db->query($query);
        $dane        = $db->fetch($res);
        $clientEmail = $dane['email'];
        $clientPhone = $dane['tel'];
    } else {
        $query       = "select * from " . dn('kontrahent') . " where ko_id=" . $T['za_ko_id'] . ";";
        $dane        = $db->onerow($query);
        $clientEmail = $dane['ko_email'];
        $clientPhone = $dane['ko_telefony'];
    }
    $clientName = $T["za_do_nazwa"];

    $orderID  = long_id($id, 6);
    $address1 = $T["za_ko_kod"] . " " . $T["za_ko_miasto"] . "\n" . $T["za_ko_ulica"] . " " . $T["za_ko_ulica_dom"] . " " . $T["za_ko_ulica_lok"];
    $address2 = $T["za_do_kod"] . " " . $T["za_do_miasto"] . " (" . $T["za_do_kraj"] . ")\n" . $T["za_do_adres"];

    $shipment = $T["do_nazwa"] . '(' . number_format($T["za_do_koszt"], 2, ".", "") . ' zł)';
    $payment  = $T["pl_nazwa"];

    $worth = number_format(($T["za_wartosc_brutto"] + $T["za_do_koszt"]), 2, ".", "");

    $message = "";

    if ($T["za_ko_nazwa"] != "") {
        $message .= "Zamawiający:\n" . $separator;
        $message .= $address1 . "\n\n";
    }

    $message .= "Dostawa:\n" . $separator;
    $message .= $address2 . "\n\n";

    $message .= "Telefon: " . $clientPhone . "\n\n";

    $message .= "Płatność/Dostawa: " . $payment . " / " . $shipment . "\n\n";

    $message .= "Suma do zapłaty: " . $worth . "\n\n";

    $message .= "Produkty:\n" . $separator;
    $nr = $db->query("select * from " . dn("sklep_zamowienie_pozycja") . " where zp_za_id=" . $id . " order by zp_id");
    while ($T = $db->fetch($nr)) {
        $product = "";

        $quantity = $T["zp_ilosc"];
        $price    = number_format($T["zp_cena_brutto"], 2, ".", "");
        $worth    = number_format($T["zp_cena_brutto"] * $T["zp_ilosc"], 2, ".", "");
        $name     = $T["zp_pr_nazwa"];
        $index    = $T["zp_pr_indeks"];
        $size     = $T["zp_at_nazwa"];

        $product .= $name . "\n";
        $product .= $index . "\n";
        $product .= "rozmiar: " . $size . "\n";
        $product .= "cena: " . $price . "\n";
        $product .= "cena: " . $quantity . "\n";
        $product .= "wartość: " . $worth . "\n";

        $message .= $product . "\n\n";
    }

    $to         = "support@gomez.pl";
    $from_user  = $clientName;
    $from_email = $clientEmail;
    $subject    = "Zamówienie #{$id}, {$from_user}";

    $status = mail_utf8($to, $from_user, $from_email, $subject, $message);
}

function zamowienie_uzytkownika_powiadomienie($id, $koID) {
    global $_GLOBAL, $db;

    // zamówienie
    $q = 'SELECT * FROM mm_kontrahent WHERE ko_id = ' . $koID;
    $K = $db->onerow($q);

    $recipients = array(
        'iza.maczkowiak@gomez.pl',
        'jakub.kwiatkowski@gomez.pl'
    );

    foreach ($recipients as $recipient) {
        $M = array(
            "m_to"      => $recipient,
            "m_subject" => 'Zamówienie monitorowanego kontrahenta',
            "m_message" => 'W sklepie internetowym złożono zamówienie przez: ' . $K['ko_nazwa'] . '<br>Numer zamówienia to: <strong>' . $id . '</strong>'
        );
        my_mail_smtp($M);
    }
}


function zamowienie_odbiorwlasny($id) {
    global $_GLOBAL, $db;

    $q           = 'SELECT do_id FROM mm_dokument_dane WHERE do_ko_nip = ' . $id;
    $rows        = $db->get_all($q);
    $documentIDs = array();
    foreach ($rows as $value) {
        array_push($documentIDs, $value['do_id']);
    }

    $q    = 'SELECT dt_ma_id, ma_nazwa, dt_pr_id, dt_indeks, dt_pr_nazwa, dt_at_nazwa, dt_ilosc FROM mm_dokument_towar LEFT JOIN mm_magazyn ON mm_magazyn.ma_id = mm_dokument_towar.dt_ma_id WHERE dt_do_id IN (' . implode(', ', $documentIDs) . ') ORDER BY dt_ma_id';
    $rows = $db->get_all($q);

    $messages['bok']     = array('to' => 'sklep@gomez.pl', 'message' => '', 'count' => 0);
    $messages['men']     = array('to' => 'men@gomez.pl', 'message' => '', 'count' => 0);
    $messages['women']   = array('to' => 'women@gomez.pl', 'message' => '', 'count' => 0);
    $messages['shoes']   = array('to' => 'shoes@gomez.pl', 'message' => '', 'count' => 0);
    $messages['magazyn'] = array('to' => 'magazyn@gomez.pl', 'message' => '', 'count' => 0);

//TU
    $messages['outlet'] = array('to' => 'sale@gomez.pl', 'message' => '', 'count' => 0);
    $messages['gsm'] = array('to' => 'gsm@gomez.pl', 'message' => '', 'count' => 0);
//KONIEC TU
    $stores = array();
    foreach ($rows as $key => $value) {
        $message = 'Produkt: ' . $value['dt_pr_nazwa'] . '[' . $value['dt_indeks'] . ']<br>';
        $message .= 'Rozmiar: ' . $value['dt_at_nazwa'] . '<br>';
        $message .= 'Liczba sztuk: ' . $value['dt_ilosc'] . '<br>';
        $message .= 'Magazyn: ' . $value['ma_nazwa'] . '<br><br>';

        // BOK
        $messages['bok']['message'] .= $message;
        $messages['bok']['count']++;

        // Magazyn Główny
        $messages['magazyn']['message'] .= $message;
        $messages['magazyn']['count']++;

        // dodanie magazynu do listy magazynow wykorzystywanych w zamowieniu
        if (!in_array($value['dt_ma_id'], $stores)) {
            $stores[] = $value['dt_ma_id'];
        }

        switch ($value['dt_ma_id']) {
            case 1 : // magazyn głowny
                // $messages['magazyn']['message'] .= $message;
                // $messages['magazyn']['count']++;
                break;
            case 2 : // meski
                $messages['men']['message'] .= $message;
                $messages['men']['count']++;
                break;
            case 16 : // meski elegancka
                $messages['men']['message'] .= $message;
                $messages['men']['count']++;
                break;
            case 3 : // damski
                $messages['women']['message'] .= $message;
                $messages['women']['count']++;
                break;
            case 6 : // obowie
                $messages['shoes']['message'] .= $message;
                $messages['shoes']['count']++;
                break;
            case 7 : // obowie męskie
                $messages['shoes']['message'] .= $message;
                $messages['shoes']['count']++;
                break;
            case 8 : // foto
                $messages['magazyn']['message'] .= $message;
                $messages['magazyn']['count']++;
                break;
			//TU
            case 19 : //outlet
                $messages['outlet']['message'] .= $message;
                $messages['outlet']['count']++;
                break;
            case 20: //gsm
                $messages['gsm']['message'] .= $message;
                $messages['gsm']['count']++;
                break;
            //KONIEC TU
        }
    }

    foreach ($messages as $key => $value) {
        if (count($stores) > 1) {
            $message = '<b>Zamówienie realizowane przez kilka magazynów!</b><br><br>';
        } else {
            $message = '<b>Zamówienie realizowane przez jeden magazyn!</b><br><br>';
        }
        $message .= 'Elementy zamówienia:<br><br>';
        if ($value['count'] > 0) {
            $M["m_to"]      = $value['to'];
            $M["m_subject"] = 'Odbiór własny - Zamówienie nr ' . $id . '';
            $M["m_message"] = $message . $value['message'];
            my_mail_smtp($M);
        }
    }
}


function zamowienie_listprzewozowy($id) {
    global $_GLOBAL, $db;

    // zamówienie   
    $q = 'SELECT * FROM mm_sklep_zamowienie LEFT JOIN mm_sklep_zamowienie_dane ON mm_sklep_zamowienie.za_id = mm_sklep_zamowienie_dane.za_id WHERE mm_sklep_zamowienie.za_id = ' . $id;
    $T = $db->onerow($q);

    $email = $T['email'];
    $phone = $T['tel'];

    if ($T['za_ko_id'] != 0) {
        $q = 'SELECT * FROM mm_kontrahent WHERE ko_id = ' . $T['za_ko_id'];
        $K = $db->onerow($q);

        $email    = $K['ko_email'];
        $telArray = explode('|', $K['ko_telefony']);
        $phone    = '';
        foreach ($telArray as $value) {
            $value = str_replace(';', '', $value);
            $phone .= (!empty($value)) ? $value . ', ' : '';
        }
    }

    $doc               = new DOMDocument('1.0', 'windows-1250');
    $doc->formatOutput = true;

    $root = $doc->createElement("LIST");
    $doc->appendChild($root);

    $item = $doc->createElement("RECEIVER_ID");
    $item->appendChild($doc->createTextNode($id));
    $root->appendChild($item);

    $item = $doc->createElement("RECEIVER_NAME");
    $item->appendChild($doc->createTextNode(ucfirst($T['za_do_nazwa'])));
    $root->appendChild($item);

    $item = $doc->createElement("RECEIVER_POSTCODE");
    $item->appendChild($doc->createTextNode($T['za_do_kod']));
    $root->appendChild($item);

    $item = $doc->createElement("RECEIVER_CITY");
    $item->appendChild($doc->createTextNode(ucfirst($T['za_do_miasto'])));
    $root->appendChild($item);

    $item = $doc->createElement("RECEIVER_STREET");
    $item->appendChild($doc->createTextNode(ucfirst($T['za_do_adres'])));
    $root->appendChild($item);

    $item = $doc->createElement("RECEIVER_TEL");
    $item->appendChild($doc->createTextNode($phone));
    $root->appendChild($item);

    $item = $doc->createElement("PRE_REC_SMS");
    $item->appendChild($doc->createTextNode($phone));
    $root->appendChild($item);

    $item = $doc->createElement("PRE_REC_EMAIL");
    $item->appendChild($doc->createTextNode($email));
    $root->appendChild($item);

    $item = $doc->createElement("PRODUCT");
    $item->appendChild($doc->createTextNode('AH'));
    $root->appendChild($item);

    $item = $doc->createElement("INVOICE_TO");
    $item->appendChild($doc->createTextNode('N'));
    $root->appendChild($item);

    $item = $doc->createElement("PAYMENT_TYPE");
    $item->appendChild($doc->createTextNode('P'));
    $root->appendChild($item);

    $item = $doc->createElement("CATEGORY1");
    $item->appendChild($doc->createTextNode('1'));
    $root->appendChild($item);

    $item = $doc->createElement("BLP");
    $item->appendChild($doc->createTextNode('1'));
    $root->appendChild($item);

    $worth        = $T['za_wartosc_brutto'];
    $q            = 'SELECT * FROM mm_sklep_zamowienie_pozycja LEFT JOIN mm_produkt ON mm_produkt.pr_id = mm_sklep_zamowienie_pozycja.zp_pr_id WHERE zp_za_id = ' . $id;
    $rows         = $db->get_all($q);
    $withDiscount = false;
    foreach ($rows as $r) {
        if ($r['zp_cena_brutto'] < $r['pr_cena_b_brutto']) {
            $withDiscount = true;
            break;
        }
    }

    if (($worth < 200) AND ($withDiscount == false)) {
        $worth += $T['za_do_koszt'];
    } elseif (($worth < 300) AND ($withDiscount == true)) {
        $worth += $T['za_do_koszt'];
    }

    // pobranie
    if ($T['za_pl_id'] == 1) {
        $item = $doc->createElement("CASH_ON_DELIVERY");
        $item->appendChild($doc->createTextNode($worth));
        $root->appendChild($item);
    }

    // ubezpieczenie
    if ((intval($T['za_wartosc_brutto']) > 500) OR ($T['za_pl_id'] == 1)) {
        $item = $doc->createElement("GOODS_VALUE");
        $item->appendChild($doc->createTextNode(intval($worth)));
        $root->appendChild($item);
    }

    $item = $doc->createElement("CONTENT");
    $item->appendChild($doc->createTextNode($id));
    $root->appendChild($item);

    $item = $doc->createElement("COMMENT");
    $item->appendChild($doc->createTextNode($T['za_uwagi']));
    $root->appendChild($item);

    $doc->save('dhl/export/' . $id . ".xml");
}

/**
 * Wysłanie maila z potwierdzeniem zamówienia / zmiany stanu zam.
 *
 * @global object $_GLOBAL
 * @global object $db
 * @param int $id ID zamówienia
 * @param string $email
 * @param string $pre
 */
function zamowienie_mail($id, $email = "", $pre = "") {
    global $_GLOBAL, $db;

    //$q  = "select za.*, ko_email,pl_nazwa, do_nazwa ";
    $q = "select za.*, pl_nazwa, do_nazwa, do_nazwa_en, pl_nazwa_en ";
    //$q .= "from ".dn("sklep_zamowienie")." za, ".dn("kontrahent").", ".dn("sklep_platnosc").",".dn("sklep_dostawa")." ";
    $q .= "from " . dn("sklep_zamowienie") . " za, " . dn("sklep_platnosc") . "," . dn("sklep_dostawa") . " ";
    //$q .= "where ko_id=za_ko_id and za_do_id=do_id and za_pl_id=pl_id and za_id=".$id;
    $q .= "where za_do_id=do_id and za_pl_id=pl_id and za_id=" . $id;
    $T = $db->onerow($q);

    //za_ko_id
    if ($T['za_ko_id'] == 0) {
        $query         = "select * from " . dn('sklep_zamowienie_dane') . " where za_id=" . (int)$id . ";";
        $res           = $db->query($query);
        $dane          = $db->fetch($res);
        $T['ko_email'] = $dane['email'];
    } else {
        $query         = "select * from " . dn('kontrahent') . " where ko_id=" . $T['za_ko_id'] . ";";
        $dane          = $db->onerow($query);
        $T['ko_email'] = $dane['ko_email'];

        $dane2   = $db->onerow("select * from " . dn('sklep_kontrahent') . " where ko_id=" . $T['za_ko_id'] . ";");
        $ko_lang = $dane2['ko_lang'];
    }

    // wygenerowanie listu przewozowego
    // if ((($T['za_do_id'] == 3) || ($T['za_do_id'] == 4) || ($T['za_do_id'] == 6)) AND ($T['za_ko_id'] != 2391)) {
    //     zamowienie_listprzewozowy($id);
    // } elseif ($T['za_do_id'] == 1) {
    //     zamowienie_odbiorwlasny($id);
    // }
    if ($T['za_do_id'] == 1) {
        zamowienie_odbiorwlasny($id);
    }
    // ---


    // powiadomienia o zakupach konkretnych uzytkownikow
    // Pani Iwona Milc
    if ($T['za_ko_id'] == 18779) {
        zamowienie_uzytkownika_powiadomienie($id, 18779);
    }
    // ---


    if ($email != "") $T["ko_email"] = $email;

    if ($_GLOBAL['lang'] == "pl") {
        $TP = get_komunikat(array("T_EMAIL", "T_EMAIL_ORDER"));
    } else {
        $TP = get_komunikat(array("T_EMAIL", "T_EMAIL_ORDER_EN"));
    }

    $B["{URL}"]   = $_GLOBAL["page_url"] . $_GLOBAL['lang'] . "/zamowienie/";
    $B["{NUMER}"] = "ZM/" . date("y", $T["za_data_in"]) . "/" . date("m", $T["za_data_in"]) . "/" . long_id($id, 6);
    $B["{DATA}"]  = date("Y-m-d, H:i", $T["za_data_in"]);
    $B["{NAZWA}"] = s($T["za_ko_nazwa"]);
    if ($T["za_ko_nip"] != "") $B["{NIP}"] = s($T["za_ko_nip"]);
    else $DB[] = "IT_NIP";
    $B["{ADRES}"]    = s($T["za_ko_kod"] . " " . $T["za_ko_miasto"] . ", " . $T["za_ko_ulica"] . " " . $T["za_ko_ulica_dom"] . " " . $T["za_ko_ulica_lok"]);
    $B["{DO_NAZWA}"] = s($T["za_do_nazwa"]);
    $B["{DO_ADRES}"] = s($T["za_do_kod"] . " " . $T["za_do_miasto"] . " (" . $T["za_do_kraj"] . "), " . $T["za_do_adres"]);
    if ($ko_lang != null) {
        if ($ko_lang == 0) {
            $LST = get_lista("order_status", 0, $pre);
        } else {
            $LST = get_lista("order_status_en", 0, $pre);
        }
    } else {
        if ($_GLOBAL['lang'] == "pl") {
            $LST = get_lista("order_status", 0, $pre);
        } else {
            $LST = get_lista("order_status_en", 0, $pre);
        }
    }
    $B["{STATUS}"]     = $LST[$T["za_status"]];
    $B["{DOSTAWA}"]    = ($ko_lang != null && $ko_lang == 0 ? s($T["do_nazwa"]) : ($_GLOBAL['lang'] == "pl" ? s($T["do_nazwa"]) : s($T["do_nazwa_en"])));
    $B["{WARTOSC_DO}"] = number_format($T["za_do_koszt"], 2, ".", "");
    $B["{PLATNOSC}"]   = ($ko_lang != null && $ko_lang == 0 ? s($T["pl_nazwa"]) : ($_GLOBAL['lang'] == "pl" ? s($T["pl_nazwa"]) : s($T["pl_nazwa_en"])));
    $B["{SUMA}"]       = number_format(($T["za_wartosc_brutto"] + $T["za_do_koszt"]), 2, ".", "");

    $item = "";
    if ($_GLOBAL['lang'] == "pl") {
        $TP["T_EMAIL_ORDER"] = get_tag($TP["T_EMAIL_ORDER"], "IT_ITEM", $item);
    } else {
        $TP["T_EMAIL_ORDER_EN"] = get_tag($TP["T_EMAIL_ORDER_EN"], "IT_ITEM", $item);
    }

    $M["m_to"] = $T["ko_email"];

    // convert product into meta products
    $query = "select * from " . dn("sklep_zamowienie_pozycja") . " where zp_za_id=" . $id . " order by zp_id";
    $szp   = $db->get_all($query);
    $i     = 1;

    $pTMP = array();
    foreach ($szp as $p) {
        $pattern_prefix = substr($p['zp_pr_indeks'], 0, -5);
        $pattern_sufix  = substr($p['zp_pr_indeks'], -3);
        $index          = $pattern_prefix . '-0' . $pattern_sufix . '-' . $p['zp_at_id'];
        $mpindex        = $pattern_prefix . '-0' . $pattern_sufix;

        if (array_key_exists($index, $pTMP)) {
            $pTMP[$index]['zp_ilosc'] += $p['zp_ilosc'];
        } else {
            $query = 'SELECT p.*, sp.pr_nazwa AS name FROM ' . dn("produkt") . ' p LEFT JOIN ' . dn('sklep_produkt') . ' sp ON sp.pr_id = p.pr_id WHERE pr_indeks = \'' . $mpindex . '\'';
            $mp    = $db->onerow($query);

            $pTMP[$index]['zp_id']          = $p['zp_id'];
            $pTMP[$index]['zp_cena_brutto'] = $p['zp_cena_brutto'];
            $pTMP[$index]['zp_ilosc']       = $p['zp_ilosc'];
            // $pTMP[$index]['zp_pr_nazwa'] = $mp['name'];
            $pTMP[$index]['zp_pr_nazwa']  = ($mp['name'] != '') ? $mp['name'] : ucfirst($mp['pr_nazwa']);
            $pTMP[$index]['zp_pr_indeks'] = $mpindex;
            $pTMP[$index]['zp_at_nazwa']  = $p['zp_at_nazwa'];
            $pTMP[$index]['zp_pr_id']     = $p['zp_pr_id'];
        }
    }
    // ---


    foreach ($pTMP as $T) {
        $C              = array();
        $C["{LP}"]      = $i;
        $C["{MOD}"]     = $i++ % 2;
        $C["{ILE}"]     = $T["zp_ilosc"];
        $C["{ATRYBUT}"] = ($T["zp_at_nazwa"]);
        $C["{CENA}"]    = number_format($T["zp_cena_brutto"], 2, ".", "");
        $C["{WARTOSC}"] = number_format($T["zp_cena_brutto"] * $T["zp_ilosc"], 2, ".", "");
        if ($ko_lang != null) {
            if ($ko_lang == 0) {
                $C["{NAZWA}"] = hs($T["zp_pr_nazwa"]);
            } else {
                $query = 'SELECT description FROM ' . dn("sklep_product_translation") . ' WHERE pr_id = ' . $T['zp_pr_id'] . ' AND langid = 2 AND name LIKE \'nazwa\'';
                $mp    = $db->onerow($query);
                if (isset($mp['description']) && $mp['description'] != '') {
                    $C["{NAZWA}"] = hs($mp['description']);
                } else {
                    $C["{NAZWA}"] = hs($T["zp_pr_nazwa"]);
                }
            }
        } else {
            if ($_GLOBAL['lang'] == "pl") {
                $C["{NAZWA}"] = hs($T["zp_pr_nazwa"]);
            } else {
                $query = 'SELECT description FROM ' . dn("sklep_product_translation") . ' WHERE pr_id = ' . $T['zp_pr_id'] . ' AND langid = 2 AND name LIKE \'nazwa\'';
                $mp    = $db->onerow($query);
                if (isset($mp['description']) && $mp['description'] != '') {
                    $C["{NAZWA}"] = hs($mp['description']);
                } else {
                    $C["{NAZWA}"] = hs($T["zp_pr_nazwa"]);
                }
            }
        }
        $C["{INFO}"] = hs($T["zp_pr_indeks"]);
        $B["{IT_ITEM}"] .= get_template($item, $C, '', 0);
    }

    if ($ko_lang != null) {
        if ($ko_lang == 0) {
            $A["{TRESC}"]   = get_template($TP["T_EMAIL_ORDER"], $B, $DB, 0);
            $M["m_subject"] = "Gomez.pl-Zamówienie nr " . $B["{NUMER}"];
            $M["m_message"] = get_template($TP["T_EMAIL"], $A, '', 0);
        } else {
            $A["{TRESC}"]   = get_template($TP["T_EMAIL_ORDER_EN"], $B, $DB, 0);
            $M["m_subject"] = "Gomez.pl-Order No. " . $B["{NUMER}"];
            $M["m_message"] = get_template($TP["T_EMAIL"], $A, '', 0);
        }
    } else {
        if ($_GLOBAL['lang'] == "pl") {
            $A["{TRESC}"]   = get_template($TP["T_EMAIL_ORDER"], $B, $DB, 0);
            $M["m_subject"] = "Gomez.pl-Zamówienie nr " . $B["{NUMER}"];
            $M["m_message"] = get_template($TP["T_EMAIL"], $A, '', 0);
        } else {
            $A["{TRESC}"]   = get_template($TP["T_EMAIL_ORDER_EN"], $B, $DB, 0);
            $M["m_subject"] = "Gomez.pl-Order No. " . $B["{NUMER}"];
            $M["m_message"] = get_template($TP["T_EMAIL"], $A, '', 0);
        }
    }
    debug($M);

    my_mail_smtp($M);
}


function zamowienie_dodaj($id, $magazyn = 0) {
    global $db, $_GLOBAL;
    $nr = $db->query("select do_id from " . dn("dokument_dane") . " where do_ko_nip='" . $id . "' and do_status='0'");
    if ($db->affected_rows()) {
        while ($T = $db->fetch($nr)) $DID[] = $T[0];
        $db->query("delete from " . dn("dokument_dane") . " where do_id in (" . join(",", $DID) . ")");
        $db->query("delete from " . dn("dokument_towar") . " where dt_do_id in (" . join(",", $DID) . ")");
    }
    $T   = $db->onerow("select ds_tresc from " . dn("dokument_szablon") . " where ds_rodzaj='ZA'");
    $tpl = $T[0];

    $ZA = $db->onerow("select za_wartosc_netto, za_wartosc_brutto from " . dn("sklep_zamowienie") . " where za_id=" . $id);

    // Wzorzec zapytanie, które dodaje zamówienia do bazy
    $q = "insert into " . dn("dokument_dane") . " set ";
    $q .= "do_numer='{AUTO}.{MA_ID}." . $id . "',do_rodzaj='ZA',do_status='0',";
    $q .= "do_data=" . time() . ",do_data_in=" . time() . ",do_update='1',do_data_update=" . time() . ",";
    $q .= "do_uz_id=0,do_uz_nazwa='System',do_ma_id={MA_ID},do_ko_id=1,do_ko_nip='" . $id . "',";
    $q .= "do_wartosc_netto={WARTOSC_NETTO},do_wartosc_brutto={WARTOSC_BRUTTO},";
    $q .= "do_szablon='{SZABLON}'";
    $qz = $q;

    $q = "select zp.*,pr_pkwiu ___pr_pkwiu,gt_nazwa ___gt_nazwa from " . dn("sklep_zamowienie_pozycja") . " zp ";
    $q .= "left join " . dn("produkt") . " on zp_pr_id=pr_id left join " . dn("produkt_grupa_towarowa") . " on pr_gt_id=gt_id ";
    $q .= "where zp_za_id=" . $id . " order by zp_id";

    $nr   = $db->query($q);
    $KOSZ = array();
    $IDS  = array();
    while ($T = $db->fetch($nr, MYSQL_ASSOC)) {
        $KOSZ[$T["zp_pr_id"]][$T["zp_at_id"]] = $T;
        $IDS[]                                = "(" . $T["zp_pr_id"] . "," . $T["zp_at_id"] . ")";
    }

    // ustalanie kolejnosci wyciagania produktow z magazynow, najpierw z glownego (nowego - wylacznie starego [1])
    $query = "SELECT mm_magazyn_stan.* FROM mm_magazyn_stan LEFT JOIN mm_magazyn ON mm_magazyn.ma_id = mm_magazyn_stan.ms_ma_id WHERE mm_magazyn_stan.ms_ma_id NOT IN (" . $_GLOBAL["omitted_stores"] . ") AND (mm_magazyn_stan.ms_pr_id, mm_magazyn_stan.ms_at_id) IN (" . join(",", $IDS) . ") ORDER BY mm_magazyn.ma_order DESC";
    $nr    = $db->query($query);
    // file_put_contents('!queries.txt', $query, FILE_APPEND);

    while ($T = $db->fetch($nr)) {
        $STAN[$T["ms_ma_id"]][$T["ms_pr_id"]][$T["ms_at_id"]] = $T["ms_ilosc"];
    }

    // Wzorzec zapytania, które wprowadzi pozycje zamówienia
    $q = "insert into " . dn("dokument_towar") . " set dt_do_id={DO_ID}, dt_ma_id={MA_ID}, dt_pr_id={PR_ID}, dt_at_id='{AT_ID}',";
    $q .= "dt_pr_nazwa='{PR_NAZWA}', dt_pr_pkwiu='{PR_PKWIU}', dt_at_nazwa='{AT_NAZWA}', dt_kod_kreskowy='{PR_KOD_KRESKOWY}', ";
    $q .= "dt_ilosc={ILE}, dt_jm='{PR_JM}', dt_cena_netto={CENA_NETTO}, dt_cena_brutto={CENA_BRUTTO}, ";
    $q .= "dt_cena_org_netto={CENA_NETTO}, dt_cena_org_brutto={CENA_BRUTTO}, dt_vat_stawka='{PR_VAT_STAWKA}', ";
    $q .= "dt_gt_nazwa='{GT_NAZWA}', dt_kolor='{PR_KOLOR}', dt_indeks='{PR_INDEKS}'";
    // Podziel zamówienie na magazyny, które mają dany towar
    if ($magazyn == 0) {
        foreach ($STAN as $ma => $S) {
            if (in_array($ma, $_GLOBAL["omitted_stores"])) continue;

            foreach ($S as $pr => $I) {
                $PR[$pr] = $pr;
                foreach ($I as $at => $ile) {
                    if (!isset($KOSZ[$pr][$at]) or $KOSZ[$pr][$at]["zp_ilosc"] <= 0 or $ile <= 0) continue;

                    // Jeżeli na stanie jest więcej niż potrzebujemy to zajmujemy tyle ile potrzebujemy
                    // w przeciwnym wypadku zajmujemy tyle ile jest                   
                    if ($ile >= $KOSZ[$pr][$at]["zp_ilosc"]) {
                        $zajmij                     = $KOSZ[$pr][$at]["zp_ilosc"];
                        $KOSZ[$pr][$at]["zp_ilosc"] = 0;
                    } else {
                        $zajmij                     = $ile;
                        $KOSZ[$pr][$at]["zp_ilosc"] = $KOSZ[$pr][$at]["zp_ilosc"] - $ile;
                    }

                    $STAN[$ma][$pr][$at]       = $STAN[$ma][$pr][$at] - $zajmij;
                    $ZMIEN_STAN[$ma][$pr][$at] = true;
                    $DODAJ[$ma][$pr][$at]      = $zajmij;
                }
            }
        }


        foreach ($DODAJ as $ma => $S) {
            $A              = array();
            $A["{SZABLON}"] = $tpl;
            $A["{MA_ID}"]   = $ma;
            $suma_netto     = $suma_brutto = 0;
            foreach ($S as $pr => $I) {
                foreach ($I as $at => $ile) {
                    $suma_netto += $ile * $KOSZ[$pr][$at]["zp_cena_netto"];
                    $suma_brutto += $ile * $KOSZ[$pr][$at]["zp_cena_brutto"];
                }
            }

            $A["{WARTOSC_NETTO}"]  = $suma_netto;
            $A["{WARTOSC_BRUTTO}"] = $suma_brutto;

            //debug(strtr($qz,$A));
            $db->query(strtr($qz, $A));
            $A["{DO_ID}"] = $db->insert_id();
            foreach ($S as $pr => $I) {
                foreach ($I as $at => $ile) {
                    $A["{ILE}"] = $ile;

                    foreach ($KOSZ[$pr][$at] as $key => $v) {
                        $A["{" . strtoupper(substr($key, 3)) . "}"] = $v;
                    }
                      $A["{PR_NAZWA}"] = a($A["{PR_NAZWA}"]);
                    $db->query(strtr($q, $A));
					
                    //debug("update ".dn("magazyn_stan")." set ms_ilosc=".$STAN[$ma][$pr][$at]."
                    //             where ms_ma_id=".$ma." and ms_pr_id=".$pr." and ms_at_id=".$at);
                    $db->query("update " . dn("magazyn_stan") . " set ms_ilosc=" . $STAN[$ma][$pr][$at] . "
                                    where ms_ma_id=" . $ma . " and ms_pr_id=" . $pr . " and ms_at_id=" . $at);
                }
            }
        }

        $nr  = $db->query("select distinct ms_pr_id from mm_magazyn_stan where ms_ilosc>0
                              and ms_ma_id NOT IN (" . $_GLOBAL["omitted_stores"] . ")
                              and ms_pr_id in (" . join(",", $PR) . ")  
                              order by ms_pr_id");
        $IDS = array();
        while ($T = $db->fetch($nr)) {
            $IDS[$T[0]] = $T[0];
        }

        if (count($IDS)) {
            $db->query("update `mm_sklep_produkt` set pr_stan=0 where pr_id in(" . join(",", $PR) . ")");
            $db->query("update `mm_sklep_produkt` set pr_stan=1 where pr_id in(" . join(",", $IDS) . ")");
        }

        /**
         * Prześlij kompletne zamówienie do wybranego magazynu
         */
    } else {
        foreach ($KOSZ as $pr => $K) {
            foreach ($K as $at => $T) {
                $DODAJ[$pr][$at]     = $T["zp_ilosc"];
                $STAN_NOWY[$pr][$at] = $STAN[$magazyn][$pr][$at] - $T["zp_ilosc"];
            }
        }
        $A                     = array();
        $A["{WARTOSC_NETTO}"]  = $ZA[0];
        $A["{WARTOSC_BRUTTO}"] = $ZA[1];
        $A["{SZABLON}"]        = $tpl;
        $A["{MA_ID}"]          = $magazyn;

        //debug(strtr($qz,$A));
        $db->query(strtr($qz, $A));

        $A["{DO_ID}"] = $db->insert_id();

        foreach ($DODAJ as $pr => $I) {
            foreach ($I as $at => $ile) {
                $A["{ILE}"] = $ile;

                foreach ($KOSZ[$pr][$at] as $key => $v) {
                    $A["{" . strtoupper(substr($key, 3)) . "}"] = $v;
                }

                //debug(strtr($q,$A));
                $db->query(strtr($q, $A));

                if (isset($STAN[$magazyn][$pr][$at])) {
                    $db->query("update " . dn("magazyn_stan") . " set ms_ilosc=" . $STAN_NOWY[$pr][$at] . "
                      where ms_ma_id=" . $magazyn . " and ms_pr_id=" . $pr . " and ms_at_id=" . $at);
                } else {
                    $db->query("insert into " . dn("magazyn_stan") . " set ms_ilosc=" . $STAN_NOWY[$pr][$at] . ",
                      ms_ma_id=" . $magazyn . ", ms_pr_id=" . $pr . ", ms_at_id=" . $at);
                }
            }
        }
    }
}

function zamowienie_uwaga($id, $msg, $uz = 0) {
    global $db;
    $db->query("insert into " . dn("sklep_zamowienie_uwaga") . " set zu_za_id=" . $id . ",zu_data_in=" . time() . ", zu_uz_id=" . $uz . ",zu_tresc='" . a($msg) . "'");
}

function zamowienie_anuluj($id) {
}

function m_url($mode, $id, $text, $url = "", $typ = "", $modul = "") {
    global $_GLOBAL;
    if ($url != "") return substr($url, 0, 11) == 'javascript:' ? $url : str_replace("this", $id, str_replace("&", "&amp;", str_replace("&amp;", "&", $url)));

    return $mode . "," . $id . "," . conv2file($text) . ".htm";

    switch (true) {
        case $typ == "modul":
            if ($_GLOBAL["modul"][$modul]["url"] != "") return "/mod," . $id . "," . conv2file($_GLOBAL["modul"][$modul]["nazwa"]) . "," . $_GLOBAL["modul"][$modul]["url"] . ".htm";

            return "/2," . $id . "," . conv2file($_GLOBAL["modul"][$modul]["nazwa"]) . ".htm";
            break;
        case $mode == "cms":
            return "/1," . $id . "," . conv2file($text) . ".htm";
            break;
        case $mode == "smodul":
            return "/2," . $id . "," . $typ . "," . $text . ".htm";
            break;
    }

    return "#";
}

/**
 * Uniwersalne
 */

function get_slownik($word, $ver) {
    if (!file_exists("pliki/slownik.txt")) return $word;
    $FILE = file("pliki/slownik.txt");
    foreach ($FILE as $line) {
        $A = explode("\t", trim($line));
        if ($A[0] == $word) return $A[$ver];
    }

    return $word;
}

function md($data) {
    return substr($data, -2) . "-" . substr($data, 5, 2) . "-" . substr($data, 0, 4);
}

function csv_log($plik, $T, $extra = "") {
    if (!is_array($T) and $T != "") $M[] = $T;
    elseif (is_array($T)) $M = $T;

    if ($extra == "call") {
        foreach ($_POST as $key => $val) $P[] = $key . "=" . $val;
        foreach ($_GET as $key => $val) $G[] = $key . "=" . $val;
        if (is_array($P)) $M[] = "_POST:" . join("&", $P);
        if (is_array($G)) $M[] = "_GET:" . join("&", $G);
    }

    if (($fp = fopen($plik, "a+")) === false) return false;
    fwrite($fp, date("Y-m-d H:i") . ";" . $_SERVER['REMOTE_ADDR'] . ";" . join(";", $M) . "\n");
    fclose($fp);

    return true;
}

function call_url($url, $file) {
    $header = "GET " . $file . " HTTP/1.0\r\n";
    $header .= "Host: $url\r\n";
    $header .= "Content-Type: application/x-www-form-urlencoded\r\n";
    $header .= "Content-Length: 0\r\n\r\n";
    //fopen
    $fp = fsockopen($url, 80, $errno, $errstr, 30);
    //$fp = @fopen ("http://".$url.$file,"r");
    if (!$fp) {
        debug("CALL URL CONNECTION ERROR TO: http://" . $url . $file);

        return '';
    } else {
        fputs($fp, $header);
        $res = false;
        while (!feof($fp)) {
//      $line = ereg_replace("[\n\r]","",fgets ($fp, 1024));
            $line  = fgets($fp, 1024);
            $RET[] = $line;
        }
    }
    fclose($fp);

    return join("", $RET);
}

function call_post($url, $P) {
    $user_agent = "Mozilla/4.0 (compatible; MSIE 5.01; Windows NT 5.0)";
    $ch         = curl_init();
    curl_setopt($ch, CURLOPT_POST, 1);
    if (count($P)) curl_setopt($ch, CURLOPT_POSTFIELDS, join("&", $P));
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 2);
    curl_setopt($ch, CURLOPT_USERAGENT, $user_agent);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    $result = curl_exec($ch);
    curl_close($ch);

    return $result;
}

function xml2array($xml) {
    $xmlary = array();

    if ((strlen($xml) < 256) && is_file($xml)) $xml = file_get_contents($xml);
    $ReElements   = '/<(\w+)\s*([^\/>]*)\s*(?:\/>|>(.*?)<(\/\s*\1\s*)>)/s';
    $ReAttributes = '/(\w+)=(?:"|\')([^"\']*)(:?"|\')/';

    preg_match_all($ReElements, $xml, $elements);
    foreach ($elements[1] as $ie => $xx) {
        $xmlary[$ie]["name"] = $elements[1][$ie];
        if ($attributes = trim($elements[2][$ie])) {
            preg_match_all($ReAttributes, $attributes, $att);
            foreach ($att[1] as $ia => $xx)
                // all the attributes for current element are added here
                $xmlary[$ie]["attributes"][$att[1][$ia]] = $att[2][$ia];
        } // if $attributes

        // get text if it's combined with sub elements
        $cdend = strpos($elements[3][$ie], "<");
        if ($cdend > 0) {
            $xmlary[$ie]["text"] = substr($elements[3][$ie], 0, $cdend - 1);
        } // if cdend

        if (substr($elements[3][$ie], 0, 9) != "<![CDATA[" and preg_match($ReElements, $elements[3][$ie])) {
            $xmlary[$ie]["elements"] = xml2array($elements[3][$ie]);
        } else if (isset($elements[3][$ie])) {
            $xmlary[$ie]["text"] = $elements[3][$ie];
        }
        // $xmlary[$ie]["closetag"] = $elements[4][$ie];
    }

    //foreach ?
    return $xmlary;
}


function my_strrpos($haystack, $needle, $offset = 0) {
    if (trim($haystack) != "" && trim($needle) != "" && $offset <= strlen($haystack)) {
        $last_pos = $offset;
        $found    = false;
        while (($curr_pos = strpos($haystack, $needle, $last_pos)) !== false) {
            $found    = true;
            $last_pos = $curr_pos + 1;
        }

        if ($found) {
            return $last_pos - 1;
        } else {
            return false;
        }
    } else {
        return false;
    }
}


function my_strip_tags($main) {
    return ereg_replace("\{([A-Z_]{1,20})\}", "", $main);
}

function my_format_case($str) {
    if ($str == "") return $str;
    $A = explode(" ", $str);
    foreach ($A as $key => $word) {
        $B = explode(",", $word);
        foreach ($B as $key2 => $word2) {
            $B[$key2] = substr($word2, 0, 1) . pl_strtolower(substr($word2, 1));
        }
        $A[$key] = join(",", $B);
    }

    return join(" ", $A);
}

function my_wordwrap($txt, $where) {
    if (empty($txt)) return false;
    for ($c = 0, $a = 0, $g = 0; $c < strlen($txt); $c++) {
        $d[$c + $g] = $txt[$c];
        if ($txt[$c] != " ") $a++;
        else if ($txt[$c] == " ") $a = 0;
        if ($a == $where) {
            $g++;
            $d[$c + $g] = " ";
            $a          = 0;
        }
    }

    return implode("", $d);
}

function get_post($mode = "") {
    global $_POST;
    $R = array();
    if ($mode == "notag")
        foreach ($_POST as $key => $val) $R[$key] = is_array($val) ? $val : strip_tags(trim($val));
    else
        foreach ($_POST as $key => $val) $R[$key] = is_array($val) ? $val : trim($val);

    return $R;
}

function my_mkdir($dir) {
    if (!is_dir($dir)) {
        mkdir($dir);
        @chmod($dir, 0777);
    }
}

function my_error($str) {
    global $_GLOBAL;
    $M["m_to"]      = "michal.bzowy@wp.pl";
    $M["m_subject"] = $_GLOBAL["page_url"] . " - ERROR";
    $B[]            = 'Automatyczne zgłoszenie błędu ze strony:</br>';
    $B[]            = '<a href="http://' . $_SERVER['HTTP_HOST'] . '/' . $_SERVER['PHP_SELF'] . '?' . $_SERVER['QUERY_STRING'] . '">http://' . $_SERVER['HTTP_HOST'] . '/' . $_SERVER['PHP_SELF'] . '?' . $_SERVER['QUERY_STRING'] . '</a><br/>';
    $B[]            = 'Wystątpił bład:<br/>';
    $B[]            = $str;
    $M["m_message"] = join("", $B);
    //my_mail_smtp($M);
}

function get_template($name, $ARG = '', $DELTAG = '', $zpliku = 1, $pre = "") {
    if ($zpliku) {
        if (!file_exists($pre . "template/" . TEMPLATE . "/" . $name . ".tpl")) die("Brak szablonu: " . $pre . "template/" . TEMPLATE . "/" . $name . ".tpl");
        $src = file_get_contents($pre . "template/" . TEMPLATE . "/" . $name . ".tpl");
    } else {
        $src = $name;
    }

    if (is_array($DELTAG)) {
        foreach ($DELTAG as $val) $src = del_template($val, $src);
    }

    if (is_array($ARG)) $src = strtr($src, $ARG);

    return $src;
}

function get_tag($src, $tag, &$ret) {
    $A   = explode("<!--{" . $tag . "}-->", $src, 2);
    $R[] = $A[0];
    if (count($A) != 2) return $src;
    $R[] = '{' . $tag . '}';
    $A   = explode("<!--/{" . $tag . "}-->", $A[1], 2);
    $ret = $A[0];
    $R[] = $A[1];
    if (count($A) != 2) return $src;

    return join("", $R);
}


//Ta funkcja usuwa z kodu $src bloki zawierajace sie w tagach <!--{$TAG}--> <!--/{$TAG}-->
function del_template($tag, $src) {
    $A   = explode("<!--{" . $tag . "}-->", $src, 2);
    $R[] = $A[0];
    if (count($A) != 2) return $src;
    $A   = explode("<!--/{" . $tag . "}-->", $src, 2);
    $R[] = $A[1];
    if (count($A) != 2) return $src;

    return join("", $R);
}

function get_url($str, $get = "", $extra = "") {
    switch ($str) {
        case "cms":
            $str = "cms.php?i=" . $get . $extra;
            break;
    }

    return $str;
}

function get_short($text, $len) {
    if (strlen($text) > $len + 1) {
        $text = trim(substr($text, 0, $len)) . "..";
    }

    return $text;
}

function get_link($str) {
    return $str;
}

function my_fread($fname, $mode = "") {
    if (!file_exists($fname)) return '';
    if (filesize($fname) == 0) return '';
    $main = file_get_contents($fname);
    if ($mode == "win") return win2iso($main);

    return $main;
}

function my_fwrite($fname, $src, $mode = "w+") {
    $fp = fopen($fname, $mode);
    fwrite($fp, $src);
    fclose($fp);
    //chmod($fname,0664);
    @chmod($fname, 0777);
}

function get_lista($id, $mode = 0, $pre = "") {
    static $LISTA;
    if (isset($LISTA[$id])) return $LISTA[$id];
    $R = array();
    $L = @file($pre . "files/list/" . $id . ".txt");
    if (!$L) {
        return array();
    }
//  unset($L[0]);
    foreach ($L as $line) {
        $line = trim($line);
        if ($line == "") continue;
        if (strpos($line, "|") !== false) $R[substr($line, 0, strpos($line, "|"))] = win2utf(ereg_replace("[\n\r]", "", substr($line, strpos($line, "|") + 1)));
        else $R[$line] = win2utf($line);
    }
    if ($mode) pl_asort($R);
    $LISTA[$id] = $R;

    return $R;
}

function get_file($plik, $ext, $x = "", $y = "", $alt = "", $url = "") {
    global $h, $_GLOBAL;
    switch ($ext) {
        case "jpg":
        case "gif":
        case "png":
            return '<img src="' . $plik . '.' . $ext . '" alt="' . $alt . '"/>';
            break;
        case "swf":
            return '
      <script type="text/javascript">CreateControl(\'' . $plik . '.swf\',' . $x . ',' . $y . ',\'' . $url . '\');</script>';
        case "flv":
            $w = 280;
            $h = 245;

            return '<object classid="clsid:d27cdb6e-ae6d-11cf-96b8-444553540000" codebase="http://fpdownload.macromedia.com/pub/shockwave/cabs/flash/swflash.cab#version=8,0,0,0" width="' . $w . '" height="' . $h . '" id="video" align="middle">
<param name="movie" value="' . $_GLOBAL["page_url"] . '/template/player.swf?film=/' . $plik . '.flv" /><param name="quality" value="high" /><param name="bgcolor" value="#ffffff" /><embed src="' . $_GLOBAL["page_url"] . '/template/player.swf?film=/' . $plik . '.flv" quality="high" bgcolor="#ffffff" width="' . $w . '" height="' . $h . '" name="video" align="middle" type="application/x-shockwave-flash" pluginspage="http://www.macromedia.com/go/getflashplayer" />
</object>
';
        case "wmv":
            $w = 280;
            $h = 245;

            return '<script type="text/javascript">
<!--
	if (navigator.appName == "Netscape" )
	{
		navigator.plugins.refresh();
	}
//-->
</script>
<!-- Embedded Microsoft Media Player object for Microsoft Internet Explorer. -->
<object ID="NSPlay" width="' . $w . '" height="' . $h . '" classid="CLSID:22d6f312-b0f6-11d0-94ab-0080c74c7e95" codebase="http://activex.microsoft.com/activex/controls/mplayer/en/nsmp2inf.cab#Version=5,1,52,701" standby="Loading Microsoft Windows Media Player components..." type="application/x-oleobject">
<param name="FileName" value="/' . $plik . '.wmv"><param name="ShowControls" value=1><param name="ShowPositionControls" value=0>
	<param name="ShowAudioControls" value=1><param name="ShowTracker" value=1><param name="ShowDisplay" value=0>
	<param name="ShowStatusBar" value=0><param name="ShowGotoBar" value=0><param name="ShowCaptioning" value=0>
	<param name="AutoStart" value=1><param name="AnimationAtStart" value=1><param name="TransparentAtStart" value=0>
	<param name="AllowChangeDisplaySize" value=0><param name="AllowScan" value=0><param name="ClickToPlay" value=0>
<embed type="application/x-mplayer2" pluginspage="http://www.microsoft.com/Windows/Downloads/Contents/Products/MediaPlayer/" name="NSPlay" src="/' . $plik . '.wmv" width="' . $w . '" height="' . $h . '" showcontrols="1" ShowPositionControls=0 ShowAudioControls=1 ShowTracker=1 ShowDisplay=0 ShowStatusBar=0 ShowGoToBar=0 ShowCaptioning=0 AutoStart=1 AutoRewind=0 AnimationAtStart=1 TransparentAtStart=0 AllowChangeDisplaySize=0 AllowScan=0 ClickToPlay=0>	</embed>
</object>
';
            break;
    }
}

function get_komunikat($A) {
    global $db, $_GLOBAL;

    if (is_array($A)) {
        $nr = $db->query("select ko_kod, ko_tresc from " . dn("komunikat") . " where ko_kod in ('" . join("','", $A) . "')");
        while ($B = $db->fetch($nr)) $T[$B[0]] = s($B[1]);
    } else {
        $T = $db->onerow("select ko_tresc from " . dn("komunikat") . " where ko_kod='" . $A . "'");
        $T = s($T[0]);
    }

    return $T;
}

function pass2code($str) {
    global $_GLOBAL;

    return md5($str);
}

function long_id($id, $max = 3) {
    for ($i = strlen($id); $i < $max; $i++) $id = "0" . $id;

    return $id;
}

function onload($val = "", $typ = "onload") {
    static $W = array();
    if ($val == "reset") $W[$typ] = array();
    elseif ($val != "") $W[$typ][] = $val;
    elseif (count($W) > 0) {
        $RET = array();
        if (isset($W["onload"])) $RET[] = ' onload="' . join(" ", $W["onload"]) . '"';
        if (isset($W["onunload"])) $RET[] = ' onload="' . join(" ", $W["onunload"]) . '"';
        if (isset($W["id"])) $RET[] = ' id="' . $W["id"][count($W["id"]) - 1] . '"';

        return join("", $RET);
    }
}

function get_head($val = "", $mode = "") {
    global $_GLOBAL;
    static $W = array();
    if ($val != "" and $mode == "css") $W[] = '<link type="text/css" rel="stylesheet" href="' . $_GLOBAL['page_url'] . 'template/{TEMPLATE}/' . $val . '.css"/>';
    elseif ($val != "" and $mode == "js") $W[] = '<script type="text/javascript" src="' . $_GLOBAL['page_url'] . 'inchtml/' . $val . '.js"></script>';
    elseif ($val != "") $W[] = $val;
    elseif (count($W) > 0) return join(chr(10) . "  ", $W);
}

function conv2file($string) {
    $RET    = array("ą" => "a", "ż" => "z", "ę" => "e", "ź" => "z", "ł" => "l", "ś" => "s", "ć" => "c", "ń" => "n", "ó" => "o");
    $string = strtr(pl_strtolower($string), $RET);
    $string = ereg_replace("[^a-z0-9._]", "_", ereg_replace(" ", "_", ereg_replace("%20", "_", $string)));
    $string = ereg_replace("[_]+", "_", $string);

    return $string;
}

function get_error($ERROR, $mode = "error") {
    global $b;
    if (!is_array($ERROR)) return;
    $C["error"] = ".messerr";
    $C["mess"]  = ".messinf";
    foreach ($ERROR as $line) {
        $R[] = $line . "";
    }

    return $b->div($C[$mode]) . join("<br/>", $R) . $b->div();
}

function get_zajawka($intext, $len, $dots = 1) {
    if (strlen($intext) > $len) {
        $text = substr($intext, 0, $len);
        if (strpos($text, " ") !== false) $text = substr($text, 0, strrpos($text, " "));
        if ($dots and $intext != $text) $text .= "...";
    } else $text = $intext;

    return $text;
}

function dn($db_name) {
    global $_GLOBAL;
    if ($_GLOBAL["db_pre"] != "") $db_name = $_GLOBAL["db_pre"] . "_" . $db_name;

    return $db_name;
}

function debug($val) {
    global $_GLOBAL;
    if (!$_GLOBAL["debug"]) return;
    if (is_array($val) or is_object($val)) {
        print('<pre>');
        print_r($val);
        print('</pre>');
    } else print($val);
    print("<br>");
}

function test_real($real) {
    if (ereg("^[0-9]*$", $real)) return true;
    if (ereg("^[0-9]*([,]|[.])[0-9]{1,2}$", $real)) return true;

    return false;
}

function test_int($real) {
    if (ereg("^[0-9]*$", $real)) return true;

    return false;
}

function test_email($email) {
    if (eregi("([-a-z0-9_]+(\.[_a-z0-9-]+)*@([a-z0-9-]+(\.[a-z0-9-]+)+))", $email)) return true;

    return false;
}

#
# Testuje czy data jest poprawna i czy wprowadzona w poprawnym formacie
# Dostepne formaty to: 2001.5.4, 2000.05.04, 2001.05.4, 2001.5.04 (RRRR.M[M].D[D])
#                      2001-5-4, 2001-05-04, 2001-05-4, 2001-5-04 (RRRR-M[M]-D[D])
#                      4.5.2001, 04.05.2001, 4.05.2001, 04.5.2001 (D[D].M[M].RRRR)
#                      4-5-2001, 04-05-2001, 4-05-2001, 04-5-2001 (D[D]-M[M]-RRRR)
#         format CIEL: 20010405 (RRRRMMDD)
#         tylko dzien: 4, 04 (D[D])
#     dzien i miesiac: 4-5, 04-05, 4-05, 04-5 (D[D]-M[M])
#                      4.5, 04.05, 4.05, 04.5 (D[D].M[M])
#
# Przyklad uzycia:
#   if (!($data = testdate("2001-01-20"))) blad ("Bledna data !");
#   $data - zawiera false jesli data bledna lub UNIX'owy znacznik czasu jesli ok
#
function test_date($date) {
    if (ereg("^[0-9]{1,2}$", $date)) {
        # uzupelniamy miesiac
        $mies = date("m");
        if ($date < date("d")) $mies++;
        if ($mies > 12) $mies = 1;
        $date .= "-" . $mies;
    }

    if (ereg("^[0-9]{1,2}([.-])([0-9]{1,2})$", $date, $regs)) {
        # uzupelniamy rok
        $rok = date("Y");
        if ($regs[2] < date("m")) $rok++;
        $date .= $regs[1] . $rok;
    }

    if (ereg("^[0-9]{4}[.-][0-9]{1,2}[.-][0-9]{1,2}$", $date)) {
        $tab = explode(substr($date, 4, 1), $date);
        if (!checkdate($tab[1], $tab[2], $tab[0])) return false;
        else return mktime(0, 0, 0, $tab[1], $tab[2], $tab[0]);
    } else if (ereg("^[0-9]{1,2}[.-][0-9]{1,2}[.-][0-9]{4}$", $date)) {
        $tab = explode(substr($date, strlen($date) - 5, 1), $date);
        if (!checkdate($tab[1], $tab[0], $tab[2])) return false;
        else return mktime(0, 0, 0, $tab[1], $tab[0], $tab[2]);
    } else if (ereg("^[0-9]{8}$", $date)) {
        $y = substr($date, 0, 4);
        $m = substr($date, 4, 2);
        $d = substr($date, 6, 2);

        if (!checkdate($m, $d, $y)) return false;
        else return mktime(0, 0, 0, $m, $d, $y);

    } else return false;
}

function test_is_empty($POLE, $ARG) {
    foreach ($POLE as $pole) {
        $ARG[$pole] = trim($ARG[$pole]);
        if ($ARG[$pole] == "") $ERROR[$pole] = true;
    }
    if (isset($ERROR)) return $ERROR;

    return true;
}

function hs($val) {
    return htmlspecialchars(stripslashes($val));
}

function h($tekst, $nbsp = false) {
    if ($tekst != "") return htmlspecialchars($tekst);
    if ($nbsp) return " ";

    return "";
}

function s($val) {
    return stripslashes($val);
}

function a($val) {
    return addslashes($val);
}

function a2($val) {
    $out = mysql_real_escape_string($val);
    $out = strip_tags($out);

    return $out;
}

function redirect($plik, $mode = "print") {
    if ($mode == "print") {
        print "<html><head><meta http-equiv=\"refresh\" content=\"0;URL=" . $plik . "\"></head></html>";
        exit;
    } else {
        return '<html xmlns="http://www.w3.org/1999/xhtml"><head><meta http-equiv="refresh" content="2;URL=' . $plik . '">{HEAD}</head><body>{STRONA}</body></html>';
    }
}

function pl_sort(&$array) {
    usort($array, "pl_compare");
}

function pl_asort(&$array) {
    uasort($array, "pl_compare");
}

function pl_ksort(&$array) {
    uksort($array, "pl_compare");
}

function pl_compare($a, $b) {
    return strcmp(pl_sortkey((string)$a), pl_sortkey((string)$b));
}

function pl_sortkey($a) {
    static $T;
    if (!isset($T))
        $T = array("�" => "a ", "�" => "c ", "�" => "e ", "�" => "l ", "�" => "n ", "�" => "o ",
                   "�" => "s ", "�" => "z ", "�" => "z  ", "�" => "a ", "�" => "c ", "�" => "e ",
                   "�" => "l ", "�" => "n ", "�" => "o ", "�" => "s ", "�" => "z ", "�" => "z  ");

    return strtolower(strtr($a, $T));
}

function pl_dzien($dzien, $mode = 0) {
    $WEEK[0] = array("Niedziela", "Poniedziałek", "Wtorek", "Środa", "Czwartek", "Piątek", "Sobota");
    $WEEK[1] = array("niedziela", "poniedziałek", "wtorek", "środa", "czwartek", "piątek", "sobota");

    return $WEEK[$mode][$dzien];
}

function pl_miesiac($msc, $mode = 0) {
    $ROK[0] = array("Styczeń", "Luty", "Marzec", "Kwiecień", "Maj", "Czerwiec",
        "Lipiec", "Sierpień", "Wrzesień", "Październik", "Listopad", "Grudzień");
    $ROK[1] = array("stycznia", "lutego", "marca", "kwietnia", "maja", "czerwca",
        "lipca", "sierpnia", "września", "października", "listopada", "grudnia");

    return $ROK[$mode][((int)$msc - 1)];
}

$pl_male = "abcdefghijklmnopqrstuvwxyząęńłóśćżź";
$pl_duze = "ABCDEFGHIJKLMNOPQRSTUVWXYZĄĘŃŁÓŚĆŻŹ";

function pl_strtolower($s) {
    global $pl_male;
    global $pl_duze;

    return strtr($s, $pl_duze, $pl_male);
}

function pl_strtoupper($s) {
    global $pl_male;
    global $pl_duze;

    return strtr($s, $pl_male, $pl_duze);
}


function m2rzym($msc) {
    $MSC = array(1 => "I", "II", "III", "IV", "V", "VI", "VII", "VIII", "IX", "X", "XI", "XII");

    return $MSC[$msc];
}


function getext($filename) {
    $filename = basename(strval($filename));
    while (substr($filename, 0, 1) == '.') $filename = substr($filename, 1);
    while (substr($filename, -1) == '.') $filename = substr($filename, 0, -1);
    $ext = strrchr($filename, '.');

    if (substr($ext, 0, 1) == '.') $ext = substr($ext, 1);

    return $ext;
}

function fixed_phpversion() {
    $curver = explode(".", phpversion());
    $v      = (int)$curver[0] . "." . (int)$curver[1] . "." . (int)$curver[2];

    return $v;
}

function createthumb($src_file, $maxX, $maxY, $dest_file = "", $quality = 90, $size = "fit") {
    if ($size == "crop") {
        cropthumb($src_file, $dest_file, $maxX, $maxY);

        return;
    }
    // find the image size & type
    $testgd = get_extension_funcs("gd"); // Grab function list
    if (!$testgd) {
        return "GD library is not installed";
    }

    $imginfo = @getimagesize($src_file);
    switch ($imginfo[2]) {
        case 1:
            $type = IMG_GIF;
            break;
        case 2:
            $type = IMG_JPG;
            break;
        case 3:
            $type = IMG_PNG;
            break;
        case 4:
            $type = IMG_WBMP;
            break;
        default:
            return "$src_file: Unknown image format";
            break;
    }

    // height/width
    $srcX = $imginfo[0];
    $srcY = $imginfo[1];

    if ($srcX <= $maxX && $srcY <= $maxY) {
        if (strcasecmp(getext($src_file), getext($dest_file)) == 0) {
            if (!copy($src_file, $dest_file)) return "Unable to copy $src_file to $dest_file\n";

            return "";
        }

        // Different extensions
        $destX = $srcX;
        $destY = $srcY;
        $ratio = 1;
    } else {
        $ratio = min(($maxX / $srcX), ($maxY / $srcY));
        $destX = (int)($srcX * $ratio + 0.5);
        $destY = (int)($srcY * $ratio + 0.5);
    }

    $srcImage = null;

    // Set memory limit to 3*unpacked image size + 8M
    global $g_bGDSetMemoryLimit;
    if ($g_bGDSetMemoryLimit) {
        $mem = (int)((3 * $srcX * $srcY * 4) / 1000 / 1000 + 8);
        @ini_set("memory_limit", "{$mem}M");
    }
    switch ($type) {
        case IMG_GIF:
            if (function_exists('imagecreatefromgif')) $srcImage = @imagecreatefromgif($src_file);
            break;
        case IMG_JPG:
            if (function_exists('imagecreatefromjpeg')) $srcImage = @imagecreatefromjpeg($src_file);
            break;
        case IMG_PNG:
            if (function_exists('imagecreatefrompng')) $srcImage = @imagecreatefrompng($src_file);
            break;
        case IMG_WBMP:
            if (function_exists('imagecreatefromwbmp')) $srcImage = @imagecreatefromwbmp($src_file);
            break;
    }

    if (empty($srcImage) or !$srcImage) {
        define('THUMB_ERR', "$src_file: Image format is not supported by your version of GD library");

        return false;
    }

    // resize
    $g_bGDVer2 = (fixed_phpversion() > "4.3");
    $bSuccess  = true;
    $destImage = null;
    if ($g_bGDVer2) $destImage = imagecreatetruecolor($destX, $destY);
    if (!$destImage) $bSuccess = false;
    if ($bSuccess && $g_bGDVer2) $bSuccess = imagecopyresampled($destImage, $srcImage, 0, 0, 0, 0, $destX, $destY, $srcX, $srcY);
    if (!$bSuccess) {
        if ($destImage) $bSuccess = imagecopyresized($destImage, $srcImage, 0, 0, 0, 0, $destX, $destY, $srcX, $srcY);
        if (!$bSuccess) {
            $destImage = imagecreate($destX, $destY);
            if ($destImage) $bSuccess = imagecopyresized($destImage, $srcImage, 0, 0, 0, 0, $destX, $destY, $srcX, $srcY);
        }
    }
    if (!$bSuccess) return "An GD library error occured resizing image.";

    // create and save final picture
    if (in_array($dest_file, array("jpg", "jpeg", "gif", "png", "bmp"))) {
        $ext       = $dest_file;
        $dest_file = '';
    } else {
        $ext = strtolower(getext($dest_file));
    }

    switch ($ext) {
        case 'gif':
            $bSuccess = imagegif($destImage, $dest_file);
            break;
        case 'jpg':
        case 'jpeg':
            $bSuccess = imagejpeg($destImage, $dest_file, $quality);
            break;
        case 'png':
            $bSuccess = imagepng($destImage, $dest_file);
            break;
        case 'bmp':
            $bSuccess = imagewbmp($destImage, $dest_file);
            break;
    }

    if (!$bSuccess) return "Unable to write image file $dest_file";

    // free the memory
    @imagedestroy($srcImage);
    @imagedestroy($destImage);

    @chmod($dest_file, 0664);

    return true;
}

function cropthumb($src, $dst, $dstx, $dsty) {

    //$src = original image location
    //$dst = destination image location
    //$dstx = user defined width of image
    //$dsty = user defined height of image

    $allowedExtensions = 'jpg jpeg gif png';

    $name              = explode(".", $src);
    $currentExtensions = $name[count($name) - 1];
    $extensions        = explode(" ", $allowedExtensions);

    for ($i = 0; count($extensions) > $i; $i = $i + 1) {
        if ($extensions[$i] == $currentExtensions) {
            $extensionOK   = 1;
            $fileExtension = $extensions[$i];
            break;
        }
    }

    if ($extensionOK) {
        $size   = getImageSize($src);
        $width  = $size[0];
        $height = $size[1];

        if ($width >= $dstx AND $height >= $dsty) {
            $proportion_X = $width / $dstx;
            $proportion_Y = $height / $dsty;

            if ($proportion_X > $proportion_Y) {
                $proportion = $proportion_Y;
            } else {
                $proportion = $proportion_X;
            }
            $target['width']  = $dstx * $proportion;
            $target['height'] = $dsty * $proportion;

            $original['diagonal_center'] =
                round(sqrt(($width * $width) + ($height * $height)) / 2);
            $target['diagonal_center']   =
                round(sqrt(($target['width'] * $target['width']) + ($target['height'] * $target['height'])) / 2);

            $crop = round($original['diagonal_center'] - $target['diagonal_center']);

            if ($proportion_X < $proportion_Y) {
                $target['x'] = 0;
                $target['y'] = round((($height / 2) * $crop) / $target['diagonal_center']);
            } else {
                $target['x'] = round((($width / 2) * $crop) / $target['diagonal_center']);
                $target['y'] = 0;
            }

            if ($fileExtension == "jpg" OR $fileExtension == 'jpeg') {
                $from = ImageCreateFromJpeg($src);
            } elseif ($fileExtension == "gif") {
                $from = ImageCreateFromGIF($src);
            } elseif ($fileExtension == 'png') {
                $from = imageCreateFromPNG($src);
            }

            $new = ImageCreateTrueColor($dstx, $dsty);

            imagecopyresampled($new, $from, 0, 0, $target['x'], $target['y'], $dstx, $dsty, $target['width'], $target['height']);

            if ($fileExtension == "jpg" OR $fileExtension == 'jpeg') {
                imagejpeg($new, $dst, 90);
            } elseif ($fileExtension == "gif") {
                imagegif($new, $dst);
            } elseif ($fileExtension == 'png') {
                imagepng($new, $dst);
            }
        }
    }
}


function win2iso($string) {
    $ibm = array("'" . chr(165) . "'", "'" . chr(198) . "'", "'" . chr(202) . "'", "'" . chr(163) . "'",
        "'" . chr(209) . "'", "'" . chr(211) . "'", "'" . chr(140) . "'", "'" . chr(143) . "'",
        "'" . chr(175) . "'", "'" . chr(185) . "'", "'" . chr(230) . "'", "'" . chr(234) . "'",
        "'" . chr(179) . "'", "'" . chr(241) . "'", "'" . chr(243) . "'", "'" . chr(156) . "'",
        "'" . chr(159) . "'", "'" . chr(191) . "'");

    $iso = array(chr(161), chr(198), chr(202), chr(163), chr(209), chr(211), chr(166), chr(172),
        chr(175), chr(177), chr(230), chr(234), chr(179), chr(241), chr(243), chr(182),
        chr(188), chr(191));

    return preg_replace($ibm, $iso, $string);
}

function iso2win($string) {
    $ibm = array("'" . chr(161) . "'", "'" . chr(198) . "'", "'" . chr(202) . "'", "'" . chr(163) . "'",
        "'" . chr(209) . "'", "'" . chr(211) . "'", "'" . chr(166) . "'", "'" . chr(172) . "'",
        "'" . chr(175) . "'", "'" . chr(177) . "'", "'" . chr(230) . "'", "'" . chr(234) . "'",
        "'" . chr(179) . "'", "'" . chr(241) . "'", "'" . chr(243) . "'", "'" . chr(182) . "'",
        "'" . chr(188) . "'", "'" . chr(191) . "'");
    $iso = array(chr(165), chr(198), chr(202), chr(163),
        chr(209), chr(211), chr(140), chr(143),
        chr(175), chr(185), chr(230), chr(234),
        chr(179), chr(241), chr(243), chr(156),
        chr(159), chr(191));

    return preg_replace($ibm, $iso, $string);
}

function iso2utf($string) {
    $in = array("'" . chr(161) . "'", "'" . chr(198) . "'", "'" . chr(202) . "'", "'" . chr(163) . "'", "'" . chr(209) . "'", "'" . chr(211) . "'", "'" . chr(166) . "'", "'" . chr(172) . "'",
        "'" . chr(175) . "'", "'" . chr(177) . "'", "'" . chr(230) . "'", "'" . chr(234) . "'", "'" . chr(179) . "'", "'" . chr(241) . "'", "'" . chr(243) . "'", "'" . chr(182) . "'",
        "'" . chr(188) . "'", "'" . chr(191) . "'");

    $out = array(chr(196) . chr(132), chr(196) . chr(134), chr(196) . chr(152), chr(197) . chr(129),
        chr(197) . chr(131), chr(195) . chr(147), chr(197) . chr(154), chr(197) . chr(185),
        chr(197) . chr(187),
        chr(196) . chr(133), chr(196) . chr(135), chr(196) . chr(153), chr(197) . chr(130),
        chr(197) . chr(132), chr(195) . chr(179), chr(197) . chr(155), chr(197) . chr(186),
        chr(197) . chr(188));

    return preg_replace($in, $out, $string);
}

function win2utf($string) {
    return iso2utf(win2iso($string));
}

function utf2iso($string) {
    // ACELNOSXZ acelnosxz
    $in = array("'" . chr(196) . chr(132) . "'", "'" . chr(196) . chr(134) . "'",
        "'" . chr(196) . chr(152) . "'", "'" . chr(197) . chr(129) . "'",
        "'" . chr(197) . chr(131) . "'", "'" . chr(195) . chr(147) . "'",
        "'" . chr(197) . chr(154) . "'", "'" . chr(197) . chr(185) . "'",
        "'" . chr(197) . chr(187) . "'",
        "'" . chr(196) . chr(133) . "'", "'" . chr(196) . chr(135) . "'",
        "'" . chr(196) . chr(153) . "'", "'" . chr(197) . chr(130) . "'",
        "'" . chr(197) . chr(132) . "'", "'" . chr(195) . chr(179) . "'",
        "'" . chr(197) . chr(155) . "'", "'" . chr(197) . chr(186) . "'",
        "'" . chr(197) . chr(188) . "'");

    $out = array(chr(161), chr(198), chr(202), chr(163), chr(209), chr(211), chr(166), chr(172),
        chr(175), chr(177), chr(230), chr(234), chr(179), chr(241), chr(243), chr(182),
        chr(188), chr(191));

    return preg_replace($in, $out, $string);
}

function utf2none($string) {
    // ACELNOSXZ acelnosxz
    $in = array("'" . chr(196) . chr(132) . "'", "'" . chr(196) . chr(134) . "'",
        "'" . chr(196) . chr(152) . "'", "'" . chr(197) . chr(129) . "'",
        "'" . chr(197) . chr(131) . "'", "'" . chr(195) . chr(147) . "'",
        "'" . chr(197) . chr(154) . "'", "'" . chr(197) . chr(185) . "'",
        "'" . chr(197) . chr(187) . "'",
        "'" . chr(196) . chr(133) . "'", "'" . chr(196) . chr(135) . "'",
        "'" . chr(196) . chr(153) . "'", "'" . chr(197) . chr(130) . "'",
        "'" . chr(197) . chr(132) . "'", "'" . chr(195) . chr(179) . "'",
        "'" . chr(197) . chr(155) . "'", "'" . chr(197) . chr(186) . "'",
        "'" . chr(197) . chr(188) . "'");

    $out = array(chr(65), chr(67), chr(69), chr(76),
        chr(78), chr(79), chr(83), chr(88),
        chr(90),
        chr(97), chr(99), chr(101), chr(108),
        chr(110), chr(111), chr(115), chr(122),
        chr(122));

    return preg_replace($in, $out, $string);
}


function arab2roman($nr) {
    $rom = array();

    $rom[1]   = "I";
    $rom[2]   = "II";
    $rom[3]   = "III";
    $rom[4]   = "IV";
    $rom[5]   = "V";
    $rom[6]   = "VI";
    $rom[7]   = "VII";
    $rom[8]   = "VIII";
    $rom[9]   = "IX";
    $rom[10]  = "X";
    $rom[11]  = "XI";
    $rom[12]  = "XII";
    $rom[13]  = "XIII";
    $rom[14]  = "XIV";
    $rom[15]  = "XV";
    $rom[16]  = "XVI";
    $rom[17]  = "XVII";
    $rom[18]  = "XVIII";
    $rom[19]  = "XIX";
    $rom[20]  = "XX";
    $rom[21]  = "XXI";
    $rom[22]  = "XXII";
    $rom[23]  = "XXIII";
    $rom[24]  = "XXIV";
    $rom[25]  = "XXV";
    $rom[26]  = "XXVI";
    $rom[27]  = "XXVII";
    $rom[28]  = "XXVIII";
    $rom[29]  = "XXIX";
    $rom[30]  = "XXX";
    $rom[50]  = "L";
    $rom[100] = "C";
    if (isset($rom[$nr])) return $rom[$nr];

    return $nr;
}

function prepareUrl($str) {
    $out = '';
    $co  = array('-', '/', ',', ' ', '__', 'ą', 'ę', 'ś', 'ć', 'ż', 'ź', 'ł', 'ń', 'ó', 'Ą', 'Ę', 'Ś', 'Ć', 'Ż', 'Ź', 'Ł', 'Ń', 'Ó', '�', "'", "\\");
    $na  = array('_', '_', '_', '_', '_', 'a', 'e', 's', 'c', 'z', 'z', 'l', 'n', 'o', 'A', 'E', 'S', 'C', 'Z', 'Z', 'L', 'N', 'O', 'z', '_', '');
    $out = str_replace($co, $na, $str);

    return $out;
}

function get_main_menu($lang) {
    global $db, $_GLOBAL, $L;

    $out   = '';
    $query = "SELECT * FROM " . dn('menu') . " WHERE type=1 AND lang='" . $lang . "' ORDER BY sort_order, id;";
    $res   = $db->query($query);
    //$dane = $db->fetch($res);

    while ($T = $db->fetch($res)) {
        $out .= '<li><a href="' . $_GLOBAL['page_url'] . $_GLOBAL['lang'] . '/' . $T['link'] . '" target="_self">' . str_replace('&', '&amp;', $T['name']) . '</a>';

        if (($T['id'] == 6) || ($T['id'] == 55)) { // news & events
            $out .= '<div id="nae" class="sub">';
            $out .= '<ul>';
            $out .= '<li><a href="http://gomezworld.com" target="_blank">Gomez Fashion Magazine</a></li>';
            $out .= '<li><a href="http://gomez.pl/' . $_GLOBAL['lang'] . '/news_events/">News & Events</a></li>';
            $out .= '<li><a href="http://gomez.pl/' . $_GLOBAL['lang'] . '/fasion_store/">Fashion Store</a></li>';
            $out .= '</ul>';
            $out .= '</div>';
        }

        if (($T['id'] == 1) || ($T['id'] == 50)) //1 - woman
        {
            $out .= '<div class="sub">';
            $query = "select * from " . dn('sklep_kategoria') . " where ka_id=" . (($_GLOBAL['langid'] != 1) ? '219' : '121') . ";";
            $r     = $db->query($query);
            $fitem = $db->fetch($r);

            $query = "select * from " . dn('sklep_kategoria') . " where ka_ka_id=" . (($_GLOBAL['langid'] != 1) ? '219' : '121') . " order by ka_pozycja;";
            $r     = $db->query($query);
            while ($item = $db->fetch($r)) {
                $out .= '<ul>';
                $out .= '<li><div class="nav-header"><a href="' . $_GLOBAL['page_url'] . '' . $_GLOBAL['lang'] . '/' . $fitem['ka_alias'] . '/' . $item['ka_alias'] . '/">' . $item['ka_nazwa'] . '</a></div></li>';

                $query = "select * from " . dn('sklep_kategoria') . " where ka_ka_id=" . $item['ka_id'] . " and ka_widoczna>0 and ka_menu=1 order by ka_pozycja;";
                $sr    = $db->query($query);
                $nr    = $db->num_rows($sr);
                $nr2   = 0;
                $lp    = 1;
                while ($sitem = $db->fetch($sr)) {
                    $out .= '<li><a href="' . $_GLOBAL['page_url'] . '' . $_GLOBAL['lang'] . '/' . $fitem['ka_alias'] . '/' . $sitem['ka_alias'] . '/">' . $sitem['ka_nazwa'] . '</a></li>';
                    if (($lp++ % 7 == 0) && (($nr + $nr2) != ($lp - 1))) $out .= '</ul><ul><li><div class="nav-header">&nbsp;</div></li>';

                    $query = "select * from " . dn('sklep_kategoria') . " where ka_ka_id=" . $sitem['ka_id'] . " and ka_widoczna>0 and ka_menu=1 order by ka_pozycja;";
                    $sr2   = $db->query($query);
                    $nr2 += $db->num_rows($sr2);
                    //$lp=1;
                    while ($sitem2 = $db->fetch($sr2)) {
                        $out .= '<li><a href="' . $_GLOBAL['page_url'] . '' . $_GLOBAL['lang'] . '/' . $fitem['ka_alias'] . '/' . $sitem['ka_alias'] . '/' . $sitem2['ka_alias'] . '/">' . $sitem2['ka_nazwa'] . '</a></li>';
                        if (($lp++ % 7 == 0) && (($nr2 + $nr) != ($lp - 1))) $out .= '</ul><ul><li><div class="nav-header">&nbsp;</div></li>';
                    }
                }
                if ($lp % 8 == 0) $out .= '</ul><ul><li><div class="nav-header">&nbsp;</div></li>';
                $out .= '<li class="more"><a href="' . $_GLOBAL['page_url'] . '' . $_GLOBAL['lang'] . '/' . $fitem['ka_alias'] . '/' . $item['ka_alias'] . '/">' . $L['{T_WIECEJ2}'] . '</a></li>';
                $out .= '</ul>';
            }
            $out .= '</div>';
        }

        if (($T['id'] == 2) || ($T['id'] == 51)) //2 - men
        {
            $query = "select * from " . dn('sklep_kategoria') . " where ka_id=" . (($_GLOBAL['langid'] != 1) ? '220' : '122') . ";";
            $r     = $db->query($query);
            $fitem = $db->fetch($r);

            $out .= '<div class="sub">';

            $query = "select * from " . dn('sklep_kategoria') . " where ka_ka_id=" . (($_GLOBAL['langid'] != 1) ? '220' : '122') . " order by ka_pozycja;";
            $r     = $db->query($query);
            while ($item = $db->fetch($r)) {
                $out .= '<ul>';
                $out .= '<li><div class="nav-header"><a href="' . $_GLOBAL['page_url'] . '' . $_GLOBAL['lang'] . '/' . $fitem['ka_alias'] . '/' . $item['ka_alias'] . '/">' . $item['ka_nazwa'] . '</a></div></li>';

                $query = "select * from " . dn('sklep_kategoria') . " where ka_ka_id=" . $item['ka_id'] . " and ka_widoczna>0 and ka_menu=1 order by ka_pozycja;";
                $sr    = $db->query($query);
                $nr    = $db->num_rows($sr);
                $nr2   = 0;
                $lp    = 1;
                while ($sitem = $db->fetch($sr)) {
                    $out .= '<li><a href="' . $_GLOBAL['page_url'] . '' . $_GLOBAL['lang'] . '/' . $fitem['ka_alias'] . '/' . $sitem['ka_alias'] . '/">' . $sitem['ka_nazwa'] . '</a></li>';
                    if (($lp++ % 7 == 0) && (($nr + $nr2) != ($lp - 1))) $out .= '</ul><ul><li><div class="nav-header">&nbsp;</div></li>';

                    $query = "select * from " . dn('sklep_kategoria') . " where ka_ka_id=" . $sitem['ka_id'] . " and ka_widoczna>0 and ka_menu=1 order by ka_pozycja;";
                    $sr2   = $db->query($query);
                    $nr2 += $db->num_rows($sr2);
                    while ($sitem2 = $db->fetch($sr2)) {
                        $out .= '<li>' . $lp . '<a href="' . $_GLOBAL['page_url'] . '' . $_GLOBAL['lang'] . '/' . $fitem['ka_alias'] . '/' . $sitem['ka_alias'] . '/' . $sitem2['ka_alias'] . '/">' . $sitem2['ka_nazwa'] . '</a></li>';
                        if (($lp++ % 7 == 0) && (($nr2 + $nr) != ($lp - 1))) $out .= '</ul><ul><li><div class="nav-header">&nbsp;</div></li>';
                    }
                }
                if ($lp % 8 == 0) $out .= '</ul><ul><li><div class="nav-header">&nbsp;</div></li>';
                $out .= '<li class="more"><a href="' . $_GLOBAL['page_url'] . '' . $_GLOBAL['lang'] . '/' . $fitem['ka_alias'] . '/' . $item['ka_alias'] . '/">' . $L['{T_WIECEJ2}'] . '</a></li>';
                $out .= '</ul>';
            }
            $out .= '</div>';
        }

        if (($T['id'] == 5) || ($T['id'] == 22) || ($T['id'] == 54)) //5 - kids
        {
            $out .= '<div class="sub">';
            $query = "select * from " . dn('sklep_kategoria') . " where ka_id=" . (($_GLOBAL['langid'] != 1) ? '308' : '277') . ";";
            debug($query);
            $r     = $db->query($query);
            $fitem = $db->fetch($r);

            $query = "select * from " . dn('sklep_kategoria') . " where ka_ka_id=" . (($_GLOBAL['langid'] != 1) ? '308' : '277') . " order by ka_pozycja;";
            debug($query);
            $r     = $db->query($query);
            while ($item = $db->fetch($r)) {
                $out .= '<ul>';
                $out .= '<li><div class="nav-header" id="kidsMargin"><a href="' . $_GLOBAL['page_url'] . '' . $_GLOBAL['lang'] . '/' . $fitem['ka_alias'] . '/' . $item['ka_alias'] . '/">' . $item['ka_nazwa'] . '</a></div></li>';

                $query = "select * from " . dn('sklep_kategoria') . " where ka_ka_id=" . $item['ka_id'] . " and ka_widoczna>0 and ka_menu=1 order by ka_pozycja;";
                debug($query);
                $sr    = $db->query($query);
                $nr    = $db->num_rows($sr);
                $nr2   = 0;
                $lp    = 1;
                while ($sitem = $db->fetch($sr)) {
                    $out .= '<li><a href="' . $_GLOBAL['page_url'] . '' . $_GLOBAL['lang'] . '/' . $fitem['ka_alias'] . '/' . $sitem['ka_alias'] . '/">' . $sitem['ka_nazwa'] . '</a></li>';
                    if (($lp++ % 7 == 0) && (($nr + $nr2) != ($lp - 1))) $out .= '</ul><ul><li><div class="nav-header">&nbsp;</div></li>';

                    $query = "select * from " . dn('sklep_kategoria') . " where ka_ka_id=" . $sitem['ka_id'] . " and ka_widoczna>0 and ka_menu=1 order by ka_pozycja;";

                    $sr2   = $db->query($query);
                    $nr2 += $db->num_rows($sr2);
                    //$lp=1;
                    while ($sitem2 = $db->fetch($sr2)) {
                        $out .= '<li><a href="' . $_GLOBAL['page_url'] . '' . $_GLOBAL['lang'] . '/' . $fitem['ka_alias'] . '/' . $sitem['ka_alias'] . '/' . $sitem2['ka_alias'] . '/">' . $sitem2['ka_nazwa'] . '</a></li>';
                        if (($lp++ % 7 == 0) && (($nr2 + $nr) != ($lp - 1))) $out .= '</ul><ul><li><div class="nav-header">&nbsp;</div></li>';
                    }
                }
                if ($lp % 8 == 0) $out .= '</ul><ul><li><div class="nav-header">&nbsp;</div></li>';
//                $out .= '<li class="more"><a href="' . $_GLOBAL['page_url'] . '' . $_GLOBAL['lang'] . '/' . $fitem['ka_alias'] . '/' . $item['ka_alias'] . '/">' . $L['{T_WIECEJ2}'] . '</a></li>';
                $out .= '</ul>';
            }
            // brands
            $out .= '<ul>';
            $out .= '<li><div class="nav-header"><a href="' . $_GLOBAL['page_url'] . $_GLOBAL['lang'] . '/kids/brands/">BRANDS</a></div></li>';

            $query = "select * from " . dn('sklep_producent') . " where pd_s='y' order by pd_nazwa;";

            $query_id_pd = "SELECT DISTINCT mm_sklep_produkt.pr_pd_id FROM mm_sklep_produkt LEFT JOIN mm_sklep_kategoria_produkt ON mm_sklep_produkt.pr_id = mm_sklep_kategoria_produkt.kp_pr_id WHERE mm_sklep_kategoria_produkt.kp_ka_id = 277";
//            $query_id_pd = "SELECT ka_nazwa FROM mm_sklep_kategoria WHERE ka_id = 277";

            $res_id_pd = $db->query($query_id_pd);

            $ile_pd_nr = 0;
            while ( $ile_pd = $db->fetch($res_id_pd)) {
                $ile_pd_nr += 1;
                $ile_pd_tab[$ile_pd_nr] = $ile_pd[0];
            }


            $respd = $db->query($query);
            $nr    = $db->num_rows($query);
            $lp    = 1;
           for ($i = 1 ; $i <= $ile_pd_nr ; $i++) {
               $guery_brands = "SELECT * FROM mm_sklep_producent WHERE pd_id =" . $ile_pd_tab[$i];
               $res_Brandstab = $db->query($guery_brands);
               $res_Brands = $db->fetch($res_Brandstab);
               debug($ile_pd_tab[$i]);
               debug($res_Brands['pd_nazwa']);
                $out .= '<li><a href="' . $_GLOBAL['page_url'] . $_GLOBAL['lang'] . '/wyszukiwarka/?a=s&kategoria=&kategoria=277&brands%5B' . s($res_Brands['pd_id']) . '%5D=' . $res_Brands['pd_id'] . '">' . s($res_Brands['pd_nazwa']) . '</a></li>';
//                $out .= '<li><a href="' . $_GLOBAL['page_url'] . $_GLOBAL['lang'] . '/brands/kids/' . $itempd['pd_alias'] . '/">' . s($itempd['pd_nazwa']) . '</a></li>';
                if (($lp++ % 8 == 0) && ($nr != ($lp - 1))) $out .= '</ul><ul><li><div class="nav-header">&nbsp;</div></li>';
            }
            //*******************
//            $respd = $db->query($query);
//            $nr    = $db->num_rows($query);
//            $lp    = 1;
//            while ($itempd = $db->fetch($respd)) {
//                $out .= '<li><a href="' . $_GLOBAL['page_url'] . $_GLOBAL['lang'] . '/wyszukiwarka/?a=s&kategoria=&kategoria=277&brands%5B' . s($itempd['pd_id']) . '%5D=' . $itempd['pd_id'] . '">' . s($itempd['pd_nazwa']) . '</a></li>';
////                $out .= '<li><a href="' . $_GLOBAL['page_url'] . $_GLOBAL['lang'] . '/brands/kids/' . $itempd['pd_alias'] . '/">' . s($itempd['pd_nazwa']) . '</a></li>';
//                if (($lp++ % 7 == 0) && ($nr != ($lp - 1))) $out .= '</ul><ul><li><div class="nav-header">&nbsp;</div></li>';
//            }
            $out .= '<li><a href="' . $_GLOBAL['page_url'] . $_GLOBAL['lang'] . '/brands/kids/" style="text-transform:none">' . $L['{T_WIECEJ2}'] . '</a></li>';
            $out .= '</ul>';
            $out .= '</div>';
        }


        if (($T['id'] == 3) || ($T['id'] == 52)) //3 - brands
        {
            $out .= '<div class="sub">';

            $out .= '<ul>';
            $out .= '<li><div class="nav-header"><a href="' . $_GLOBAL['page_url'] . $_GLOBAL['lang'] . '/women/">Women</a></div></li>';

            $query = "select * from " . dn('sklep_producent') . " where pd_w='y' order by pd_nazwa;";

            $respd = $db->query($query);
            $nr    = $db->num_rows($query);
            $lp    = 1;
            while ($itempd = $db->fetch($respd)) {
                $out .= '<li><a href="' . $_GLOBAL['page_url'] . $_GLOBAL['lang'] . '/brands/women/' . $itempd['pd_alias'] . '/">' . s($itempd['pd_nazwa']) . '</a></li>';
                if (($lp++ % 8 == 0) && ($nr != ($lp - 1))) $out .= '</ul><ul><li><div class="nav-header">&nbsp;</div></li>';
            }
            $out .= '<li><a href="' . $_GLOBAL['page_url'] . $_GLOBAL['lang'] . '/brands/" style="text-transform:none">' . $L['{T_WIECEJ2}'] . '</a></li>';
            $out .= '</ul>';


            $out .= '<ul>';
            $out .= '<li><div class="nav-header"><a href="' . $_GLOBAL['page_url'] . $_GLOBAL['lang'] . '/men/">Men</a></div></li>';

            $query = "select * from " . dn('sklep_producent') . " where pd_m='y' order by pd_nazwa;";

            $respd = $db->query($query);
            $nr    = $db->num_rows($query);
            $lp    = 1;
            while ($itempd = $db->fetch($respd)) {
                $out .= '<li><a href="' . $_GLOBAL['page_url'] . $_GLOBAL['lang'] . '/brands/men/' . $itempd['pd_alias'] . '/">' . s($itempd['pd_nazwa']) . '</a></li>';
                if (($lp++ % 8 == 0) && ($nr != ($lp - 1))) $out .= '</ul><ul><li><div class="nav-header">&nbsp;</div></li>';
            }
            $out .= '<li><a href="' . $_GLOBAL['page_url'] . $_GLOBAL['lang'] . '/brands/" style="text-transform:none">' . $L['{T_WIECEJ2}'] . '</a></li>';
            $out .= '</ul>';


            $out .= '<ul>';
            $out .= '<li><div class="nav-header"><a href="#">Shop-In-Shop</a></div></li>';
            $out .= '<li><a href="http://gomez.pl/' . $_GLOBAL['lang'] . '/ralph_lauren/">Ralph Lauren</a></li>';
            $out .= '<li><a href="http://gomez.pl/' . $_GLOBAL['lang'] . '/guess/">Guess</a></li>';
            $out .= '<li><a href="http://gomez.pl/' . $_GLOBAL['lang'] . '/tommy_hilfiger/">Tommy Hilfiger</a></li>';
            $out .= '<li><a href="http://gomez.pl/' . $_GLOBAL['lang'] . '/pepe_jeans_london/">Pepe Jeans London</a></li>';
            $out .= '<li><a href="http://gomez.pl/' . $_GLOBAL['lang'] . '/armani/">Armani</a></li>';
            $out .= '<li><a href="http://gomez.pl/' . $_GLOBAL['lang'] . '/hugo_boss/">Hugo Boss</a></li>';
            $out .= '</ul>';


            $out .= '</div>';
        }


        if (($T['id'] == 4) || ($T['id'] == 53)) //4 - sale
        {
            $out .= '<div class="sub">';

            //women
            $query = "select * from " . dn('sklep_kategoria') . " where ka_id=" . (($_GLOBAL['langid'] != 1) ? '219' : '121') . ";";
            $r     = $db->query($query);
            $fitem = $db->fetch($r);

            $query = "select * from " . dn('sklep_kategoria') . " where ka_ka_id=" . (($_GLOBAL['langid'] != 1) ? '219' : '121') . " AND ka_id NOT IN (299, 118) order by ka_pozycja;";
            $r     = $db->query($query);
            $i     = 0;
            while ($item = $db->fetch($r)) {
                $out .= '<ul>';
                if ($i == 0) {
                    $out .= '<li><div class="nav-header"><a href="' . $_GLOBAL['page_url'] . '' . $_GLOBAL['lang'] . '/sale/' . $fitem['ka_alias'] . '/' . $item['ka_alias'] . '/">Women</a></div></li>';
                } else {
                    $out .= '<li><div class="nav-header"><a href="' . $_GLOBAL['page_url'] . '' . $_GLOBAL['lang'] . '/sale/' . $fitem['ka_alias'] . '/' . $item['ka_alias'] . '/">&nbsp;</a></div></li>';
                }

                $query = "select * from " . dn('sklep_kategoria') . " where ka_ka_id=" . $item['ka_id'] . " and ka_widoczna>0 and ka_sale=1 order by ka_pozycja;";
                $sr    = $db->query($query);
                $nr    = $db->num_rows($sr);
                $nr2   = 0;
                $lp    = 1;
                while ($sitem = $db->fetch($sr)) {
                    $out .= '<li><a href="' . $_GLOBAL['page_url'] . '' . $_GLOBAL['lang'] . '/sale/' . $fitem['ka_alias'] . '/' . $sitem['ka_alias'] . '/">' . $sitem['ka_nazwa'] . '</a></li>';
                    if (($lp++ % 7 == 0) && (($nr + $nr2) != ($lp - 1))) $out .= '</ul><ul><li><div class="nav-header">&nbsp;</div></li>';

                    $query = "select * from " . dn('sklep_kategoria') . " where ka_ka_id=" . $sitem['ka_id'] . " and ka_widoczna>0 and ka_sale=1 order by ka_pozycja;";
                    $sr2   = $db->query($query);
                    $nr2 += $db->num_rows($sr2);
                    //$lp=1;
                    while ($sitem2 = $db->fetch($sr2)) {
                        $out .= '<li><a href="' . $_GLOBAL['page_url'] . '' . $_GLOBAL['lang'] . '/sale/' . $fitem['ka_alias'] . '/' . $sitem['ka_alias'] . '/' . $sitem2['ka_alias'] . '/">' . $sitem2['ka_nazwa'] . '</a></li>';
                        if (($lp++ % 7 == 0) && (($nr2 + $nr) != ($lp - 1))) $out .= '</ul><ul><li><div class="nav-header">&nbsp;</div></li>';
                    }
                }
                if ($lp % 8 == 0) $out .= '</ul><ul><li><div class="nav-header">&nbsp;</div></li>';
                $out .= '<li class="more"><a href="' . $_GLOBAL['page_url'] . '' . $_GLOBAL['lang'] . '/sale/' . $fitem['ka_alias'] . '/' . $item['ka_alias'] . '/">' . $L['{T_WIECEJ2}'] . '</a></li>';
                $out .= '</ul>';
                $i++;
            }


            // men
            $query = "select * from " . dn('sklep_kategoria') . " where ka_id=" . (($_GLOBAL['langid'] != 1) ? '220' : '122') . ";";
            $r     = $db->query($query);
            $fitem = $db->fetch($r);

            $query = "select * from " . dn('sklep_kategoria') . " where ka_ka_id=" . (($_GLOBAL['langid'] != 1) ? '220' : '122') . " AND ka_id NOT IN (302, 271) order by ka_pozycja;";
            $r     = $db->query($query);
            $i     = 0;
            while ($item = $db->fetch($r)) {
                $out .= '<ul>';

                if ($i == 0) {
                    $out .= '<li><div class="nav-header"><a href="' . $_GLOBAL['page_url'] . '' . $_GLOBAL['lang'] . '/sale/' . $fitem['ka_alias'] . '/' . $item['ka_alias'] . '/">Men</a></div></li>';
                } else {
                    $out .= '<li><div class="nav-header"><a href="' . $_GLOBAL['page_url'] . '' . $_GLOBAL['lang'] . '/sale/' . $fitem['ka_alias'] . '/' . $item['ka_alias'] . '/">&nbsp;</a></div></li>';
                }
                
                $query = "select * from " . dn('sklep_kategoria') . " where ka_ka_id=" . $item['ka_id'] . " and ka_widoczna>0 and ka_sale=1 order by ka_pozycja;";
                $sr    = $db->query($query);
                $nr    = $db->num_rows($sr);
                $nr2   = 0;
                $lp    = 1;
                while ($sitem = $db->fetch($sr)) {
                    $out .= '<li><a href="' . $_GLOBAL['page_url'] . '' . $_GLOBAL['lang'] . '/sale/' . $fitem['ka_alias'] . '/' . $sitem['ka_alias'] . '/">' . $sitem['ka_nazwa'] . '</a></li>';
                    if (($lp++ % 7 == 0) && (($nr + $nr2) != ($lp - 1))) $out .= '</ul><ul><li><div class="nav-header">&nbsp;</div></li>';

                    $query = "select * from " . dn('sklep_kategoria') . " where ka_ka_id=" . $sitem['ka_id'] . " and ka_widoczna>0 and ka_sale=1 order by ka_pozycja;";
                    $sr2   = $db->query($query);
                    $nr2 += $db->num_rows($sr2);
                    while ($sitem2 = $db->fetch($sr2)) {
                        $out .= '<li>' . $lp . '<a href="' . $_GLOBAL['page_url'] . '' . $_GLOBAL['lang'] . '/sale/' . $fitem['ka_alias'] . '/' . $sitem['ka_alias'] . '/' . $sitem2['ka_alias'] . '/">' . $sitem2['ka_nazwa'] . '</a></li>';
                        if (($lp++ % 7 == 0) && (($nr2 + $nr) != ($lp - 1))) $out .= '</ul><ul><li><div class="nav-header">&nbsp;</div></li>';
                    }
                }
                if ($lp % 8 == 0) $out .= '</ul><ul><li><div class="nav-header">&nbsp;</div></li>';
                $out .= '<li class="more"><a href="' . $_GLOBAL['page_url'] . '' . $_GLOBAL['lang'] . '/sale/' . $fitem['ka_alias'] . '/' . $item['ka_alias'] . '/">' . $L['{T_WIECEJ2}'] . '</a></li>';
                $out .= '</ul>';
                $i++;
            }


            // brands        
            $out .= '<ul>';
            $out .= '<li><div class="nav-header"><a href="' . $_GLOBAL['page_url'] . $_GLOBAL['lang'] . '/sale/brands/">BRANDS</a></div></li>';

            $query = "select * from " . dn('sklep_producent') . " where pd_s='y' order by pd_nazwa;";

            $respd = $db->query($query);
            $nr    = $db->num_rows($query);
            $lp    = 1;
            while ($itempd = $db->fetch($respd)) {
                $out .= '<li><a href="' . $_GLOBAL['page_url'] . $_GLOBAL['lang'] . '/sale/brands/' . $itempd['pd_alias'] . '/">' . s($itempd['pd_nazwa']) . '</a></li>';
                if (($lp++ % 7 == 0) && ($nr != ($lp - 1))) $out .= '</ul><ul><li><div class="nav-header">&nbsp;</div></li>';
            }
            $out .= '<li><a href="' . $_GLOBAL['page_url'] . $_GLOBAL['lang'] . '/sale/brands/" style="text-transform:none">' . $L['{T_WIECEJ2}'] . '</a></li>';
            $out .= '</ul>';

            $out .= '</div>';
        }

        $out .= '</li>';
    }

    return $out;
}

function get_menu_bottom_title($id, $langid) {
    global $db, $_GLOBAL;
    $out   = '';
    $query = "select * from " . dn('menu') . " where type=" . (int)$id . " and langid='" . $langid . "' and parent is NULL;";
    $res   = $db->query($query);

    while ($T = $db->fetch($res)) {
        if (!empty($T['link'])) $out .= '<a href="' . $_GLOBAL['page_url'] . $_GLOBAL['lang'] . '/' . $T['link'] . '" target="' . $T['target'] . '">' . $T['name'] . '</a>';
        else $out .= '' . $T['name'] . '';
    }

    return $out;
}

;

function get_menu_bottom_list($type, $langid) {
    global $db, $_GLOBAL;
    $out   = '';
    $query = "select * from " . dn('menu') . " where type=" . (int)$type . " and langid=" . $langid . " and parent is not NULL;";
    $res   = $db->query($query);

    while ($T = $db->fetch($res)) {
        $link = '';
        if (substr($T['link'], 0, 4) == 'http') $link = $T['link'];
        else $link = $_GLOBAL['page_url'] . $_GLOBAL['lang'] . '/' . $T['link'];
        if (!empty($T['link'])) $out .= '<li><a href="' . $link . '" target="' . $T['target'] . '">' . str_replace('&', '&amp;', $T['name']) . '</a></li>' . "\n";
        else $out .= '<li>' . str_replace('&', '&amp;', $T['name']) . '</li>' . "\n";
    }

    return $out;
}

;

function get_maindescription() {
    global $db, $_GLOBAL;
    $out   = '';
    $query = "select * from " . dn('cms') . " where st_id=83;";
    $res   = $db->query($query);
    $dane  = $db->fetch($res);
    if (!empty($dane['st_tresc'])) {
        $out = '<div id="footer-container" class="container">
                <div id="footer-description">
                    ' . (($_GLOBAL['lang'] == 'pl') ? $dane['st_tresc'] : $dane['st_tresc_en']) . '
                </div>
            </div>';
    }

    return $out;
}


?>