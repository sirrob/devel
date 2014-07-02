<?php if (!defined('SMDESIGN')) {
    die("Hacking attempt");
}

/**
 * @author Michal Bzowy
 * @since  2006-03-24
 */

/*
Do poprawnego działania konieczne jest utworzenie widoku:
create view mm_uzytkownik_v as
select concat('u',uz_id) uid, uz_id id, uz_login login, uz_status status, uz_haslo haslo, uz_nazwa nazwa, uz_email email from mm_uzytkownik
union
select concat('k',ko.ko_id) uid, ko.ko_id id, ko_email login, ko_status status, ko_haslo haslo, ko_nazwa nazwa, ko_email email from mm_kontrahent ko left join mm_sklep_kontrahent sk on ko.ko_id=sk.ko_id

create view mm_uzytkownik_v as
select concat('u',uz_id) uid, uz_id id, uz_login login, uz_status status, uz_haslo haslo, uz_nazwa nazwa, uz_email email, uz_id gomezclub, uz_id punkt, uz_id club,uz_id club_numer,uz_id club_poziom, uz_id club_data,uz_id club_suma from mm_uzytkownik
union
select concat('k',ko.ko_id) uid, ko.ko_id id, ko_email login, ko_status status, ko_haslo haslo, ko_nazwa nazwa, ko_email email, sk.ko_gomezclub, sk.ko_punkt,ko_CLUB club,ko_CLUB_NUMER club_numer,ko_CLUB_POZIOM club_poziom, ko_CLUB_DATA club_data,ko_CLUB_SUMA club_suma from mm_kontrahent ko left join mm_sklep_kontrahent sk on ko.ko_id=sk.ko_id

create view mm_uzytkownik_v as
select concat('u',uz_id) uid, uz_id id, uz_login login, uz_status status, uz_haslo haslo, uz_nazwa nazwa, uz_email email, uz_id gomezclub, uz_id punkt, uz_id club,uz_id club_numer,uz_id club_poziom, uz_id club_data,uz_id club_suma from mm_uzytkownik
union
select concat('k',ko.ko_id) uid, ko.ko_id id, ko_email login, ko_status status, ko_haslo haslo, ko_nazwa nazwa, ko_email email, sk.ko_gomezclub, sk.ko_punkt,ko_CLUB club,ko_CLUB_NUMER club_numer,ko_CLUB_POZIOM club_poziom, ko_CLUB_DATA club_data,ko_CLUB_SUMA club_suma from mm_kontrahent ko left join mm_sklep_kontrahent sk on ko.ko_id=sk.ko_id

*/
class uzytkownik extends mb {
    var $UZ = array();
    var $id;
    var $DOMAINS = array("");

    function uzytkownik() {
        global $_GET;
        global $_POST;
        $this->reg_login();
        $this->mode = "prn_form";
        if ($this->is_login()) {
            if (isset($_GET["u"]) and $_GET["u"] != "" and $this->test_right("admin")) {
                $this->id = $_GET["u"];
            } elseif (isset($_POST["u"]) and $_POST["u"] != "" and $this->test_right("admin")) {
                $this->id = $_POST["u"];
            } else {
                $this->id = $this->get_id();
            }
        }

        if (isset($_POST["a"]) and $_POST["a"] == "l") { // Login
            if ($this->login(trim($_POST["lo_login"]), trim($_POST["lo_haslo"])) and isset($_POST["url"])) redirect($_POST["url"]);
        } elseif (isset($_GET["a"]) and $_GET["a"] == "lo") { // Logout
            $this->logout();
        }

        //print_r($this->WYNIK);
    }

    function aktywuj($str) {
        global $db;
        $A = explode(",", $str);
        $T = $db->onerow("select uid,haslo from " . dn("uzytkownik_v") . " where uid='k" . (int)$A[0] . "' and mid(haslo,1,8)='" . a2($A[1]) . "' and status='2'");
        if (is_array($T)) {
            $db->query("update " . dn("sklep_kontrahent") . " set ko_status='1' where ko_id=" . (int)$A[0]);
            $this->login_zapisz($T[0], $T[1]);

            return true;
        } else {
            return false;
        }
    }

    function get_dane($IDS, $POLA) {
        global $db;
        $nr = $db->query("select uz_id," . join(",", $POLA) . " from " . dn("uzytkownik") . " where uz_id in ('" . join("','", $IDS) . "')");
        while ($T = $db->fetch($nr)) {
            $RET[$T[0]] = $T;
        }

        return $RET;
    }

################################################################

    function gen_prawa() {
        global $db;
        if (!$this->is_login() or $this->is_klient()) return;
        $q  = "select * from " . dn("uprawnienie") . " where up_uz_id='" . uzytkownik::get_id() . "'";
        $nr = $db->query($q);
        while ($T = $db->fetch($nr)) {
            $this->PRAWA[$T[1]][$T[2]] = true;
        }
        //debug($this->PRAWA);
    }

    function test_right($grupa_1) {
        if (!$this->is_login() or $this->is_klient()) return 0;
        if ($grupa_1 == "provider" and isset($this->PRAWA[0]["admin"]) and $this->id == 1) return 1;
        if ($grupa_1 != "provider" and isset($this->PRAWA[0]["admin"])) return 1;
        if ($grupa_1 == "admin" and isset($this->PRAWA[0]["admin"])) return 1;
        if ($grupa_1 == "islogin" and $this->is_login()) return 1;
        //elseif (isset($this->PRAWA[$grupa_1][$grupa_2])) return $this->PRAWA[$grupa_1][$grupa_2];

        return 0;
    }

    function is_login() {
        if (isset($this->UZ["uid"])) return true;

        return false;
    }

    function is_klient() {
        if ($this->is_login() and substr($this->UZ["uid"], 0, 1) == "k") return true;

        return false;
    }

    function is_gomezclub() {
        if ((isset($this->UZ['gomezclub'])) && (substr($this->UZ['gomezclub'], 0, 1) == 'y')) return true;

        return false;
    }

    function reg_login() {
        global $db, $_GLOBAL;
        if (!isset($_COOKIE[$_GLOBAL["keylogin"]])) return;
        $T = explode(",", $_COOKIE[$_GLOBAL["keylogin"]]);
        if (count($T) == 2) {
            $q        = "select * from " . dn("uzytkownik_v") . " where uid='" . $T[0] . "' and haslo='" . $T[1] . "'";
//            file_put_contents('users.txt', 'uid: '.$T[0] . ', password: '. $T[1] . ', ip: '.$_SERVER['REMOTE_ADDR']."\n", FILE_APPEND);
            $this->UZ = $db->onerow($q);
            $this->gen_prawa();
        }
    }

    function zmien_haslo($pass) {
        global $_GLOBAL;
        setcookie($_GLOBAL['keylogin'], "k" . $this->get_id() . "," . $pass, 0, "/");
    }

    function login_zapisz($id, $pass, $pamietaj = "0") {
        global $_GLOBAL, $db;
        if (substr($id, 0, 1) == "k") { //Kontrahent
            $q = "update " . dn("sklep_kontrahent") . " set ko_data_wizyta=ko_data_login, ko_data_login='" . time() . "',ko_login_ilosc=ko_login_ilosc+1 ";
            $q .= "where ko_id='" . substr($id, 1) . "'";
            $db->query($q);
            //To jest po to, zeby nie przesylac do MK wszystkich klientow od razu, tylko jak sie beda logowali
            $q = "update " . dn("kontrahent") . " set ko_data_update=" . time() . ", ko_update='1' ";
            $q .= "where ko_id='" . substr($id, 1) . "' and ko_data_update=0";
            $db->query($q);
        } else {
            $q = "update " . dn("uzytkownik") . " set uz_data_wizyta=uz_data_login, uz_data_login='" . time() . "',uz_ip='" . $_SERVER['REMOTE_ADDR'] . "' ";
            $q .= "where uz_id='" . $id . "'";
        }
        //    $db->query($q);
        setcookie($_GLOBAL['keylogin'], 0, (time() - 3600));
        unset($_COOKIE[$_GLOBAL['keylogin']]);
        setcookie($_GLOBAL['keylogin'], $id . "," . $pass, ($pamietaj ? (time() + 90 * 86400) : 0), "/");

        $this->UZ = $db->onerow("select * from " . dn("uzytkownik_v") . " where uid='" . $id . "'");

        if (substr($id, 0, 1) == "k") {
            $query = "select * from " . dn('kontrahent') . " where ko_id=" . substr($id, 1) . ";";
            $res   = $db->query($query);
            $dane  = $db->fetch($res);

            $query = "update " . dn('kontrahent') . " set ko_CLUB_POZIOM=" . $this->getPercent() . " where ko_id=" . substr($id, 1) . ";";
            $db->query($query);
        }
        $this->gen_prawa();
        //sync_pobierz($this->get_id());

        if ($this->is_klient()) {
            if ($_GLOBAL['langid'] == 2) {
                onload("alert('Hugo Boss Black, Polo Ralph Lauren brands are now available only for registered customers. Thank you for logging.')");
            } else {
                onload("alert('Marka Boss Hugo Boss (linia Black) dostępna jest teraz wyłącznie dla zalogowanych klientów. Dziękujemy za logowanie.')");
            }
        }
    }

    function login($login, $haslo) {
        global $_GLOBAL, $db;
        $err = 0;
        if ($login != "" and $haslo != "") {
            $q = "select uid from " . dn("uzytkownik_v") . " where ";
            $q .= "login='" . a2($login) . "' and haslo='" . pass2code($haslo) . "' and status='1'";
            $TAB = $db->onerow($q);
            if (is_array($TAB)) {
                $id   = $TAB[0];
                $pass = pass2code($haslo);
                $this->login_zapisz($id, $pass);
                setcookie("re_login", 0, time() - 3600);

                return true;
            } else {
                $err = 1;
            }
        } else {
            $err = 1;
        }

        if ($err) {
            if (isset($_COOKIE["re_login"]) and $_COOKIE["re_login"] > 2) {
                onload("m_haslo();");
            } else {
                onload("alert('{T_ERR_LOGIN}');");
            }
        }


        return false;
    }

    function get_id() {
        if (isset($this->UZ["uid"]) and $this->UZ["id"] > 0) return $this->UZ["id"];

        return 0;
    }

    function get_gcsuma() {
        if (isset($this->UZ["club_suma"])) return $this->UZ["id"];
        else return 0;
    }

    function get_login() {
        if ($this->UZ["uid"]) return $this->UZ["login"];

        return false;
    }

    function get_email() {
        if ($this->UZ["uid"]) return $this->UZ["email"];

        return false;
    }

    function get_licencja() {
        if ($this->UZ["uz_id"]) return $this->UZ["so_licencja"];

        return false;
    }

    function get_program() {
        if ($this->UZ["uz_id"]) return $this->UZ["so_id"];

        return false;
    }

    function get_nazwisko() {
        if (isset($this->UZ["uid"])) return $this->UZ["nazwa"];

        return 0;
    }

    function logout() {
        global $_GLOBAL;
        $this->id = 0;
        setcookie($_GLOBAL["keylogin"], 0, time() - 1000, "/");
        unset($_COOKIE[$_GLOBAL["keylogin"]]);
        unset($this->UZ);
        unset($_COOKIE);
        unset($_SESSION[SID]['discount_code']);
        session_regenerate_id();
        redirect($_GLOBAL['page_url'] . $_GLOBAL['lang']); // . "index.php"
    }

    function getPercent() {
		global $db;
        $percent = 0;
        $id = $this->get_id();
        $query = "SELECT discount FROM gomezclub_card WHERE customer_id={$id}";
        $discRow = $db->onerow($query);
        $discount = $discRow[0];
        debug($discount);

        /*if($this->UZ["punkt"]>=10000) $percent = 15;
        else
        if($this->UZ["punkt"]>=4000)  $percent = 10;
        else
        if($this->UZ["punkt"]>=1000)  $percent = 5;
        else
        if($this->UZ["punkt"]>=500)   $percent = 3;*/
        //punkt 	club 	club_numer 	club_poziom 	club_data 	club_suma
        /*if($this->UZ["club_suma"]>=10000) $percent = 15;
            else
            if($this->UZ["club_suma"]>=4000)  $percent = 10;
            else
            if($this->UZ["club_suma"]>=1000)  $percent = 5;
            else
            if($this->UZ["club_suma"]>=500)   $percent = 3; */
//print_r($this->UZ);         
        if ($this->UZ["club_suma"] >= 48000) $percent = 20;
        else
            if ($this->UZ["club_suma"] >= 16000) $percent = 15;
            else
                if ($this->UZ["club_suma"] >= 4000) $percent = 10;
                else
                    if ($this->UZ["club_suma"] >= 1000) $percent = 5;
                    else $percent = 0;


        if ($discount > $percent) return $discount;

        else return $percent;

      /*  if ($this->UZ['club_poziom'] > $percent) return $this->UZ['club_poziom'];
        else return $percent;*/
    }

    function getPercent2($pkt =0) {
        $percent = 0;
      //  $pkt     = 0;

		global $db;
        $percent = 0;
        $id = $this->get_id();
        $query = "SELECT discount FROM gomezclub_card WHERE customer_id={$id}";
        $discRow = $db->onerow($query);
        $discount = $discRow[0];
        debug($discount);
        if (($this->UZ["club_suma"] + $pkt) >= 48000) $percent = 20;
        else
            if (($this->UZ["club_suma"] + $pkt) >= 16000) $percent = 15;
            else
                if (($this->UZ["club_suma"] + $pkt) >= 4000) $percent = 10;
                else
                    if (($this->UZ["club_suma"] + $pkt) >= 1000) $percent = 5;
                    else $percent = 0;

         if ($discount > $percent) return $discount;

        else return $percent;

     /*   if ($this->UZ['club_poziom'] > $percent) return $this->UZ['club_poziom'];
        else return $percent; */
    }

    function getLJPercent() {

        return $this->getPercent2(0); // jak aktywowac to wywalic te linie!
        $percent = 0;

        // czy uzytkownik uprzywilejowany (moze miec wiece w LJ niz wynika to z regul)
        if (isset($this->UZ['lj_type']) AND ($this->UZ['lj_type'] == 1)) {
            return $this->getPercent2(0);
        } else {
            if ($this->UZ['club_suma'] >= 10000) {
                $percent = 10;
            } elseif ($this->UZ['club_suma'] >= 2000) {
                $percent = 5;
            }

            return $percent;
        }
    }

    function setCZ() {
        global $db;

        $query = "select * from " . dn('kontrahent') . " where ko_id=" . $this->get_id() . ";";
        $dane  = $db->onerow($query);


        $query = "update " . dn('sklep_kontrahent') . " set ko_punkt=" . $dane['ko_CLUB_SUMA'] . " where ko_id=" . $this->get_id() . ";";
        $db->query($query);

        $query = "update " . dn('kontrahent') . " set ko_punkt=ko_CLUB_SUMA where ko_id=" . $this->get_id() . ";";
        $db->query($query);

    }

    /**
     * Funkcja zwraca mini boks użytkownika
     *
     * @return string Formularz logowania / Menu użytkownika
     */
    function get_user_mini() {
        global $_GLOBAL, $L;
        //print_r($this->UZ);
        if ($this->is_klient()) {
            $DD[]        = "IT_FORM";
            $A["{USER}"] = s($this->UZ["nazwa"]);
            //debug(substr($this->UZ["gomezclub"],0,1));
            if (substr($this->UZ["gomezclub"], 0, 1) == 'y') { //$this->UZ['punkt']
                $A["{GOMEZCLUB}"] = '<span class="GCinfor">' . $L['{T_TWOJ_RABAT_W_GOMEZ_CLUB}'] . ': ' . $this->getPercent() . '% | ' . (int)$this->UZ['club_suma'] . '</span> | ';
            } else {
                $A["{GOMEZCLUB}"] = '';
            }
        } else {
            $DD[] = "IT_MENU";
        }
        $A['{URL}']  = $_GLOBAL['page_url'];
        $A['{LANG}'] = $_GLOBAL['lang'];

        return get_template("user_mini", $A, $DD);
    }

    /**
     * @param integer $id ID kontrahenta
     */
    function mail_rejestracja($id) {
        global $db, $_GLOBAL, $L;

        $T = $db->onerow("select id, mid(haslo,1,8),email, nazwa, status from " . dn("uzytkownik_v") . " where uid='k" . $id . "'");

        if ($T["status"]) $B["{URL}"] = $_GLOBAL["page_url"] . "" . $_GLOBAL['lang'] . "/uzytkownik.htm?aktywuj=" . $id . "," . $T[1];

        $B["{USER}"]  = s($T["nazwa"]);
        $TP           = get_komunikat(array("T_EMAIL", "T_EMAIL_REGISTER_" . strtoupper($_GLOBAL['lang'])));
        $A["{TRESC}"] = get_template($TP["T_EMAIL_REGISTER_"] . strtoupper($_GLOBAL['lang']), $B, '', 0);

        $M["m_subject"] = $L['{T_REGISTRATION_EMAIL_SUBJECT}'];
        $M["m_to"]      = $T["email"];
        $M["m_message"] = get_template($TP["T_EMAIL"], $A, '', 0);
        my_mail_smtp($M);
    }

    /**
     * Wysłanie maila z nowym hasłem dostępu
     *
     * @param string $email Adres odbiorcy
     * @param string $haslo Nowe hasło nadane przez system
     */
    function mail_haslo($email, $haslo) {
        global $_GLOBAL, $L;

        $B["{URL}"]   = $_GLOBAL["page_url"];
        $B["{LOGIN}"] = s($email);
        $B["{HASLO}"] = s($haslo);

        $TP           = get_komunikat(array("T_EMAIL", "T_EMAIL_PASSWORD_" . strtoupper($_GLOBAL['lang'])));
        $A["{TRESC}"] = get_template($TP["T_EMAIL_PASSWORD_" . strtoupper($_GLOBAL['lang'])], $B, '', 0);

        $M["m_subject"] = $L['{T_NEW_PASSWORD_EMAIL_SUBJECT}'];

        $M["m_to"]      = $email;
        $M["m_message"] = get_template($TP["T_EMAIL"], $A, '', 0);
        my_mail_smtp($M);
    }


}

$Cuzytkownik = new uzytkownik();
//$Cuzytkownik->mail_rejestracja(22);
?>