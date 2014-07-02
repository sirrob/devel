<?php
/***********************************************/
/*                                             */
/*    Created by: Michal Bzowy                 */
/*          Date: 2004-07-01                   */
/*                                             */
/***********************************************/
if ( !defined('SMDESIGN') ) {
	die("Hacking attempt");
}
#-------------------------------------------------------------------------------
# CLASS DB_MYSQL
#
# Zbior metod obslugi komunikacji z baza mysql
#
# parameters   : 
#
#-------------------------------------------------------------------------------

function pl_order($pole) {
  return "replace(replace(replace(replace(replace(replace(replace(replace(replace(replace(replace(replace(replace(replace(replace(replace(".$pole.",'ć','cz'),'ś','sz'),'ń','nz'),'ź','zx'),'ż','zz'),'ę','ez'),'ł','lz'),'ą','az'),'Ć','cz'),'Ś','sz'),'Ń','nz'),'Ź','zx'),'Ż','zz'),'Ę','ez'),'Ł','lz'),'Ą','az')";
}

function microtime_float(){ 
    list($usec, $sec) = explode(" ", microtime()); 
    return ((float)$usec + (float)$sec); 
} 
$DBSTAT = array();
$DBSTAT["time"]=0;
$DBSTAT["queries"]=0;
$DBSTAT["list"]=array();
class db_mysql {
    var $m_pierwszy_link=false;
    var $m_WYNIK=array();
    var $m_show_error;
    var $m_nast_zap=1;
    var $m_zapytan=0;
    var $m_time_start=0;
    var $m_db_name;

    function db_mysql($host, $user, $pass, $db, $show_error=true) {
        $this->m_time_start = microtime_float();
        $this->m_show_error = $show_error;
        $this->m_db_name = $db;
        $link = @mysql_connect ($host, $user, $pass);
        if ( !$link ) {
            $this->error ("CLASS_MYSQL->CONNECT:");
            return FALSE;
        }
        $this->m_pierwszy_link = $link;
        $this->select_db($db);
        //$this->query("set names utf8");
  	//$this->query("set names 'utf8';");
	//$this->query("SET CHARACTER SET utf8");
	//$this->query("SET collation_connection = utf8_general_ci");
	
 //    $this->query("set names 'latin2';");
	// $this->query("SET CHARACTER SET latin2");

        $this->query("SET NAMES 'latin2'");
        $this->query("SET lc_time_names = 'pl_PL'");
        $this->query("SET character_set_connection=latin2");
        $this->query("SET character_set_client=latin2");
        $this->query("SET character_set_results=latin2");

	//$this->query("SET collation_connection = latin2_general_ci");

        return $link;
    }

    function num_rows($nr_zap)
    {
        $out = 0;
        $out = @mysql_num_rows($this->m_WYNIK[$nr_zap]->result);
        return $out;
    }

    function close_db() {
        mysql_close();
    }

    function select_db($db,$link=-1) {
        if ( $link == -1 && $this->m_pierwszy_link !== false ) $link = $this->m_pierwszy_link;
        if ( ! @mysql_select_db ($db, $link) ) {
            $this->error ("CLASS_MySQL->SELECT_DB: Nieprawidlowa nazwa bazy danych (".$db.")");
            return FALSE;
        }
    }
  
    function affected_rows($link=-1) {
        if ( $link == -1 && $this->m_pierwszy_link !== false ) $link = $this->m_pierwszy_link;
        if ( $link==-1 ) $ret = @mysql_affected_rows();
        else $ret = @mysql_affected_rows($link);
        if($ret == -1) $this->error ("CLASS_MYSQL->AFFECTED: Nieprawidlowy wynik");
        return $ret;
    }
  
    function insert_id($link=-1) {
        if ( $link == -1 && $this->m_pierwszy_link !== false ) $link = $this->m_pierwszy_link;
        if ( $link == -1 ) return mysql_insert_id();
        else return mysql_insert_id($link);
    }


    function sendMsgToNina($id)
    {
        $link = mysql_connect( "sql.gomez.iq.pl",  "admin_www", "home2008");
        mysql_select_db("admin_www");
        if ($link) {
            $query = "SELECT pr.pr_indeks, sk.pr_nazwa, prod.pd_nazwa FROM mm_produkt pr LEFT JOIN mm_sklep_produkt sk ON pr.pr_id = sk.pr_id LEFT JOIN mm_sklep_producent prod ON sk.pr_pd_id = prod.pd_id  WHERE pr.pr_id = {$id}";
            $result = mysql_query($query, $link);
            if ($result) {
                $row = mysql_fetch_row($result);
                $pr_indeks = $row[0];
                $pr_nazwa = $row[1];
                $pr_producent = $row[2];
                $url = 'http://'.$_SERVER['HTTP_HOST'].$_SERVER['REDIRECT_URL'];
                $msg = "Produkt indeks: {$pr_indeks} <br>";
                $msg .= "Nazwa: {$pr_nazwa} <br>";
                $msg .= "Producent: {$pr_producent} <br>";
                $msg .= "<a href={$url}>{$url}</a> <br>";
                $MM["m_to"] = 'robert.bednarowski@gomez.pl';
                $MM["m_subject"] = "Gomez - błąd SQL ";
                $MM["m_message"] = $msg;
                my_mail_smtp($MM);
            }
        }
    }


    function sendMsg($content)
    {
        //sprawdzenie czy błąd dotyczy nieprawidłowej edycji produktu (np.zostawione puste pole)
        $pos = strpos($message, "mm_sklep_kategoria_produkt.kp_pr_id");
		 $spider = 0 ;
        //Tablica z nazwami robotów
        $browserRobots = array( "008", "ABACHOBot", "Accoona-AI-Agent", "AddSugarSpiderBot",
                          "AhrefsBot", "AnyApexBot", "Arachmo", "B-l-i-t-z-B-O-T",
                          "Baiduspider", "BecomeBot", "BeslistBot", "BillyBobBot",
                          "Bimbot", "Bingbot", "BlitzBOT", "boitho.com-dc", "boitho.com-robot",
                          "btbot", "CatchBot", "Cerberian Drtrs", "Charlotte",
                          "ConveraCrawler", "cosmos", "Covario IDS", "DataparkSearch",
                          "DiamondBot", "Discobot", "Dotbot", "EARTHCOM.info",
                          "EmeraldShield.com WebBot", "envolk[ITS]spider", "EsperanzaBot",
                          "Exabot", "FAST Enterprise Crawler", "FAST-WebCrawler", "FDSE robot",
                          "FindLinks", "FurlBot", "FyberSpider", "g2crawler",
                          "Gaisbot", "GalaxyBot", "genieBot", "Gigabot",
                          "Girafabot", "Googlebot", "Googlebot-Image", "Googleboot-Image",
                          "GurujiBot", "HappyFunBot", "hl_ftien_spider", "Holmes",
                          "htdig", "iaskspider", "ia_archiver", "iCCrawler", "ichiro",
                          "igdeSpyder", "IRLbot", "IssueCrawler", "Jaxified Bot",
                          "Jyxobot", "KoepaBot", "L.webis", "LapozzBot", "Larbin",
                          "LDSpider", "LexxeBot", "Linguee Bot", "LinkWalker",
                          "lmspider", "lwp-trivial", "mabontland", "magpie-crawler",
                          "Mediapartners-Google", "MJ12bot", "MLBot", "Mnogosearch",
                          "mogimogi", "MojeekBot", "Moreoverbot", "Morning Paper",
                          "msnbot", "MSRBot", "MVAClient", "mxbot", "NetResearchServer",
                          "NetSeer Crawler", "NewsGator", "NG-Search", "nicebot",
                          "noxtrumbot", "Nusearch Spider", "NutchCVS", "Nymesis",
                          "obot", "oegp", "omgilibot", "OmniExplorer_Bot",
                          "OOZBOT", "OpenWebIndex", "Orbiter", "PageBitesHyperBot",
                          "Peew", "polybot", "Pompos", "PostPost", "Psbot",
                          "PycURL", "Qseero", "Radian6", "RAMPyBot", "RufusBot",
                          "SandCrawler", "SBIder", "ScoutJet", "Scrubby",
                          "SearchSight", "Seekbot", "semanticdiscovery",
                          "Sensis Web Crawler", "SEOChat::Bot", "SeznamBot",
                          "Shim-Crawler", "ShopWiki", "Shoula robot", "silk",
                          "Sitebot", "Snappy", "sogou", "sogou spider", "Sosospider",
                          "Speedy Spider", "Sqworm", "StackRambler", "Statsbot",
                          "suggybot", "SurveyBot", "SynooBot", "Teoma",
                          "TerrawizBot", "TheSuBot", "Thumbnail.CZ robot", "TinEye",
                          "truwoGPS", "TurnitinBot", "TweetedTimes Bot", "TwengaBot",
                          "updated", "Urlfilebot", "Vagabondo", "VoilaBot", "Vortex",
                          "voyager", "VYU2", "webcollage", "Websquash.com", "wf84",
                          "WoFindeIch Robot", "WomlpeFactory", "Xaldon_WebSpider",
                          "yacy", "Yahoo! Slurp", "Yahoo! Slurp China", "YahooSeeker",
                          "YahooSeeker-Testing", "YandexBot", "YandexImages", "YandexMetrika",
                          "Yasaklibot", "Yeti", "YodaoBot", "yoogliFetchAgent",
                          "YoudaoBot", "Zao", "Zealbot", "zspider", "ZyBorg"
                        );
      $user_agent = strtoupper($_SERVER['HTTP_USER_AGENT']);
     // $user_agent = strtoupper("Mozilla/5.0 (compatible; MJ12bot/v1.4.5; http://www.majestic12.co.uk/bot.php?+)");
      if($user_agent != "") {
          //sprawdzenie czy w tablicy $_SERVER w nagłówku HTTP_USER_AGENT występuje nazwa jednego z robotów
          while (list($key, $val) = each($browserRobots)) {
              if (strstr($user_agent, strtoupper($val))) {
                  $spider = 1;
                  break;
              }
          }
      }

        //Jeżeli robot nie został znaleziony wysyłamy wiadomość o błędzie
      if ($spider == 0) {
             $message = $content . "\n\n" . print_r($_SERVER,true);
            // W przypadku każdej linii dłuższej niż 70 znaków powinniśmy użyć funkcji wordwrap()
             $message = wordwrap($message, 70);

           // Jeżeli błąd przy edycji produktu wyślij mail do nina.rzakiecka@gomez.pl
           if ($pos = strpos($message, "mm_sklep_kategoria_produkt.kp_pr_id"))
            {
                $pr_id = "";
                $found = false;
                for($i=$pos+35; $i<strlen($message); $i++)
                {
                    if(is_numeric($message[$i]))
                    {
                        $pr_id .= $message[$i];
                        $found = true;
                    }
                    else if(!is_numeric($message[$i]) && $found == true)
                    {
                        break;
                    }
                }
                $link = mysql_connect( "sql.gomez.iq.pl",  "admin_www", "home2008");
                mysql_select_db("admin_www");
                if ($link) {
                    $query = "SELECT pr.pr_indeks, sk.pr_nazwa, prod.pd_nazwa FROM mm_produkt pr LEFT JOIN mm_sklep_produkt sk ON pr.pr_id = sk.pr_id LEFT JOIN mm_sklep_producent prod ON sk.pr_pd_id = prod.pd_id  WHERE pr.pr_id = {$pr_id}";
                    $result = mysql_query($query, $link);
                    if ($result) {
                        $row = mysql_fetch_row($result);
                        $pr_indeks = $row[0];
                        $pr_nazwa = $row[1];
                        $pr_producent = $row[2];
                        $url = 'http://'.$_SERVER['HTTP_HOST'].$_SERVER['REDIRECT_URL'];
                        $msg = "Produkt indeks: {$pr_indeks} <br>";
                        $msg .= "Nazwa: {$pr_nazwa} <br>";
                        $msg .= "Producent: {$pr_producent} <br>";
                        $msg .= "<a href={$url}>{$url}</a> <br>";
                        $MM["m_to"] = 'robert.bednarowski@gomez.pl';
                        $MM["m_subject"] = "Gomez - błąd SQL ";
                        $MM["m_message"] = $msg;
                        my_mail_smtp($MM);
                    }
                }
            }
           // reszte błędów wysyłaj na adres maciej.szczachor@gomez.pl
            else{
              $M["m_to"] = 'maciej.szczachor@gomez.pl';
              $M["m_subject"] = "Gomez - błąd SQL " . date("Y-m-d H:i:s") . ' net';
              $M["m_message"] = $message;
              //debug($M);
              my_mail_smtp($M);
          }
       }
 }


    function query($q,$link=-1) {
        if ( $link == -1 && $this->m_pierwszy_link !== false ) $link = $this->m_pierwszy_link;
        $nr_zap = sprintf ("#%d", $this->m_nast_zap++);
        $this->m_WYNIK[$nr_zap]->query = $q;
        // if ( eregi("^ *select", $q) ) $typ_select = true;
        if (preg_match('/^ *select/i', $q)) $typ_select = true;
        else $typ_select = false;
        if ( $link==-1 ) $res = mysql_query ($q) or die ($this->sendMsg(mysql_error(). "\n\n" .$q));
        else $res = mysql_query ($q, $link) or die ($this->sendMsg (mysql_error() . "\n\n" . $q));

        $this->m_WYNIK[$nr_zap]->result = $res;
        if (!$res) {
            # wystapil blad
            $this->error ("CLASS_MYSQL->QUERY: BLAD ZAPYTANIA: ".$q);
        }

        if ( $typ_select ) $this->m_WYNIK[$nr_zap]->resultsize = @mysql_num_rows ($res);
        $this->m_WYNIK[$nr_zap]->count = 0;
        $this->m_WYNIK[$nr_zap]->time = microtime_float();
        $this->m_zapytan++;
        return $nr_zap;
    }

    function onerow($q,$link=-1) {
        if ( $link == -1 && $this->m_pierwszy_link !== false ) $link = $this->m_pierwszy_link;
        $nr = $this->query ($q, $link);
        if ( $nr ) {
            $T = $this->fetch($nr);
            return $T;
        }
        return false;
    }
  
#---------------------------------------------------------------------------
# is_table($tablename)
#
# Check if specified table exist in database
#
# Parameter: $tablename - table name for chceck
#---------------------------------------------------------------------------
    function is_table($name) {
        $q =  "SHOW TABLES";
        $nr = $this->query($q);
        while($TAB = $this->fetch($nr))
  		if (strtoupper($name) == strtoupper($TAB[0])) return true;
        return false;
    }

#-------------------------------------------------------------------------------
# fetch($nr_zap,$typ)
#
# Zwraca wiersz wyniku w postaci tabeli
#
# parametry : $nr_zap - numer zapytania
#             $typ - typ wyniku (MYSQL_ASSOC, MYSQL_NUM, MYSQL_BOTH)
#-------------------------------------------------------------------------------
    function fetch($nr_zap=-1,$typ = MYSQL_BOTH) {
        if($nr_zap==-1) $nr_zap = $this->nast_zap - 1;
        if ( ! isset ($this->m_WYNIK[$nr_zap]) ) $this->error ("CLASS_MySQL->FETCH: Wadliwy pierwszy argument");
        $this->m_WYNIK[$nr_zap]->count++;
        if ( ! $this->m_WYNIK[$nr_zap]->result ) return false;
        $T = @mysql_fetch_array ($this->m_WYNIK[$nr_zap]->result, $typ);
        if ( $T === false ) {
            @mysql_free_result ($this->m_WYNIK[$nr_zap]->result);
            $this->m_WYNIK[$nr_zap]->result = false;
        }
        return $T;
    }
  
    function get_all($q, $link=-1) {
        $T = array();
        if ( $link == -1 && $this->m_pierwszy_link !== false ) $link = $this->m_pierwszy_link;
        $nr = $this->query($q);
        while ($TAB = $this->fetch($nr)) $T[] = $TAB;
        return $T;
    }
  
    function error($mess) {
        global $_GLOBAL;
        debug(mysql_errno() . ": ".$mess." / ".mysql_error());
        if(isset($_GLOBAL["system_monit"]) and $_GLOBAL["system_monit"]) {
            if(function_exists("my_error")) my_error(mysql_errno() . ": ".$mess." / ".mysql_error());
        }
        exit;
    }

    function get_sumary() {
        global $_GLOBAL,$DBSTAT;
        if(!$_GLOBAL["debug"]) return;
        //return;
        $time_end = microtime_float();
        $time = $time_end - $this->m_time_start;
        $DBSTAT["time"] += $time;
        $Q[] = "Time: ".$DBSTAT["time"];
        $DBSTAT["queries"] += $this->m_zapytan;
        $Q[] = "Queries: ".$DBSTAT["queries"];
        $t = $this->m_time_start;
        foreach($this->m_WYNIK as $T) {
            $DBSTAT["list"][] = ($T->time-$t).": ".$T->query;
            $t = $T->time;
        }
        $Q[] = join("<br/>\n",$DBSTAT["list"]);
        return '<div style="clear:both;border:1px #999 solid; padding:10px;z-index:100;">'.join("<br/>\n",$Q).'</div>';
//    return join("<br/>",$Q);
    }
}

$db = new db_mysql($_GLOBAL["db_host"],$_GLOBAL["db_user"],$_GLOBAL["db_password"],$_GLOBAL["db_database"]);

?>