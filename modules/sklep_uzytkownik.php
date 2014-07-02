<?php
/**
 * Klasa obsługi zakupu
 * 
 * 
 * @author Michał Bzowy
 * @copyright Copyright (c) 2008, Michał Bzowy
 * @since 2008-08-04 12:36:48
 * @link www.imt-host.pl
 */

/**
 * Autoryzacja
 */
if ( !defined('SMDESIGN') ) {die("Hacking attempt");}

/**
 * Klasa obsługi zakupu
 *
 */
class sklep_uzytkownik extends mb
{
    /**
     * Lista adresów powrotnych, gdzie może być przekierowany klient po zalgowaniu
     *
     * @var array
     */
    var $BACK = array("zakup"=>"zakup.htm","zamowienie"=>"zamowienie.htm", 'uzytkownik'=>'uzytkownik.htm',''=>'uzytkownik.htm');
    
    /**
     * Konstruktor klasy
     * 
     * @global array $_GLOBAL
     * @global object $Cuzytkownik
     * @global object $db
     */
    
    var $emsg = array();
    
    function sklep_uzytkownik()
    {
        global $_GLOBAL,$Cuzytkownik, $db;
        
        if(isset($_POST["a"]) and $_POST["a"]=="zachowaj") {
            $this->WYNIK["fo"] = get_post("notag");
            
            //$this->emsg = $this->test_arg();
            if($this->test_arg()) {
                $this->zapisz();
                $_POST["lo_login"] = $_POST['ko_email'];
                $_POST["lo_haslo"] = $_POST['ko_haslo'];
                if(!$Cuzytkownik->is_klient()) $Cuzytkownik->login($_POST['ko_email'],$_POST['ko_haslo']);
            } else {
                $this->mode = "prn_login";
            }
            
        } elseif(!$Cuzytkownik->is_klient() and isset($_POST["a"]) and $_POST["a"]=="haslo")
        {
            if($this->wyslij_haslo(trim($_POST["email"]))) {
                $this->mode = "prn_haslo_ok";
            } else {
                $this->mode = "prn_haslo";
            }
        } elseif(!$Cuzytkownik->is_klient() and isset($_GET["a"]) and $_GET["a"]=="haslo") {
            $this->mode = "prn_haslo";
        //} elseif($Cuzytkownik->is_klient() and isset($_POST["a"]) and $_POST["a"]=="l") {
        } elseif(isset($_POST["a"]) and $_POST["a"]=="l" and $Cuzytkownik->is_login()) {
            $this->mode = "prn_wejscie";
            //$this->WYNIK['fo']['url'] = 'uzytkownik.htm';
            
            
            
        } elseif(!$Cuzytkownik->is_login() and isset($_GET["aktywuj"])) {
            $this->mode = "prn_login";
            if($Cuzytkownik->aktywuj($_GET["aktywuj"])) {
                $this->mode = "prn_aktywacja";
            }
        } else 
        if((isset($_GET['url'])) && ($_GET['url']=='selection'))
        {
            $this->mode = "prn_login";
            $this->mode = 'prn_selection';
            
            //miejsce gdzie przechodzi z koszyka do zakupów ale nie jest się zalogowanym
            if(isset($_GET["url"]) and (!empty($_GET['url'])) and isset($this->BACK[$_GET["url"]])) $this->WYNIK["fo"]["url"] = $_GLOBAL['page_url'] . $_GLOBAL['lang'] . '/' . $_GET["url"];
            elseif(isset($_POST["url"]) and (!empty($_POST['url'])) and isset($this->BACK[$_POST["url"]])) $this->WYNIK["fo"]["url"] = $_GLOBAL['page_url'] . $_GLOBAL['lang'] . '/' . $_POST["url"];
            
            //$this->WYNIK["fo"]["url"] = 'zakup.htm';
            
        } else
        {
            if(isset($_GET['url'])) $this->mode = "prn_login";
            else if($Cuzytkownik->is_klient())$this->mode = "prn_wejscie";
            else  $this->mode = "prn_login";
            //$this->mode = 'prn_choose';
            if((isset($_GET['a'])) && ($_GET['a']=='dane')) 
            {
                $this->mode = 'prn_login';
                $this->WYNIK["fo"] = get_post("notag");
                
                if(isset($_POST['ko_email']))
                if($this->test_arg()) {
                    $this->zapisz();
                } else {
                    $this->mode = "prn_login";
                }
            }
            if((isset($_GET['a'])) && ($_GET['a']=='obserwowane')) 
            {
                $this->mode = 'prn_obserwowane';
                if((isset($_GET['drop'])) && (!empty($_GET['drop']))) 
                {
                    $query = "select * from " . dn('obserwowane') . " where pr_id=" . (int)$_GET['drop'] . " and ko_id=" . $Cuzytkownik->get_id() . ";";
                    $res = $db->query($query);
                    if($db->num_rows($res)>0)
                    {
                        $query = "delete from " . dn('obserwowane') . " where pr_id=" . (int)$_GET['drop'] . " and ko_id=" . $Cuzytkownik->get_id() . ";";
                        $db->query($query);
                    }
                }
            }

            if((isset($_GET['a'])) && ($_GET['a']=='rabat')) $this->mode = 'prn_rabat';
            if((isset($_GET['a'])) && ($_GET['a']=='dlaciebie')) $this->mode = 'prn_dlaciebie';
            
            if(!$Cuzytkownik->is_klient()) $this->mode = 'prn_login';
            //$this->mode = 'prn_login';
            
            //miejsce gdzie przechodzi z koszyka do zakupów ale nie jest się zalogowanym
            if(isset($_GET["url"]) and (!empty($_GET['url'])) and isset($this->BACK[$_GET["url"]])) $this->WYNIK["fo"]["url"] = $_GLOBAL['page_url'] . $_GLOBAL['lang'] . '/' . $_GET["url"];
            elseif(isset($_POST["url"]) and (!empty($_POST['url'])) and isset($this->BACK[$_POST["url"]])) $this->WYNIK["fo"]["url"] = $_GLOBAL['page_url'] . $_GLOBAL['lang'] . '/' . $_POST["url"];
            
            if(($this->mode=='prn_login') && (!$Cuzytkownik->is_klient())) $this->WYNIK['fo']['ko_gomezclub'] = 'y';

            if ($Cuzytkownik->is_klient()) {
                debug('zalogowanie uzytkownika');
            }
            
        }
    }
    
    /**
     * Funkcja wyboru, czy zakupy bedą kontunuowane z logowaniem czy bez
     * 
     * 
     * @global array $_GLOBAL
     * @return string Szablon strony
     */
    function prn_selection()
    {
        global $_GLOBAL;
        $A['{FURL}'] = $_GLOBAL['page_url'] . $_GLOBAL['lang'] . '/zakup/';
        $A['{URL}'] = $_GLOBAL['page_url'];
        $A['{LANG}'] = $_GLOBAL['lang'];
        return get_template("user_selection",$A,$DD);;
    }
    
    
    
  /**
   * Interaktywna ścieżka
   *
   * @return string W tym przypadku pusty
   */
    function get_local() {
        return '';
    }
  
    /**
   * Funkcja wysyłanie hasła
   *
   * @param string $email
   * @return bool W zależności czy istnieje użytkownik o podanym adresie e-mail
   */
    
    function wyslij_haslo($email) {
        global $db;
        $T = $db->onerow("select uid,email from ".dn("uzytkownik_v")." where login='".a2($email)."' and status='1'");
        if(!is_array($T)) {
            $this->MESS_ERR[] = "{T_ERR_HASLO}";
            return false;
        }
        $haslo = substr(md5(time()),0,6);
        if(substr($T[0],0,1)=="k") {
            $db->query("update ".dn("sklep_kontrahent")." set ko_haslo='".pass2code($haslo)."' where ko_id=".substr($T[0],1));
        } else {
            $db->query("update ".dn("uzytkownik")." set uz_update='1', uz_data_update=".time().", uz_haslo='".pass2code($haslo)."' where uz_id=".substr($T[0],1));
        }

        global $Cuzytkownik;
        $Cuzytkownik->mail_haslo($T[1],$haslo);
        return true;
    }
  
  /**
   * Sprawdzenie poprawności wprowadzonych w formularzu danych
   *
   * @return boolen Wynik sprawdzenia
   */
    function test_arg() {
        global $Cuzytkownik,$db, $L;        
        
        $pole = "ko_email";
        if($this->WYNIK["fo"][$pole] == "" or !test_email($this->WYNIK["fo"][$pole])) {
            $MESS_ERR['email'] = $L['{T_NIEPRAWIDLOWY_ADRES_E_MAIL}'];//"- nieprawidłowy adres e-mail";
        }

        if($Cuzytkownik->is_klient()) {
            if($this->WYNIK["fo"]["ko_haslo"] != "" and $this->WYNIK["fo"]["ko_haslo"] != $this->WYNIK["fo"]["re_haslo"]) {
                $MESS_ERR['pass'] = $L['{T_OBA_NOWE_HASLA_MUSZA_BYC_IDENTYCZNE}'];//"- oba nowe hasła muszą być identyczne";
            }

            if($this->WYNIK["fo"]["ko_haslo"] != ""){
                $T = $db->onerow("select haslo from ".dn("uzytkownik_v")." where uid='k".$Cuzytkownik->get_id()."'");
                if(a2(pass2code($this->WYNIK["fo"]["ol_haslo"])) != $T[0] ){
                    $MESS_ERR['pass_before'] = $L['{T_NIEPRAWIDLOWE_POPRZEDNIE_HASLO}'];//"- nieprawidłowe poprzednie hasło";
                }
            }
        } else {
            if(strlen($this->WYNIK["fo"]["ko_haslo"])<6 or $this->WYNIK["fo"]["re_haslo"] == "" or $this->WYNIK["fo"]["ko_haslo"] !=$this->WYNIK["fo"]["re_haslo"])
                $MESS_ERR['pass'] = $L['{T_OBA_HASLA_MUSZA_BYC_IDENTYCZNE}'];//"- oba hasła muszą być identyczne i mieć min. 6 znaków";
        }

        if($this->WYNIK['fo']['ko_CLUB_NUMER']!='')
        {
            $query = "select * from " . dn('kontrahent') . " where ko_CLUB_NUMER='" . $this->WYNIK['fo']['ko_CLUB_NUMER'] . "' and ko_id!=" . (int)$Cuzytkownik->get_id() . ";";
            $res = $db->query($query);
            if($db->num_rows($res)>0) $MESS_ERR['gc'] = $L['{T_PODANY_NUMER_KARTY_GC_NALEZY_JUZ_DO_KOGOS_INNEGO}'];//"- Podany numer karty Gomez Club należy już do kogoś innego";
        }
        
    
        if((strlen($this->WYNIK["fo"]["ko_nazwa"])<3) || ($this->WYNIK["fo"]["ko_nazwa"] == 'write...') || ($this->WYNIK["fo"]["ko_nazwa"] == 'wpisz...')) {
            $MESS_ERR['nazwa'] = '- ' . $L["{T_ERR_IMIE}"];//"- wprowadź imie i nazwisko";
        }
        
        if(($this->WYNIK["fo"]["ko_sex"]==-1) || (!isset($this->WYNIK['fo']['ko_sex']))){
            $MESS_ERR['plec'] = $L['{T_WYBIERZ_PLEC}'];//"- wybierz płeć";
        }

        if((strlen($this->WYNIK["fo"]["ko_miasto"])<3) || ($this->WYNIK['fo']['ko_miasto'] == 'write...') || ($this->WYNIK['fo']['ko_miasto']=='wpisz...')) {
            $MESS_ERR['miasto'] = $L['{T_WPROWADZ_MIASTO}'];//wprowadź miasto";
        }

        if((strlen($this->WYNIK["fo"]["ko_kod"])==0) || ($this->WYNIK['fo']['ko_kod'] == 'write...') || ($this->WYNIK['fo']['ko_kod']=='wpisz...')) {
            $MESS_ERR['kod'] = $L['{T_WPROWADZ_KOD_POCZTOWY}'];//wprowadź miasto";
        }
        
        if(strlen($this->WYNIK["fo"]["ko_ulica"])<3 or !strlen($this->WYNIK["fo"]["ko_ulica_dom"])  || 
          ($this->WYNIK['fo']['ko_ulica'] == 'write...') || ($this->WYNIK['fo']['ko_ulica']=='wpisz...')) {
            $MESS_ERR['ulica'] = $L['{T_WPROWADZ_ULICE_I_NUMER}'];//wprowadź ulicę i numer";
        }

        if((strlen($this->WYNIK["fo"]["ko_telefon"])<3)  || ($this->WYNIK['fo']['ko_telefon'] == 'write...') || ($this->WYNIK['fo']['ko_telefon']=='wpisz...')) {
            $MESS_ERR['telefon'] = $L['{T_WPROWADZ_NUMER_TELEFONU}'];//"- wprowadź numer telefonu";
        }// else        
//        if(strlen($this->WYNIK['fo']['ko_telefon'])<9)// != 12
//        {
//            $MESS_ERR[] = $L['{T_NIEPOPRAWNY_NUMER_TELEFONU}'];//'- niepoprawny numer telefonu [np.: +48123123123]';
//        }

        if($this->WYNIK["fo"]["ko_zgoda"]!="on") {
            $MESS_ERR['zgoda'] = $L['{T_ABY_SIE_ZAREJESTROWAC_MUSISZ_ZAAKCEPTOWAC_REGULAMIN_SERWISU}'];//"- aby się zarejestrować musisz zaakceptować regulamin serwisu";
        }

        if(!$Cuzytkownik->is_klient() and $this->WYNIK["fo"]["ko_skad"]=="-1") {
            $MESS_ERR['skad'] = $L['{T_WPROWADZ_INFORMACJE_SKAD_DOW__}'];//"- wprowadź informację, skąd dowiedziałeś się o naszym sklepie";
        }

        if(!$Cuzytkownik->is_klient() && $this->WYNIK["fo"]["ko_skad"]=="inne" && empty($this->WYNIK['fo']['ko_skad_inne']))
        {
            $MESS_ERR['skad'] = $L['{T_PROSZE_PODAC_INNE_JAKIE}'];
        }
        
//        if(!$MESS_ERR) {
            $q = "select login from ".dn("uzytkownik_v")." where (login='".a2($this->WYNIK["fo"]["ko_email"])."') ";
            if($Cuzytkownik->is_klient())  $q .= "and uid <>'k".$Cuzytkownik->get_id()."' ";
//echo $q;
            $T = $db->onerow($q);
            if($T[0] == a2($this->WYNIK["fo"][$pole])) {
                $MESS_ERR['email'] = $L['{T_PODANY_ADRES_E_MAIL_WYSTEPUJE_JUZ_W_NASZEJ__}'];//"- podany adres e-mail występuje już w naszej bazie! Wprowadć inny lub zaloguj się.";
            }
//        }
    
        if($MESS_ERR) {
            //$this->MESS_ERR[] = $L['{T_WYSTAPILY_BLEDY_PRZY_WYPELNINNIU_FORMULARZA}'] . "<br/>".join("<br/>",$MESS_ERR);
            if(count($MESS_ERR)>0) foreach ($MESS_ERR as $key => $value) $this->MESS_ERR[$key] = $value;
            return false;
        }
        return true;
    }
  
    /**
     * Zapisanie danych klienta
     * 
     * @global object $db
     * @global object $Cuzytkownik
     */
    function zapisz() 
    {
  	global $db, $Cuzytkownik,$L;
        $POLA = array("email","miasto","kraj","ulica","ulica_dom","ulica_lok","nip","kod","gomezclub","CLUB_NUMER");
        foreach($POLA as $p) 
        {
            if(($this->WYNIK["fo"]["ko_".$p]=='wpisz...') || ($this->WYNIK["fo"]["ko_".$p]=='write...')) $Q[] = "ko_".$p."=''";
            else $Q[] = "ko_".$p."='".a2($this->WYNIK["fo"]["ko_".$p])."'";
        }
    
        if(($this->WYNIK["fo"]["ko_firma"]!="") && ($this->WYNIK["fo"]["ko_firma"]!="wpisz...") && ($this->WYNIK["fo"]["ko_firma"]!="write..."))
        {
            $Q[] = "ko_nazwa='".a2($this->WYNIK["fo"]["ko_firma"])."'";
        } else 
        {
            $Q[] = "ko_nazwa='".a2($this->WYNIK["fo"]["ko_nazwa"])."'";
        }
    
        $Q[] = "ko_data_update=".time();
        $Q[] = "ko_update='1'";
        if($Cuzytkownik->is_klient()) 
        { //Edycja danych
            $T = $db->onerow("select ko_telefony,ko_pracownicy from ".dn("kontrahent")." where ko_id=".$Cuzytkownik->get_id());
            $TT = explode("|",$T["ko_telefony"]);
            $TT[0] = ";;".a2($this->WYNIK["fo"]["ko_telefon"]).";";
            $Q[] = "ko_telefony='".join("|",$TT)."'";      

            $TT = explode("|",$T["ko_pracownicy"]);
            $TT[0] = "".a2($this->WYNIK["fo"]["ko_nazwa"]).";;";
            $Q[] = "ko_pracownicy='".join("|",$TT)."'";      
            $q = "update ".dn("kontrahent")." set ".join(", ",$Q)." where ko_id=".$Cuzytkownik->get_id();
            debug($q);
            $db->query($q);

            $q = "update " . dn('kontrahent') . " set ko_club=" . (($this->WYNIK["fo"]["ko_gomezclub"]=='y')?1:0) . " where ko_id=" . $Cuzytkownik->get_id() . ";";
            $db->query($q);
            if(($this->WYNIK["fo"]["ko_haslo"]!="") && ($this->WYNIK["fo"]["ko_haslo"]!="wpisz...") && ($this->WYNIK["fo"]["ko_haslo"]!="write..."))
            {
                $db->query("update ".dn("sklep_kontrahent")." set ko_haslo='".pass2code($this->WYNIK["fo"]["ko_haslo"])."' where ko_id=".$Cuzytkownik->get_id());
                $Cuzytkownik->zmien_haslo(pass2code($this->WYNIK["fo"]["ko_haslo"]));
            }
      
            $db->query("update " . dn('sklep_kontrahent') . " set ko_gomezclub='" . $this->WYNIK["fo"]["ko_gomezclub"] . "' where ko_id=".$Cuzytkownik->get_id() . ";");
      
	        $db->query("update " . dn('sklep_kontrahent') . " set ko_lang=" . $this->WYNIK["fo"]["ko_lang"] . " where ko_id=".$Cuzytkownik->get_id() . ";");

            
            $query = "replace into " . dn('sklep_kontrahent_other') . " ( `klient`, `rrrr`, `mm`, `dd`, `sex`) 
                      values (" . (int)$Cuzytkownik->get_id() . ",
                              " . (($this->WYNIK['fo']['ko_year']=='RRRR')?'NULL':(int)$this->WYNIK['fo']['ko_year']) . ",
                              " . (($this->WYNIK['fo']['ko_month']=='MM')?'NULL':(int)$this->WYNIK['fo']['ko_month']) . ",
                              " . (($this->WYNIK['fo']['ko_day']=='DD')?'NULL':(int)$this->WYNIK['fo']['ko_day']) . ",
                              '" . $this->WYNIK['fo']['ko_sex'] . "');";

            $db->query($query);
            
            $this->mode = "prn_login";
            $this->MESS_INF[] = "{T_ZACHOWANO}";
        } else 
        {
            $Q[] = "ko_telefony=';;".a2($this->WYNIK["fo"]["ko_telefon"]).";|;;;|;;;|'";
            $Q[] = "ko_pracownicy='".a2($this->WYNIK["fo"]["ko_nazwa"]).";;|;;|;;|'";
            $Q[] = "ko_ma_id=0";
            $Q[] = "ko_odbiorca='1'";
            $Q[] = "ko_data_in=".time();
            $q = "insert ".dn("kontrahent")." set ".join(", ",$Q);
            $db->query($q);      
            $Q = array();
            $id = $db->insert_id();
            $Q[] = "ko_id=".$id;
			$Q[] = "ko_lang=".$this->WYNIK["fo"]["ko_lang"];
            //$Q[] = "ko_status='2'";
            $Q[] = "ko_status='1'"; // -- status 1 aby można było się logować bez potwierdzania w link w mailu
            $Q[] = "ko_email_org='".a2($this->WYNIK["fo"]["ko_email"])."'";
            $Q[] = "ko_skad='".$this->WYNIK["fo"]["ko_skad"] . (((!empty($this->WYNIK['fo']['ko_skad_inne'])) && ($this->WYNIK["fo"]["ko_skad"]=='inne'))?': ' . $this->WYNIK['fo']['ko_skad_inne']:'') ."'";
            $Q[] = "ko_haslo='".pass2code($this->WYNIK["fo"]["ko_haslo"])."'";
            $q = "insert ".dn("sklep_kontrahent")." set ".join(", ",$Q);
            $db->query($q);      

            $q = "update " . dn('kontrahent') . " set ko_club=" . (($this->WYNIK["fo"]["ko_gomezclub"]=='y')?1:0) . ", ko_gomezclub=" . (($this->WYNIK["fo"]["ko_gomezclub"]=='y')?"'y'":'NULL') . "
                  where ko_id=" . $id . ";";
            $db->query($q);
            
            $q = "update " . dn('sklep_kontrahent') . " set ko_gomezclub=" . (($this->WYNIK["fo"]["ko_gomezclub"]=='y')?"'y'":'NULL') . " 
                  where ko_id=" . $id . ";";
            $db->query($q);
            
            $query = "replace into " . dn('sklep_kontrahent_other') . " ( `klient`, `rrrr`, `mm`, `dd`, `sex`) 
                      values (" . (int)$id . "," . $this->WYNIK['fo']['ko_year'] . "," . $this->WYNIK['fo']['ko_month'] . ",
                              " . $this->WYNIK['fo']['ko_day'] . ",'" . $this->WYNIK['fo']['ko_sex'] . "');";
            $db->query($query);
            
            //$Cuzytkownik->mail_rejestracja($id); //nie wysyłamy maila z linkiem
            $this->mode = "prn_rejestracja";
        }
		debug($this->WYNIK['fo']);
    }
  
    /**
     * Wyświetla 2 krok zakupu jeżeli użytkownik jest nie zalogowany
     * 
     * @global object $Cuzytkownik
     * @global object $db
     * @global array $_GLOBAL
     * @return string Formularz logowania / rejestracji
     */
    function prn_login() 
    {
        global $Cuzytkownik, $db, $_GLOBAL, $L;
        
        //15988
        $A['{STYLE_GC_LOCK}'] = '';
        if($Cuzytkownik->is_klient()) 
        {
            if(!isset($this->WYNIK["fo"]["ko_nazwa"])) 
            {
                $query = "select ko.ko_id, ko_nazwa, ko_nip, ko_miasto, ko_kod, ko_ulica, ko_ulica_dom, ko_ulica_lok, ko_kraj, ko_lang, 
                                 ko_telefony, ko_pracownicy, ko_email, sk.ko_punkt, sk.ko_gomezclub, ko.ko_club_numer
                          from ".dn("kontrahent")." ko, ".dn("sklep_kontrahent")." sk
                          where ko.ko_id=sk.ko_id 
                          and ko.ko_id=".$Cuzytkownik->get_id();

    		debug($query);
			
    		$this->WYNIK["fo"] = $db->onerow($query);
			
			debug($this->WYNIK["fo"]);
        	$T = explode("|",$this->WYNIK["fo"]["ko_telefony"]);
        	$X = explode(";",substr(trim($T[0]),0,-1));
        	$this->WYNIK["fo"]["ko_telefon"] = $X[2];
        
        	$T = explode("|",$this->WYNIK["fo"]["ko_pracownicy"]);
        	$X = explode(";",substr(trim($T[0]),0,-1));
        	$this->WYNIK["fo"]["ko_nazwa"] = $X[0];

        	$this->WYNIK["fo"]["ko_zgoda"] = "on";
                
                $query = "select * from " . dn('sklep_kontrahent_other') . " where klient=" . $Cuzytkownik->get_id() . ";";
                $res = $db->query($query);
                $dane = $db->fetch($res);
                
                $this->WYNIK['fo']['ko_year'] = $dane['rrrr'];
                $this->WYNIK['fo']['ko_month'] = $dane['mm'];
                $this->WYNIK['fo']['ko_day'] = $dane['dd'];
                $this->WYNIK['fo']['ko_sex'] = $dane['sex'];
                //$this->WYNIK['fo']['ko_club_numer'] = $dane['ko_club_numer'];
                
            }
            $DD[] = "IT_LOGIN";
            $DD[] = "IT_HASLO_REQ";
            $DD[] = "IT_HASLO2_REQ";
            $DD[] = "IT_SKAD";
            $DD[] = "IT_INTRO";
            $A["{TYTUL_FORM}"] = "{T_MOJE_DANE}";
            if($Cuzytkownik->get_id() == 15988)
            {
                $A['{STYLE_GC_LOCK}'] = ' style="display: none" ';
            }
        } else 
        {
            $DD[] = "IT_HASLO_OLD";
            $A["{TYTUL_FORM}"] = "{T_JESTEM_NOWY}";
            //$A['{URL}'] = 'uzytkownik';
            $A['{URL}'] = $_GLOBAL['page_url'];
            $A['{LANG}'] = $_GLOBAL['lang'];
            $A['{URLform}'] = 'uzytkownik';
            //$A['{KO_GOMEZCLUB}'] = ' checked ';
            //$this->WYNIK['fo']["ko_gomezclub"] = 'y';
        }
        
        $DD[] = "IT_POTWIERDZ";
        //$A["{ERRORR}"] = $this->get_komunikat();
    
        if(isset($this->WYNIK["fo"])) 
        {
            foreach($this->WYNIK["fo"] as $key=>$val) $A["{".strtoupper($key)."}"] = hs($val);
        } else 
        {
            $this->WYNIK["fo"]["ko_kraj"] = "PL";
        }

        // <div class="panelmenu"><a href="' . $_GLOBAL['page_url'] . $_GLOBAL['lang'] . '/uzytkownik/dlaciebie/"><div>' . $L['{T_SPECJALNIE_DLA_CIEBIE}'] . '</div></a></div>  
        if($Cuzytkownik->is_login())
        {
            $A['{PANELMENU}'] = '<div class="steps">
<div class="panelmenu"><a href="' . $_GLOBAL['page_url'] . $_GLOBAL['lang'] . '/uzytkownik/rabat/"><div>' . $L['{T_TWOJ_RABAT}'] . '</div></a></div>
<div class="panelmenu"><a href="' . $_GLOBAL['page_url'] . $_GLOBAL['lang'] . '/uzytkownik/obserwowane/"><div>' . $L['{T_OBSERWOWANE}'] . '</div></a></div>
<div class="panelmenu"><a href="' . $_GLOBAL['page_url'] . $_GLOBAL['lang'] . '/uzytkownik/dane/"><div class="choose">' . $L['{T_MOJE_DANE}'] . '</div></a></div>
<div class="panelmenu"><a href="' . $_GLOBAL['page_url'] . $_GLOBAL['lang'] . '/zamowienie/"><div>' . $L['{T_HISTORIA_ZAMOWIEN}'] . '</div></a></div>
<div class="panelmenu"><a href="' . $_GLOBAL['page_url'] . $_GLOBAL['lang'] . '/koszyk/"><div>' . $L['{T_PRZEJDZ_DO_KOSZYKA}'] . '</div></a></div>
</div>
<div class="separator"></div>';
       } else $A['{PANELMENU}'] = '';
       
        $LST = get_lista("user_from_".$_GLOBAL['lang']);
        foreach($LST as $k=>$v) $A["{KO_SKAD}"] .= '<option value="'.$k.'"'.($this->WYNIK["fo"]["ko_skad"]==$k?' selected':'').'>'.$v.'</option>';
        
        if(!empty($this->WYNIK['fo']['ko_skad_inne'])) $A['{KO_SKAD_INNE}'] = $this->WYNIK['fo']['ko_skad_inne'];
        else $A['{KO_SKAD_INNE}'] = '';
        
        $LST = get_lista("country",1);
        if(!$this->WYNIK["fo"]["ko_kraj"])$this->WYNIK["fo"]["ko_kraj"]="PL";
        foreach($LST as $k=>$v) $A["{KO_KRAJ}"] .= '<option value="'.$k.'"'.($this->WYNIK["fo"]["ko_kraj"]==$k?' selected':'').'>'.$v.'</option>';
        $A["{KO_ZGODA}"] = $this->WYNIK['fo']["ko_zgoda"]=="on"?' checked':'';
        $A["{KO_GOMEZCLUB}"] = $this->WYNIK['fo']["ko_gomezclub"]=="y"?' checked':'';
        //$A['KO_CLUB_NUMER'] = 
        $A['{URL}'] = $_GLOBAL['page_url'];
        $A['{LANG}'] = $_GLOBAL['lang'];
		if($_GLOBAL['lang'] == "pl"){
			if($this->WYNIK["fo"]["ko_lang"] == 0){
				$A["{KO_JEZYK_KOMUNIKACJI}"] .= '<option value="0" selected>Polski</option><option value="1">Angielski</option>';
			} else {
				$A["{KO_JEZYK_KOMUNIKACJI}"] .= '<option value="0">Polski</option><option value="1" selected>Angielski</option>';
		    }
		} else {
			if($this->WYNIK["fo"]["ko_lang"] == 0){
				$A["{KO_JEZYK_KOMUNIKACJI}"] .= '<option value="0" selected>Polish</option><option value="1">English</option>';
			} else {
				$A["{KO_JEZYK_KOMUNIKACJI}"] .= '<option value="0">Polish</option><option value="1" selected>English</option>';
		    }
		}
        
//        $A['{KO_SEX}'] = '<option value="k" ' . (($this->WYNIK['fo']['ko_sex']=='k')?' selected ':'') . '>Kobieta</option>
//                          <option value="m" ' . (($this->WYNIK['fo']['ko_sex']=='m')?' selected ':'') . '>Mężczyzna</option>';
        $A['{ko_sexk}'] = '' . (($this->WYNIK['fo']['ko_sex']=='k')?' checked ':'') . '';
        $A['{ko_sexm}'] = '' . (($this->WYNIK['fo']['ko_sex']=='m')?' checked ':'') . '';
/*
 * <!-- <select name="ko_sex" style="width: 146px;">
                    <option value="-1">{WYBIERZ}</option>
                    {KO_SEX}
                </select> -->
 *  
 */
        
//        echo '<pre>qweqwe: ' . print_r($this->emsg,true) . '</pre>';
//        echo '<pre>qweqwe: ' . print_r($this->MESS_ERR,true) . '</pre>';
        if(count($this->MESS_ERR)>0)            
            foreach ($this->MESS_ERR as $key => $value) {
                $A['{' . strtoupper($key) . '_MSG}'] = '<span class="" style="color: red; font-size: 11px;floats: right; line-height: 10px;">' . $value . '</span><span style="font-size: 6px;"><br></span>';
            }
        return get_template("user",$A,$DD);
    }
  
  /**
   * Potwierdzenie rejestracji
   *
   * @return string Informacja o potwierdzeniu rejestracji
   */
    function prn_rejestracja() {
        global $_GLOBAL;
        $DD[] = "IT_FORM";
        $DD['{URL}'] = $_GLOBAL['page_url'];
        $DD['{LANG}'] = $_GLOBAL['lang'];

        return get_template("user",'',$DD);
    }
  
  /**
   * Formularz przypomnienia hasła
   *
   * @return string Formularz
   */
    function prn_haslo() {
        $DD[] = "IT_POTWIERDZ";
        $A["{ERROR}"] = $this->get_komunikat();
        return get_template("user_password",$A,$DD);
    }
  
  /**
   * Potwierdzenie wygenerowania nowego hasła
   *
   * @return string Potwierdzenie
   */
    function prn_haslo_ok() {
        $DD[] = "IT_FORM";
        return get_template("user_password",'',$DD);
    }
  
  /**
   * Ekran powitalny po zalgowowaniu na stronie uzytkownik.htm
   *
   * @return string Komunikat
   */
    function prn_wejscie() {
        global $Cuzytkownik,$db, $_GLOBAL, $L;
        
        $DD[] = "IT_AKTYWACJA";
        
        $query = "select * from " . dn('kontrahent') . " where ko_id=" . $Cuzytkownik->get_id() . ";";
        $res = $db->query($query);
        $dane= $db->fetch($res);
        $A['{PANELTITLE}'] = $dane['ko_nazwa'];
        
        // <div class="panelmenu"><a href="' . $_GLOBAL['page_url'] . $_GLOBAL['lang'] . '/uzytkownik/dlaciebie/"><div>' . $L['{T_SPECJALNIE_DLA_CIEBIE}'] . '</div></a></div>
        $A['{PANELMENU}'] = '<div class="steps">
        <div class="panelmenu"><a href="' . $_GLOBAL['page_url'] . $_GLOBAL['lang'] . '/uzytkownik/rabat/"><div>' . $L['{T_TWOJ_RABAT}'] . '</div></a></div>
        <div class="panelmenu"><a href="' . $_GLOBAL['page_url'] . $_GLOBAL['lang'] . '/uzytkownik/obserwowane/"><div>' . $L['{T_OBSERWOWANE}'] . '</div></a></div>
        <div class="panelmenu"><a href="' . $_GLOBAL['page_url'] . $_GLOBAL['lang'] . '/uzytkownik/dane/"><div>' . $L['{T_MOJE_DANE}'] . '</div></a></div>
        <div class="panelmenu"><a href="' . $_GLOBAL['page_url'] . $_GLOBAL['lang'] . '/zamowienie/"><div>' . $L['{T_HISTORIA_ZAMOWIEN}'] . '</div></a></div>
        <div class="panelmenu"><a href="' . $_GLOBAL['page_url'] . $_GLOBAL['lang'] . '/koszyk/"><div>' . $L['{T_PRZEJDZ_DO_KOSZYKA}'] . '</div></a></div>
        </div>';

        $query = "select * from " . dn('cms') . " where st_id=72;";
        $res = $db->query($query);
        $dane= $db->fetch($res);
        
        if ($_GLOBAL['langid'] == 2) {
            $A['{PANELCONTENTSTART}'] = str_replace('\"','"',$dane['st_tresc_en']);
        } else {
            $A['{PANELCONTENTSTART}'] = str_replace('\"','"',$dane['st_tresc']);    
        }
        
        return get_template("user_enter",$A,$DD);
    }


  /**
   * Ekran z potwierdzeniem aktywowania konta.
   *
   * @return string Komunikat
   */
    function prn_aktywacja() {
        $DD[] = "IT_LOGOWANIE";
        return get_template("user_enter",'',$DD);
    }
    
  /**
   * Ekran z listą obserwowanych produktów
   *
   * @return string Zawartość
   */
    
    function prn_obserwowane() {
        global $db, $Ccms,$Cuzytkownik, $_GLOBAL, $L;
        $tpl = get_template("shop_obserwowane");
        $tpl = get_tag($tpl,"IT_ITEM",$item);

        $ids = array();
        $query = "select * from " . dn('obserwowane') . " where ko_id=" . (int)$Cuzytkownik->get_id() . ";";
        $res = $db->query($query);
        while($itm = $db->fetch($res))
        {
            $ids[] = $itm['pr_id'];
        }

        $qw = "where pr_id in (".join(",",$ids).")";
        $q = "select count(*) from ".dn("sklep_produkt")." ".$qw." ";

        if(count($ids)>0)$T = $db->onerow($q); 
        if($T[0]&&count($ids)>0) {
      
            $q = "select * from ";
            $q.= dn("sklep_produkt")." ";
            $q.= $qw."  ";

            $nr = $db->query($q);
            $LST = get_lista("order_status_".$_GLOBAL['lang']);
            $i=1;
            while($T = $db->fetch($nr)) {
                $query = "select * from " . dn('produkt') . "," . dn('produkt_atrybuty') . " ko,
                                        ".dn("produkt_atrybut")." at,".dn("sklep_producent")." pd, ".dn("sklep_kategoria_produkt")." ka ";
                $query .= "where pa_pr_id=pr_id ";    
                $query .= "and ko.pa_at_id=at.at_id ";
                $query .= "and " . $T['pr_pd_id'] . "=pd_id ";
                $query .= "and pr_id=kp_pr_id ";
                $query .= "and pr_id=" . $T['pr_id'] . " group by pr_id;";
                $res = $db->query($query);
                $dane = $db->fetch($res);

                $T["pr_cena_w_brutto"] = $CENA[$T["pr_id"]];

                debug($T["pr_cena_w_brutto"]);
                debug($T);
                $C["{LP}"]      = $i;
                $C["{MOD}"]     = $i++%2;
                $C["{ID}"]      = $T["pr_id"];
                $C["{ILE}"]     = $T["ilosc"];
                $C["{ATRYBUT}"] = ($dane["at_nazwa"]);
                $C["{AT_ID}"]   = ($T["at_id"]);
        
                if($T["ilosc"]>$ST[$T["pr_id"]][$T["at_id"]] and $_GLOBAL["shop_stan_kontrola"]=="tak") 
                {
                    $C["{ERROR}"] = 'error';
                    $ilosc_error=true;
                } else 
                {
                    $C["{ERROR}"] = '';
                }
        
                debug($T["pr_cena_w_brutto"] . '<'. $CENAO[$T['pr_id']] . '<br>');
        
                if(($T["pr_cena_w_brutto"] < $CENAO[$T['pr_id']]) && ($Cuzytkownik->is_login()) && ($Cuzytkownik->is_gomezclub())) 
                {
                    $C["{CENA1}"] = number_format($CENAO[$T["pr_id"]],2,".","") . ''; //cena indywidualnego towaru
                    //$C["{CENA2}"] = '<br><strong>Cena Gomez Club: ' . number_format($T["pr_cena_w_brutto"],2,".","") . '</strong> zł'; //cena indywidualnego towaru
                    $C["{CENA2}"] = number_format($T["pr_cena_w_brutto"],2,".","") . ' zł'; //cena indywidualnego towaru
			
                    $C['{CENAFORM}'] = number_format($T["pr_cena_w_brutto"],2,".","");
                } else 
                {
                    $C["{CENA1}"] = number_format($T["pr_cena_w_brutto"],2,".",""); //cena indywidualnego towaru
                    $C["{CENAFORM}"] = number_format($T["pr_cena_w_brutto"],2,".",""); //cena indywidualnego towaru
                }
        
                //$C["{WARTOSC}"] = number_format($T["pr_cena_w_brutto"]*$T["ilosc"],2,".",""); //wartosc indywidualnego towaru
                //$C["{WARTOSC}"] = number_format($CENAO[$T["pr_id"]]*$T["ilosc"],2,".",""); //wartosc indywidualnego towaru
                $C['{WARTOSC}'] = number_format($dane["pr_cena_w_brutto"],2,'.','');

                if ($_GLOBAL['langid'] != 1) {
                    $q = "select * from " . dn('sklep_product_translation') . " where pr_id=" . $T['pr_id'] . " and langid=" . $_GLOBAL['langid'] . " and name='nazwa';";
                    $r = $db->query($q);
                    $translation = $db->fetch($r);

                    $product_name = $translation['description'];
                } else {
                    $product_name = $T["pr_nazwa"];
                }                

                $C["{NAZWA}"] = hs($product_name);
                
                if($_GLOBAL['langid'] != 1)
                {
                    $prdDesc = array();
                    $query = "select * from " . dn('sklep_product_translation') . " where langid=" . $_GLOBAL['langid'] . " and pr_id=" . $T['pr_id'] . ";";
                    $resx = $db->query($query);
                    while($itemx = $db->fetch($resx))
                    {
                        $prdDesc[$item['name']] = $itemx['description'];
                    }
                    
                    if(!empty($prdDesc['nazwa'])) $C['{NAZWA}'] = $prdDesc['nazwa'];
                }
                
                //$C["{INFO}"] = hs($T["pd_nazwa"]);
                $C["{INFO}"] = $dane["pd_nazwa"];
                //$C["{URL}"] = "sklep,".$dane["kp_ka_id"].",".$T["pr_id"].",".conv2file(s($T["pr_nazwa"])).".htm";
                $C['{URL}'] = $_GLOBAL['page_url'];
                $C['{LANG}'] = $_GLOBAL['lang'];
                $C['{CLUBRABAT}'] = (($rabPrd[$T['pr_id']]==-1)?'brak':(int)$rabPrd[$T['pr_id']] . '%');
                $C['{CLUBWARTOSC}'] = (((isset($C['{CENA2}'])) && (!empty($C['{CENA2}'])))?$C['{CENA2}']:$C['{CENA1}']);
                $C['{ID}'] = $T['pr_id'];
                $C['{NAMEURL}'] = parse_str($T['pr_nazwa']);
                $C['{KPKAID}'] = $dane['kp_ka_id'];
        
                $C['{DATEADD}'] = $dane['pr_rok_produkcji'];
                
                $T["pr_plik"] = $dane["pr_plik"];
                
                if(substr($T["pr_plik"],0,8)=="http:///") 
                {
                    $T["pr_plik"] = substr($T["pr_plik"],9);
                }    
        
                if(substr($T["pr_plik"],0,4)=="http") 
                {
                    $T["pr_plik"] = substr($T["pr_plik"],4);
                    $T["pr_plik"] = substr($T["pr_plik"],strpos($T["pr_plik"],"/")+1);
                }

                
                $T["pr_plik"] = str_replace("/x/","/m/",get_dirfile($T["pr_plik"]));
                $C["{IMG}"] = ($T["pr_plik"]!="" and file_exists($T["pr_plik"]))?str_replace("/m/","/s/",$T["pr_plik"]):'images/nofoto_s.gif';
                $C["{IMG}"] = $_GLOBAL['page_url'] . $C["{IMG}"];
                //$C['{INFO}'] = $T['pd_nazwa'];
                $B["{IT_ITEM}"] .= get_template($item,$C,'',0);
                $suma += number_format($T["pr_cena_w_brutto"]*$T["ilosc"],2,".","");
                $sumaorg = number_format($CENAO[$T["pr_id"]]*$T["ilosc"],2,".","");
            }
            $DD[] = "IT_BRAK";
      //$B["{ILOSC}"] = $Cnawi->get_pozycje();
      //$B["{IDZDO}"] = $Cnawi->get_idzdo();
      //$B["{STRONY}"] = $Cnawi->prn_strony();    
        } else {
            $DD[] = "IT_LISTA";
        }
    
        // <div class="panelmenu"><a href="' . $_GLOBAL['page_url'] . $_GLOBAL['lang'] . '/uzytkownik/dlaciebie/"><div>' . $L['{T_SPECJALNIE_DLA_CIEBIE}'] . '</div></a></div>
        if($Cuzytkownik->is_klient()) $B['{PANELMENU}'] = '<div class="steps">
        <div class="panelmenu"><a href="' . $_GLOBAL['page_url'] . $_GLOBAL['lang'] . '/uzytkownik/rabat/"><div>' . $L['{T_TWOJ_RABAT}'] . '</div></a></div>
        <div class="panelmenu"><a href="' . $_GLOBAL['page_url'] . $_GLOBAL['lang'] . '/uzytkownik/obserwowane/"><div class="choose">' . $L['{T_OBSERWOWANE}'] . '</div></a></div>
        <div class="panelmenu"><a href="' . $_GLOBAL['page_url'] . $_GLOBAL['lang'] . '/uzytkownik/dane/"><div>' . $L['{T_MOJE_DANE}'] . '</div></a></div>
        <div class="panelmenu"><a href="' . $_GLOBAL['page_url'] . $_GLOBAL['lang'] . '/zamowienie/"><div>' . $L['{T_HISTORIA_ZAMOWIEN}'] . '</div></a></div>
        <div class="panelmenu"><a href="' . $_GLOBAL['page_url'] . $_GLOBAL['lang'] . '/koszyk/"><div>' . $L['{T_PRZEJDZ_DO_KOSZYKA}'] . '</div></a></div>
        </div>';
        else $B['{PANELMENU}'] = '';
    
        $B['{URL}'] = $_GLOBAL['page_url'];
        $B['{LANG}'] = $_GLOBAL['lang'];
        
        $A["{TOPID}"] = $Ccms->WYNIK["fo"]["st_st_id"];
        $A["{STID}"] = $Ccms->st_id;
        $A["{TYTUL}"] = $Ccms->get_tytul();
        $A["{TRESC}"] = get_template($tpl,$B,$DD,0);
    
        return get_template("module",$A,'');
  
    }
  
    /**
   * Ekran z rabatami dla kąkretnego klienta
   *
   * @return string Strona
   */
    
  function prn_rabat()
  {
      global $Cuzytkownik,$db, $_GLOBAL, $L;
      
      $rabatLevel = array();
      $rabatnow = 0;
      $rabat = 0;
      $suma = 0;
      $idk = 0;
      
      
        $DD[] = "IT_AKTYWACJA";
        
        $query = "select * from " . dn('kontrahent') . " where ko_id=" . $Cuzytkownik->get_id() . ";";
        $res = $db->query($query);
        $dane= $db->fetch($res);
        
        $rabatnow = (int)$dane['ko_CLUB_POZIOM'];
        $rabat = $dane['ko_CLUB_POZIOM'];
        $suma = $dane['ko_CLUB_SUMA'];
        
        
        $A['{PANELTITLE}'] = $dane['ko_nazwa'];
        
        // <div class="panelmenu"><a href="' . $_GLOBAL['page_url'] . $_GLOBAL['lang'] . '/uzytkownik/dlaciebie/"><div>' . $L['{T_SPECJALNIE_DLA_CIEBIE}'] . '</div></a></div>
        $A['{PANELMENU}'] = '<div class="steps">
        <div class="panelmenu"><a href="' . $_GLOBAL['page_url'] . $_GLOBAL['lang'] . '/uzytkownik/rabat/"><div class="choose">' . $L['{T_TWOJ_RABAT}'] . '</div></a></div>
        <div class="panelmenu"><a href="' . $_GLOBAL['page_url'] . $_GLOBAL['lang'] . '/uzytkownik/obserwowane/"><div>' . $L['{T_OBSERWOWANE}'] . '</div></a></div>
        <div class="panelmenu"><a href="' . $_GLOBAL['page_url'] . $_GLOBAL['lang'] . '/uzytkownik/dane/"><div>' . $L['{T_MOJE_DANE}'] . '</div></a></div>
        <div class="panelmenu"><a href="' . $_GLOBAL['page_url'] . $_GLOBAL['lang'] . '/zamowienie/"><div>' . $L['{T_HISTORIA_ZAMOWIEN}'] . '</div></a></div>
        <div class="panelmenu"><a href="' . $_GLOBAL['page_url'] . $_GLOBAL['lang'] . '/koszyk/"><div>' . $L['{T_PRZEJDZ_DO_KOSZYKA}'] . '</div></a></div>
        </div>';

        
        if($Cuzytkownik->is_klient() && $Cuzytkownik->is_gomezclub())
        {
            $query = "select * from " . dn('cms') . " where st_id=73;";
            $res = $db->query($query);
            $dane= $db->fetch($res);
            $B['[GCSTD]'] = $Cuzytkownik->getPercent() . '%';
            $B['[GCSALE]'] = ($Cuzytkownik->getPercent()/2) . '%';        
        
            //int mktime ( int $godzina , int $minuta , int $sekunda , int $miesiąc , int $dzień, int $rok [, int $letni/zimowy ] )
            $datado = '';
            if(!isset($item['datado']))
            {
                $datado = $L['{T_BRAK_RABATU}'];
            } else
            if((int)$item['datado']<time())
            {
                $datado = $L['{T_RABAT_WYGASL}'];
            } else
            {
                $datado = $item['data'];
            }
            /*
            5% od 1000 do 3999, Konto Brązowe
            10% od 4000 do 15999, Konto Srebrne
            15% od 16000 do 47999, Konto Złote
            20% od 48000, Konto Platynowe
             
            wszystkie te progi można nadać ręcznie,
             
            dodatkowo:
             
            30% wpisywanie ręczne, indywiduanie nadawane, Konto Platynowe VIP
            40% wpisywanie ręczne, indywiduanie nadawane, Konto Platynowe VIP
             */
            $co_dalej = '<table style="width: 770px" border="0">
                <tbody>
                <tr>
                    <td style="width: 780px; height: 42px; vertical-align: middle; text-align: center; font-family: Tahoma; font-size: 12px; color: #848484; background-image: url(/files/cms/0/rab_bg.jpg);background-repeat: no-repeat">' . $L['{T_CO_DALEJ__}'] . '</td>
                </tr>
                </tbody>
                </table>
                <p>&nbsp;</p>';
            if($rabat < 5)
            {
                $suma = 1000 - (int)$suma;
                if($suma<0) $suma = 0;
                $rabat = 5;
            } else
            if($rabat == 5)
            {
                $rabat = 10;
                $suma = 4000 - (int)$suma;
                if($suma<0) $suma = 0;
            } else
            if($rabat == 10)
            {
                $rabat = 15;
                $suma = 16000 - (int)$suma;
                if($suma<0) $suma = 0;
            } else
            if($rabat == 15)
            {
                $rabat = 20;
                $suma = 48000 - (int)$suma;
                if($suma<0) $suma = 0;
            } else
            {
                $rabat = $L['{T_BRAK_MOZLIWOSCI}'];
                $co_dalej = '';
            }
        
            $B['[DALEJRABAT]'] = $rabat;
            $B['[DALEJSUMA]'] = $suma;
            $co_dalej = str_replace(array('[DALEJRABAT]','[DALEJSUMA]'), array($rabat,$suma),$co_dalej);
   
       
            if ($_GLOBAL['langid'] == 2) {
                $A['{PANELCONTENTSTART}'] = get_template(s($dane['st_tresc_en']),$B,'',0);
            } else {
                $A['{PANELCONTENTSTART}'] = get_template(s($dane['st_tresc']),$B,'',0);
            }
            $A['{PANELCONTENTSTART}'] .= $co_dalej;
        
            if($rabatnow >= 20) $idk = 82;
            else
            if($rabatnow >= 15) $idk = 81;
            else
            if($rabatnow >= 10) $idk = 80;
            else
            if($rabatnow >= 5) $idk = 79;
        
            if($idk>0)
            {
                $query = "select * from " . dn('cms') . " where st_id=" . $idk . ";";
                $res = $db->query($query);
                $dane= $db->fetch($res);
            
                if ($_GLOBAL['langid'] == 2) {
                    $A['{PANELCONTENTSTART}'] .= str_replace('\"','"',$dane['st_tresc_en']);
                } else {
                    $A['{PANELCONTENTSTART}'] .= s($dane['st_tresc']);
                }
            }
        } else
        {
            $A['{PANELCONTENTSTART}'] .= $L['{T_PROSZE_ZAPISAC_SIE_DO_PROGRAMU_LOJALNOSCIOWEGO__}'];
        }
      
        return get_template("user_enter",$A,$DD);
  }
  
  /**
   * Ekran z informacją banerową
   *
   * @return string Strona
   */
  
  function prn_dlaciebie()
  {
      global $Cuzytkownik,$db, $_GLOBAL, $L;
        
        $DD[] = "IT_AKTYWACJA";
        
        $A['[R1]'] = $Cuzytkownik->getPercent();
        $A['[R2]'] = 0;
        $A['[RS]'] = $A['[R1]'] + $A['[R2]'];
        
        $query = "select * from " . dn('kontrahent') . " where ko_id=" . $Cuzytkownik->get_id() . ";";
        $res = $db->query($query);
        $dane= $db->fetch($res);
        $A['{PANELTITLE}'] = $dane['ko_nazwa'];
        
        // <div class="panelmenu"><a href="' . $_GLOBAL['page_url'] . $_GLOBAL['lang'] . '/uzytkownik/dlaciebie/"><div class="choose">' . $L['{T_SPECJALNIE_DLA_CIEBIE}'] . '</div></a></div>
        $A['{PANELMENU}'] = '<div class="steps">
        <div class="panelmenu"><a href="' . $_GLOBAL['page_url'] . $_GLOBAL['lang'] . '/uzytkownik/rabat/"><div>' . $L['{T_TWOJ_RABAT}'] . '</div></a></div>
        <div class="panelmenu"><a href="' . $_GLOBAL['page_url'] . $_GLOBAL['lang'] . '/uzytkownik/obserwowane/"><div>' . $L['{T_OBSERWOWANE}'] . '</div></a></div>
        <div class="panelmenu"><a href="' . $_GLOBAL['page_url'] . $_GLOBAL['lang'] . '/uzytkownik/dane/"><div>' . $L['{T_MOJE_DANE}'] . '</div></a></div>
        <div class="panelmenu"><a href="' . $_GLOBAL['page_url'] . $_GLOBAL['lang'] . '/zamowienie/"><div>' . $L['{T_HISTORIA_ZAMOWIEN}'] . '</div></a></div>
        <div class="panelmenu"><a href="' . $_GLOBAL['page_url'] . $_GLOBAL['lang'] . '/koszyk/"><div>' . $L['{T_PRZEJDZ_DO_KOSZYKA}'] . '</div></a></div>
        </div>';
        

        $query = "select * from " . dn('cms') . " where st_id=74;";
        $res = $db->query($query);
        $dane= $db->fetch($res);
        
        if ($_GLOBAL['langid'] == 2) {
            $A['{PANELCONTENTSTART}'] = str_replace(array('\"','[R1]','[R2]','[RS]'),array('"',$A['[R1]'],$A['[R2]'],$A['[RS]']),$dane['st_tresc_en']);
        } else {
            $A['{PANELCONTENTSTART}'] = str_replace(array('\"','[R1]','[R2]','[RS]'),array('"',$A['[R1]'],$A['[R2]'],$A['[RS]']),$dane['st_tresc']);
        }    
        
        //        $query = "select * from " . dn('foryou_settings') . " where id=1;";
        //        $res = $db->query($query);
        //        $item = $db->fetch($res);
        /*
        id, marka, wielkosc_zakupu_od, wielkosc_zakupu_do, przecena, miasto, 
        kod, plec, wiek_od, wiek_do, reg_od, reg_do, tresc_pl, tresc_en, priorytet
         */   
        //szukanie najczęściej kupowanej marki      
        
        $pd = array();
        $pdMax = array('count'=>0,'pd_id'=>0);
        $total = 0;
        $kod = '';
        $miasto = '';
        $plec = '';//0-oba, 1-kobieta, 2-mezczyzna
        $wiek = '';
        $reg = '';
        $przecenaTak = 0;
        $przecenaNie = 0;
        
        
        
        $query = "select * from " . dn('kontrahent') . " k, " . dn('sklep_kontrahent') . " sk 
                  where k.ko_id=sk.ko_id and k.ko_id=" . $Cuzytkownik->get_id() . ";";
        $res = $db->query($query);
        $item = $db->fetch($res);

        $kod = $item['ko_kod'];
        
        $miasto = $item['ko_miasto'];
        $reg = date("Y-m-d",$item['ko_data_update']);
        
        $query = "select * from " . dn('kontrahent') . " k, " . dn('sklep_kontrahent_other') . " sk 
                  where k.ko_id=sk.klient and k.ko_id=" . $Cuzytkownik->get_id() . ";";
        $res = $db->query($query);
        $item = $db->fetch($res);
        
        if(!isset($item['sex'])) $plec = 0;
        else{
            if($item['sex']=='k') $plec = 1;
            else $plec = 2;
        }
        
        $wiek = date("Y") - $item['rrrr'];
        
        $query = "select * from " . dn('sklep_zamowienie') . " where za_ko_id=" . (int)$Cuzytkownik->get_id() . ";";

        $res = $db->query($query);
        while ($item = $db->fetch($res))
        {
            //tutaj wylistować zakupione produkty z dodanymi id markami oraz dodać ilość zakupionych produktów dla każdej z marki
            //na koniec wybrać jedną najczęściej kupowaną markę
            $total += $item['za_wartosc_brutto'];
            $query = "select count(*) as count, sk.pd_nazwa, sk.pd_id
                     from " . dn('sklep_zamowienie_pozycja') . " szp, " . dn('sklep_produkt') . " sp, " . dn('sklep_producent') . " sk
                     where szp.zp_pr_id=sp.pr_id and
                           sp.pr_pd_id=sk.pd_id and
                           zp_za_id=" . $item['za_id'] . "
                     group by sk.pd_id order by count(*);";
            $res2 = $db->query($query);
            while ($item2 = $db->fetch($res2))
            {
                if(!isset($pd[$item2['pd_id']])) $pd[$item2['pd_id']] = $item2['count'];
                else $pd[$item2['pd_id']] += $item2['count'];
                if($item2['pr_cena_w_brutto'] <= ($item2['pr_cena_b_brutto']*70/100)) $przecenaTak++;
                else $przecenaNie++;                
            } 
        }
        
        //wyciągnięcie najczesciej kupowanej marki w wszystkich marek jakie były w zamówieniach
        if(count($pd)>0)
        foreach($pd as $k=>$v)
        {
            if($pdMax['count'] < $v)  
            {
                $pdMax['count'] = $v;
                $pdMax['pd_id'] = $k;
            }
        }
        
        $query = "select * from " . dn('foryou_settings') . " where id=1;";
        $res = $db->query($query);
        $item = $db->fetch($res);
        $query = "select * from " . dn('foryou') . " fu
                  left join " . dn('foryou_params') . " fup on fu.id=fup.foryou_id 
                  where (wielkosc_zakupu_od is NULL or wielkosc_zakupu_od = '' or wielkosc_zakupu_od=0 or wielkosc_zakupu_od>=" . $total . ") and
                        (wielkosc_zakupu_do is NULL or wielkosc_zakupu_do = '' or wielkosc_zakupu_do=0 or wielkosc_zakupu_do<=" . $total . ") and
                        (wiek_od is NULL or wiek_od = '' or wiek_od=0 or wiek_od>=" . $wiek . ") and
                        (wiek_do is NULL or wiek_do = '' or wiek_do=0 or wiek_do<=" . $wiek . ") and
                        (plec=0 or plec=" . (int)$plec . ") and
                        (miasto='' or miasto like '%" . $miasto . "%') and
                        (kod='' or kod='" . $kod . "') and
                        (przecena=0 or przecena=" . (($przecenaNie<$przecenaTak)?1:2) . ")
                        " . (($pdMax['pd_id']!=0)?" and fup.value = " . $pdMax['pd_id']:'') . "
                  order by priorytet
                  limit " . (int)$item['ilosc'] . ";";

        $res = $db->query($query);
        if($db->num_rows($res)>0) $A['{PANELCONTENTSTART}'] = '';
        
        while($item = $db->fetch($res))
        {
            if($_GLOBAL['langid'] != 1) $A['{PANELCONTENTSTART}'] .= $item['tresc_en'];
            else $A['{PANELCONTENTSTART}'] .= $item['tresc_pl'];
        }
        
        
        //$A['{PANELCONTENTSTART}'] .= 'asdasdasdasd';
        return get_template("user_enter",$A,$DD);
  }
}
?>