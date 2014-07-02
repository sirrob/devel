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

/**
 * Początek przetwarzania
 */
 
require_once("incphp/mb_pliki.php");
require_once("incphp/mb_common_begin.php");
require_once("common/incphp/mb_func.php");

include_once("incphp/mb_pliki.php");
include_once("incphp/mb_common_begin.php");
include_once("common/incphp/mb_func.php");

test_zamowienie_dodaj(83946, 4);


function test_zamowienie_dodaj($id, $magazyn = 0) {
    global $db, $_GLOBAL;

    $d = $db->onerow("select * from " . dn("sklep_zamowienie") . " where za_id=" . $id);
    echo substr(trim($d['za_do_kod']), 0, 1);

    // if ($db->affected_rows()) {
//        while ($T = $db->fetch($nr)) $DID[] = $T[0];
//        $db->query("delete from " . dn("dokument_dane") . " where do_id in (" . join(",", $DID) . ")");
//        $db->query("delete from " . dn("dokument_towar") . " where dt_do_id in (" . join(",", $DID) . ")");
    // }
//     $T   = $db->onerow("select ds_tresc from " . dn("dokument_szablon") . " where ds_rodzaj='ZA'");
//     $tpl = $T[0];

//     $ZA = $db->onerow("select za_wartosc_netto, za_wartosc_brutto from " . dn("sklep_zamowienie") . " where za_id=" . $id);

//     // Wzorzec zapytanie, które dodaje zamówienia do bazy
//     $q = "insert into " . dn("dokument_dane") . " set ";
//     $q .= "do_numer='{AUTO}.{MA_ID}." . $id . "',do_rodzaj='ZA',do_status='0',";
//     $q .= "do_data=" . time() . ",do_data_in=" . time() . ",do_update='1',do_data_update=" . time() . ",";
//     $q .= "do_uz_id=0,do_uz_nazwa='System',do_ma_id={MA_ID},do_ko_id=1,do_ko_nip='" . $id . "',";
//     $q .= "do_wartosc_netto={WARTOSC_NETTO},do_wartosc_brutto={WARTOSC_BRUTTO},";
//     $q .= "do_szablon='{SZABLON}'";
//     $qz = $q;

//     $q = "select zp.*,pr_pkwiu ___pr_pkwiu,gt_nazwa ___gt_nazwa from " . dn("sklep_zamowienie_pozycja") . " zp ";
//     $q .= "left join " . dn("produkt") . " on zp_pr_id=pr_id left join " . dn("produkt_grupa_towarowa") . " on pr_gt_id=gt_id ";
//     $q .= "where zp_za_id=" . $id . " order by zp_id";

//     echo $q . "<br />";

//     $nr = $db->query($q);
//     $KOSZ = array();
//     $IDS = array();
//     while ($T = $db->fetch($nr, MYSQL_ASSOC)) {
//         $KOSZ[$T["zp_pr_id"]][$T["zp_at_id"]] = $T;
//         $IDS[]                                = "(" . $T["zp_pr_id"] . "," . $T["zp_at_id"] . ")";
//     }

//     // ustalanie kolejnosci wyciagania produktow z magazynow, najpierw z glownego (nowego - wylacznie starego [1])
//     $query = "SELECT mm_magazyn_stan.* FROM mm_magazyn_stan LEFT JOIN mm_magazyn ON mm_magazyn.ma_id = mm_magazyn_stan.ms_ma_id WHERE mm_magazyn_stan.ms_ma_id NOT IN (" . $_GLOBAL["omitted_stores"] . ") AND (mm_magazyn_stan.ms_pr_id, mm_magazyn_stan.ms_at_id) IN (" . join(",", $IDS) . ") ORDER BY mm_magazyn.ma_order DESC";
//     $nr = $db->query($query);
//     // file_put_contents('!queries.txt', $query, FILE_APPEND);

//     while ($T = $db->fetch($nr)) {
//         $STAN[$T["ms_ma_id"]][$T["ms_pr_id"]][$T["ms_at_id"]] = $T["ms_ilosc"];
//     }

//     // Wzorzec zapytania, które wprowadzi pozycje zamówienia
//     $q = "insert into " . dn("dokument_towar") . " set dt_do_id={DO_ID}, dt_ma_id={MA_ID}, dt_pr_id={PR_ID}, dt_at_id='{AT_ID}',";
//     $q .= "dt_pr_nazwa='{PR_NAZWA}', dt_pr_pkwiu='{PR_PKWIU}', dt_at_nazwa='{AT_NAZWA}', dt_kod_kreskowy='{PR_KOD_KRESKOWY}', ";
//     $q .= "dt_ilosc={ILE}, dt_jm='{PR_JM}', dt_cena_netto={CENA_NETTO}, dt_cena_brutto={CENA_BRUTTO}, ";
//     $q .= "dt_cena_org_netto={CENA_NETTO}, dt_cena_org_brutto={CENA_BRUTTO}, dt_vat_stawka='{PR_VAT_STAWKA}', ";
//     $q .= "dt_gt_nazwa='{GT_NAZWA}', dt_kolor='{PR_KOLOR}', dt_indeks='{PR_INDEKS}'";

//     // Podziel zamówienie na magazyny, które mają dany towar
//     if ($magazyn == 0) {
//         foreach ($STAN as $ma => $S) {
//             if (in_array($ma, $_GLOBAL["omitted_stores"])) continue;

//             foreach ($S as $pr => $I) {
//                 $PR[$pr] = $pr;
//                 foreach ($I as $at => $ile) {
//                     if (!isset($KOSZ[$pr][$at]) or $KOSZ[$pr][$at]["zp_ilosc"] <= 0 or $ile <= 0) continue;

//                     // Jeżeli na stanie jest więcej niż potrzebujemy to zajmujemy tyle ile potrzebujemy
//                     // w przeciwnym wypadku zajmujemy tyle ile jest
//                     if ($ile >= $KOSZ[$pr][$at]["zp_ilosc"]) {
//                         $zajmij                     = $KOSZ[$pr][$at]["zp_ilosc"];
//                         $KOSZ[$pr][$at]["zp_ilosc"] = 0;
//                     } else {
//                         $zajmij                     = $ile;
//                         $KOSZ[$pr][$at]["zp_ilosc"] = $KOSZ[$pr][$at]["zp_ilosc"] - $ile;
//                     }

//                     $STAN[$ma][$pr][$at]       = $STAN[$ma][$pr][$at] - $zajmij;
//                     $ZMIEN_STAN[$ma][$pr][$at] = true;
//                     $DODAJ[$ma][$pr][$at]      = $zajmij;
//                 }
//             }
//         }


//         foreach ($DODAJ as $ma => $S) {
//             $A              = array();
//             $A["{SZABLON}"] = $tpl;
//             $A["{MA_ID}"]   = $ma;
//             $suma_netto     = $suma_brutto = 0;
//             foreach ($S as $pr => $I) {
//                 foreach ($I as $at => $ile) {
//                     $suma_netto += $ile * $KOSZ[$pr][$at]["zp_cena_netto"];
//                     $suma_brutto += $ile * $KOSZ[$pr][$at]["zp_cena_brutto"];
//                 }
//             }

//             $A["{WARTOSC_NETTO}"]  = $suma_netto;
//             $A["{WARTOSC_BRUTTO}"] = $suma_brutto;

//             //debug(strtr($qz,$A));
// //            $db->query(strtr($qz, $A));
//             $A["{DO_ID}"] = 'XXX';//$db->insert_id();

//             foreach ($S as $pr => $I) {
//                 foreach ($I as $at => $ile) {
//                     $A["{ILE}"] = $ile;

//                     foreach ($KOSZ[$pr][$at] as $key => $v) {
//                         $A["{" . strtoupper(substr($key, 3)) . "}"] = $v;
//                     }
//                     //debug(strtr($q,$A));
//                     echo strtr($q, $A)."<br>";
// //                    $db->query(strtr($q, $A));
//                     //debug("update ".dn("magazyn_stan")." set ms_ilosc=".$STAN[$ma][$pr][$at]."
//                     //             where ms_ma_id=".$ma." and ms_pr_id=".$pr." and ms_at_id=".$at);
// //                    $db->query("update " . dn("magazyn_stan") . " set ms_ilosc=" . $STAN[$ma][$pr][$at] . "
// //                                    where ms_ma_id=" . $ma . " and ms_pr_id=" . $pr . " and ms_at_id=" . $at);
//                 }
//             }
//         }

//         $nr  = $db->query("select distinct ms_pr_id from mm_magazyn_stan where ms_ilosc>0
//                               and ms_ma_id NOT IN (" . $_GLOBAL["omitted_stores"] . ")
//                               and ms_pr_id in (" . join(",", $PR) . ")
//                               order by ms_pr_id");
//         $IDS = array();
//         while ($T = $db->fetch($nr)) {
//             $IDS[$T[0]] = $T[0];
//         }

//         if (count($IDS)) {
// //            $db->query("update `mm_sklep_produkt` set pr_stan=0 where pr_id in(" . join(",", $PR) . ")");
// //            $db->query("update `mm_sklep_produkt` set pr_stan=1 where pr_id in(" . join(",", $IDS) . ")");
//         }

//         /**
//          * Prześlij kompletne zamówienie do wybranego magazynu
//          */
//     } else {
//         foreach ($KOSZ as $pr => $K) {
//             foreach ($K as $at => $T) {
//                 $DODAJ[$pr][$at]     = $T["zp_ilosc"];
//                 $STAN_NOWY[$pr][$at] = $STAN[$magazyn][$pr][$at] - $T["zp_ilosc"];
//             }
//         }

//         $A                     = array();
//         $A["{WARTOSC_NETTO}"]  = $ZA[0];
//         $A["{WARTOSC_BRUTTO}"] = $ZA[1];
//         $A["{SZABLON}"]        = $tpl;
//         $A["{MA_ID}"]          = $magazyn;

//         echo strtr($qz,$A).'<br><hr>';
// //        $db->query(strtr($qz, $A));

//         $A["{DO_ID}"] = 'XXX';//$db->insert_id();

//         foreach ($DODAJ as $pr => $I) {
//             foreach ($I as $at => $ile) {
//                 $A["{ILE}"] = $ile;

//                 foreach ($KOSZ[$pr][$at] as $key => $v) {
//                     $A["{" . strtoupper(substr($key, 3)) . "}"] = $v;
//                 }

//                 echo strtr($q,$A).'<br>';
// //                $db->query(strtr($q, $A));

//                 if (isset($STAN[$magazyn][$pr][$at])) {
//                     $qm = "update " . dn("magazyn_stan") . " set ms_ilosc=" . $STAN_NOWY[$pr][$at] . " where ms_ma_id=" . $magazyn . " and ms_pr_id=" . $pr . " and ms_at_id=" . $at;
//                     echo $qm . '<br>';
// //                    $db->query($qm);
//                 } else {
//                     $qm = "insert into " . dn("magazyn_stan") . " set ms_ilosc=" . $STAN_NOWY[$pr][$at] . ", ms_ma_id=" . $magazyn . ", ms_pr_id=" . $pr . ", ms_at_id=" . $at;
//                     echo $qm . '<br>';
// //                    $db->query($qm);
//                 }
//             }
//         }
//     }
}

// Zakończenie przetwarzania i wyświetlenie wyniku
include_once("incphp/mb_common_end.php");

?>
