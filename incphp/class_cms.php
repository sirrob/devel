<?php
  /**
   * Klasa obsługi prezentacji stron CMSa
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
   * Klasa obsługi wyświetlania stron CMSa
   *
   */
  class cms extends layout {
    /**
     * Zmienna zawirajaca katualnie otwartą stronę
     *
     * var integer $ppp
     */
    var $ppp;

    /**
     * Konstruktor klasy
     *
     * @param array $ARG Parametry wejściowe
     */

    function cms($ARG) {
      global $_GLOBAL;
      $this->db_name     = "cms";
      $this->db_pre      = "st";
      $this->element_typ = "cms";
      $this->ppp         = (isset($_GLOBAL["cms_lista"]) ? $_GLOBAL["cms_lista"] : 2);
	
      debug($ARG);
      $this->layout($ARG);
      $this->menu_id = $this->id;
    }

    function generuj() {
      global $db, $Cuzytkownik, $_GLOBAL;
      if (isset($_GET["a"]) and $_GET["a"] == "szukaj" and ((isset($_GET["sz_key"]) and s($_GET["sz_key"]) != ""))) {
        $this->mode = "szukaj";
        if (isset($_GET["sz_key"])) $this->WYNIK["key"] = s($_GET["sz_key"]);
        $this->id = $this->menu_id = get_modul_cms(MO_MAPA);
        if (!$this->id) die("Do wykorzystania wyszukiwarki niezbędny jest moduł mapy serwisu.");
      }
        debug($this->id);
      if ($this->id) {
        $this->WYNIK["fo"] = $db->onerow("select * from " . dn("cms") . " where st_id='" . $this->id . "'");

        if ($_GLOBAL['langid'] != 1) {
          if (!empty($this->WYNIK['fo']['st_tytul_en'])) $this->WYNIK['fo']['st_tytul'] = $this->WYNIK['fo']['st_tytul_en'];
        }

        if (!is_array($this->WYNIK["fo"])) $this->error("Brak takiej strony");
        if (!$this->WYNIK["fo"]["st_widoczna"] and !$Cuzytkownik->test_right("cms", $this->id)) $this->error("Strona niewidoczna");
        if ($this->WYNIK["fo"]["st_url"] != "" and $this->id > 3) redirect(s($this->WYNIK["fo"]["st_url"]));
      } else {
//        redirect("/");
      }

      $this->WYNIK["strona"][$this->id] = array("id"           => $this->WYNIK["fo"]["st_id"],
                                                "id_id"        => $this->WYNIK["fo"]["st_st_id"],
                                                "widoczna"     => $this->WYNIK["fo"]["st_widoczna"],
                                                "tytul"        => $this->WYNIK["fo"]["st_tytul"],
                                                "url"          => $this->WYNIK["fo"]["st_url"],
                                                "url_tryb"     => $this->WYNIK["fo"]["st_url_tryb"],
                                                "baner_powiel" => $this->WYNIK["fo"]["st_baner_powiel"],
                                                "baner"        => $this->WYNIK["fo"]["st_baner"]);
      $id                               = $this->id;

      while ($this->WYNIK["strona"][$id]["id_id"] != 0) {
        $this->WYNIK["strona"][$this->WYNIK["strona"][$id]["id_id"]] = $db->onerow("select st_id id, st_st_id id_id,st_widoczna widoczna,st_tytul tytul,st_url url,st_url_tryb url_tryb,st_boks_powiel boks_powiel, st_baner_powiel baner_powiel,st_baner baner from " . dn($this->db_name) . " where st_id=" . $this->WYNIK["strona"][$id]["id_id"]);
        $id                                                          = $this->WYNIK["strona"][$id]["id_id"];
      }
    }

    /**
     * Tworzy zawartość strony
     *
     * @param array $ARG Parametry wejściowe
     *
     * @return string
     */

    function get_strona() {
      $TT = $DD = array();

      if ($this->mode == "szukaj") {
        $TT["{STRONA}"] = $this->get_lista();
      } elseif ($this->WYNIK["fo"]["st_typ"] == "tresc") {
        $TT["{STRONA}"] = $this->get_tresc($this->WYNIK["fo"]);
      } elseif ($this->WYNIK["fo"]["st_typ"] == "lista") {
        $TT["{STRONA}"] = $this->get_lista();
      } elseif ($this->WYNIK["fo"]["st_typ"] == "modul") {
        $TT["{STRONA}"] = $this->get_modul($this->WYNIK["fo"]["st_modul"]);
      } elseif ($this->mode == 'brands') {
      }

      return get_template($this->get_strona_tpl(), $TT, $DD, 0);
    }

    /**
     * Tytuł strony
     *
     * @return string
     */
    function get_tytul() {
      return s($this->WYNIK["fo"]["st_tytul"]);
    }

    /**
     * Dział
     *
     * @param array $ARG Parametry wejściowe
     *
     * @return integer id działu
     */

    function get_sekcja() {
      $id = $this->id;
      while ($this->WYNIK["parent"][$id] != 0) $id = $this->WYNIK["parent"][$id];

      return $id;
    }

    function get_sekcja_tytul($id = 0) {
      if ($id == 0) $id = $this->get_sekcja();

      return s($this->WYNIK["strona"][$id]["st_tytul"]);
    }

    /**
     * Przygotowuje treść strony
     *
     * @param array $T Parametry wejściowe
     *
     * @return string
     */

    function get_tresc($T) {
      global $Cuzytkownik, $_GLOBAL, $db;
      $DD           = array();
      $A["{TOPID}"] = $this->get_sekcja();

      if ($_GLOBAL['langid'] != 1) {
        if (!empty($T["st_tytul_en"])) $A["{TYTUL}"] = s($T["st_tytul_en"]);
        else $A["{TYTUL}"] = s($T["st_tytul"]);
      } else $A["{TYTUL}"] = s($T["st_tytul"]);


      if ($T["st_data"] != "") $A["{DATA}"] = s($T["st_data"]); else $DD[] = "IT_DATA";

//print_r($T);
      if ($T["st_wstep"] != "") $A["{WSTEP}"] = nl2br(s($T["st_wstep"]));
      else $DD[] = "IT_WSTEP";


      if ($_GLOBAL['langid'] != 1) {
        if (!empty($T["st_tresc_en"])) $TRESC = explode("<p><!-- pagebreak --></p>", s($T["st_tresc_en"]));
        else $TRESC = explode("<p><!-- pagebreak --></p>", s($T["st_tresc"]));
      } else $TRESC = explode("<p><!-- pagebreak --></p>", s($T["st_tresc"]));

      $part         = (isset($_GET["part"]) and test_int($_GET["part"]) and isset($TRESC[$_GET["part"]])) ? $_GET["part"] : 0;
      $A["{TRESC}"] = $TRESC[$part];

      if (count($TRESC) > 1) {
        $A["{TRESC}"] .= '<div class="czesc">';
        for ($i = 0; $i < count($TRESC); $i++) {
          $A["{TRESC}"] .= '<a href="' . m_url("cms", $T["st_id"], s($T["st_tytul"]), $T["st_url"], $T["st_typ"], $T["st_modul"]) . '&part=' . $i . '"' . ($i == $part ? ' class="sel"' : '') . '>' . ($i + 1) . '</a>';
        }
        $A["{TRESC}"] .= '<div class="clear"></div></div>';
      }

      $A["{TRESC}"] .= s($T["st_html"]);
      $A["{URL}"]  = "http://" . $_SERVER['HTTP_HOST'] . $_SERVER["REQUEST_URI"];
      $A["{STID}"] = $this->id;

      if ($this->id == 1) {
//tutaj generowanie i wyświetlanie strony głównej

        if (file_exists("files/prototyp/home.tpl")) {
          $tpl         = my_fread("files/prototyp/home.tpl");
          $banertop    = '';
          $banerbottom = '';
          $logotypes   = '';

          /*                $query = "select * from " . dn('brands') . " where active='y' and home='y';";
                          $re = $db->query($query);
                          while($item = $db->fetch($re))
                          {
                              if(!empty($item['link']))
                              {
                                  $logotypes .= '<li><a href="' . $_GLOBAL['page_url'] . $_GLOBAL['lang'] . '/brands/' . $item['link'] . '" title="' . $item['name'] . '"><img src="' . $_GLOBAL['page_url'] . 'files/brands/' . $item['file'] . '?p=' . time() . '" width="125" height="60" alt="' . $item['name'] . '" /></a></li>';
                              } else
                              {
                                  $logotypes .= '<li><img src="' . $_GLOBAL['page_url'] . 'files/brands/' . $item['file'] . '?p=' . time() . '" width="125" height="60" alt="' . $item['name'] . '" /></li>';
                              }
                          }*/

          /*
                  $query = "select * from " . dn('baners') . " where page=1;";
                  $re = $db->query($query);
                  $baner = $db->fetch($re);

                  $banertpl = my_fread('files/prototyp/baner' . $baner['template'] . '.tpl');

                  $query = "select * from " . dn('baners_files') . " where baners=" . $baner['id'] . ";";
                  $re = $db->query($query);
                  while($item = $db->fetch($re))
                  {
                      $tml = '';
                      if($item['type']=='jpg')
                      {
                          if(!empty($item['link']))
                          {
                              $tml = '<a href="' . $item['link'] . '"><img src="' . $_GLOBAL['page_url'] . 'files/baners/' . $item['plik'] . '?p=' . time() . '" alt="" /></a>';
                          } else
                          {
                              $tml = '<img src="' . $_GLOBAL['page_url'] . 'files/baners/' . $item['plik'] . '?p=' . time() . '" alt="" />';
                          }
                      } else
                      if($item['type']=='swf')
                      {
                          if($baner['template']==1)
                          {
                              $sizer[1]['width'] = 990;
                              $sizer[1]['height'] = 375;
                          } else
                          if($baner['template']==2)
                          {
                              $sizer[1]['width'] = 492;
                              $sizer[1]['height'] = 374;
                              $sizer[2]['width'] = 492;
                              $sizer[2]['height'] = 374;
                          } else
                          if($baner['template']==3)
                          {
                              $sizer[1]['width'] = 492;
                              $sizer[1]['height'] = 184;
                              $sizer[2]['width'] = 492;
                              $sizer[2]['height'] = 184;
                              $sizer[3]['width'] = 492;
                              $sizer[3]['height'] = 184;
                              $sizer[4]['width'] = 492;
                              $sizer[4]['height'] = 184;
                          } else
                          if($baner['template']==4)
                          {
                              $sizer[1]['width'] = 741;
                              $sizer[1]['height'] = 374;
                              $sizer[2]['width'] = 243;
                              $sizer[2]['height'] = 374;
                          } else
                          if($baner['template']==5)
                          {
                              $sizer[1]['width'] = 243;
                              $sizer[1]['height'] = 374;
                              $sizer[2]['width'] = 243;
                              $sizer[2]['height'] = 374;
                              $sizer[3]['width'] = 243;
                              $sizer[3]['height'] = 374;
                              $sizer[4]['width'] = 243;
                              $sizer[4]['height'] = 374;
                          } else
                          if($baner['template']==6)
                          {
                              $sizer[1]['width'] = 243;
                              $sizer[1]['height'] = 374;
                              $sizer[2]['width'] = 741;
                              $sizer[2]['height'] = 374;
                          }

  //                        $tml = '
  //                                <object style="border: solid 0px red;" width="' . $sizer[$item['position']]['width'] . '" height="' . $sizer[$item['position']]['height'] . '" type="application/x-shockwave-flash" id="flash_id">
  //                                <param name="allowScriptAccess" value="sameDomain" />
  //                                <param name="movie" value="files/baners/' . $item['plik'] . '" />
  //                                <param name="quality" value="high" />
  //                                <param name="wmode" value="transparent">
  //                                <param name="scale" value="scale" />
  //                                <param name="menu" value="false" />
  //                                <embed src="files/baners/' . $item['plik'] . '" menu="false" scale="scale" wmode="transparent" quality="high" width="' . $sizer[$item['position']]['width'] . '" height="' . $sizer[$item['position']]['height'] . '" allowscriptaccess="sameDomain" type="application/x-shockwave-flash" pluginspage="http://www.macromedia.com/go/getflashplayer" />
  //                                </object>';
                          $tml = '
                                  <object classid="clsid:d27cdb6e-ae6d-11cf-96b8-444553540000" style="border: solid 0px red;" codebase="http://fpdownload.macromedia.com/pub/shockwave/cabs/flash/swflash.cab#version=8,0,0,0" width="' . $sizer[$item['position']]['width'] . '" height="' . $sizer[$item['position']]['height'] . '" id="flash_id" align="middle">
                                  <param name="allowScriptAccess" value="sameDomain" />
                                  <param name="movie" value="files/baners/' . $item['plik'] . '" />
                                  <param name="quality" value="high" />
                                  <param name="wmode" value="transparent">
                                  <param name="scale" value="scale" />
                                  <param name="menu" value="false" />
                                  <embed src="files/baners/' . $item['plik'] . '" menu="false" scale="scale" wmode="transparent" quality="high" width="' . $sizer[$item['position']]['width'] . '" height="' . $sizer[$item['position']]['height'] . '" name="flash_id" align="middle" allowscriptaccess="sameDomain" type="application/x-shockwave-flash" pluginspage="http://www.macromedia.com/go/getflashplayer" />
                                  </object>';
                      } else
                      if($item['type']=='flv')
                      {} else
                      if($item['type']=='mp4')
                      {
                          if($baner['template']==1)
                          {
                              $sizer[1]['width'] = 990;
                              $sizer[1]['height'] = 375;
                          } else
                          if($baner['template']==2)
                          {
                              $sizer[1]['width'] = 492;
                              $sizer[1]['height'] = 374;
                              $sizer[2]['width'] = 492;
                              $sizer[2]['height'] = 374;
                          } else
                          if($baner['template']==3)
                          {
                              $sizer[1]['width'] = 492;
                              $sizer[1]['height'] = 184;
                              $sizer[2]['width'] = 492;
                              $sizer[2]['height'] = 184;
                              $sizer[3]['width'] = 492;
                              $sizer[3]['height'] = 184;
                              $sizer[4]['width'] = 492;
                              $sizer[4]['height'] = 184;
                          } else
                          if($baner['template']==4)
                          {
                              $sizer[1]['width'] = 741;
                              $sizer[1]['height'] = 374;
                              $sizer[2]['width'] = 243;
                              $sizer[2]['height'] = 374;
                          } else
                          if($baner['template']==5)
                          {
                              $sizer[1]['width'] = 243;
                              $sizer[1]['height'] = 374;
                              $sizer[2]['width'] = 243;
                              $sizer[2]['height'] = 374;
                              $sizer[3]['width'] = 243;
                              $sizer[3]['height'] = 374;
                              $sizer[4]['width'] = 243;
                              $sizer[4]['height'] = 374;
                          } else
                          if($baner['template']==6)
                          {
                              $sizer[1]['width'] = 243;
                              $sizer[1]['height'] = 374;
                              $sizer[2]['width'] = 741;
                              $sizer[2]['height'] = 374;
                          }


                          $tml = '<object width="' . $sizer[$item['position']]['width'] . '" height="' . $sizer[$item['position']]['height'] . '" type="application/x-shockwave-flash" data="' . $_GLOBAL['page_url'] . 'files/player.swf">
          <param name="movie" value="' . $_GLOBAL['page_url'] . 'files/player.swf" />
          <param name="flashvars" value="controlbar=over&amp;image=' . $_GLOBAL['page_url'] . 'files/baners/' . str_replace('mp4','jpg',$item['plik']) .'&amp;file=' . $_GLOBAL['page_url'] . 'files/baners/' . $item['plik'] . '" />
                                  <embed src="' . $_GLOBAL['page_url'] . 'files/player.swf" FlashVars="controlbar=over&amp;image=' . $_GLOBAL['page_url'] . 'files/baners/' . str_replace('mp4','jpg',$item['plik']) .'&amp;file=' . $_GLOBAL['page_url'] . 'files/baners/' . $item['plik'] . '"  menu="false" scale="scale" wmode="transparent" quality="high" width="' . $sizer[$item['position']]['width'] . '" height="' . $sizer[$item['position']]['height'] . '" allowscriptaccess="sameDomain" type="application/x-shockwave-flash" pluginspage="http://www.macromedia.com/go/getflashplayer" />
                              </object>';

                      }
                      $banertpl = str_replace('{BANER' . $item['position'] . '}',$tml,$banertpl);
                  }

                  $banertop = $banertpl;
                  */

          $banertop =
            '<div class="slider-wrapper theme-default">
              <div id="slider" class="nivoSlider">
			                <a href="http://gomez.pl/' . $_GLOBAL['lang'] . '/groups/guess_kids/"><img src="http://gomez.pl/images/baner_top/2014_05_09/guess.jpg" data-thumb="http://gomez.pl/images/baner_top/2014_05_09/guess.jpg" alt="" title="" data-transition="fade"/></a>
							<a href="http://gomez.pl/' . $_GLOBAL['lang'] . '/brands/ice_iceberg/"><img src="http://gomez.pl/images/baner_top/2014_05_09/ice.jpg" data-thumb="http://gomez.pl/images/baner_top/2014_05_09/ice.jpg" alt="" title="" data-transition="fade"/></a>
						    <a href="http://gomez.pl/' . $_GLOBAL['lang'] . '/brands/marc_o_polo/"><img src="http://gomez.pl/images/baner_top/2014_05_09/mop.jpg" data-thumb="http://gomez.pl/images/baner_top/2014_05_09/mop.jpg" alt="" title="" data-transition="fade"/></a>
							<a href="http://gomez.pl/' . $_GLOBAL['lang'] . '/brands/ea7/"><img src="http://gomez.pl/images/baner_top/2014_05_06/ea7.jpg" data-thumb="http://gomez.pl/images/baner_top/2014_05_06/ea7.jpg" alt="" title="" data-transition="fade"/></a>
                            <a href="http://gomez.pl/' . $_GLOBAL['lang'] . '/brands/love_moschino/"><img src="http://gomez.pl/images/baner_top/2014_05_06/lm.jpg" data-thumb="http://gomez.pl/images/baner_top/2014_05_06/lm.jpg" alt="" title="" data-transition="fade"/></a>
							<a href="http://gomez.pl/' . $_GLOBAL['lang'] . '/brands/calvin_klein_jeans/"><img src="http://gomez.pl/images/baner_top/2014_05_06/ck.jpg" data-thumb="http://gomez.pl/images/baner_top/2014_05_06/ck.jpg" alt="" title="" data-transition="fade"/></a>
			                <a href="http://gomez.pl/' . $_GLOBAL['lang'] . '/brands/hilfiger_denim/"><img src="http://gomez.pl/images/baner_top/2014_05_06/hd.jpg" data-thumb="http://gomez.pl/images/baner_top/2014_05_06/hd.jpg" alt="" title="" data-transition="fade"/></a>
							<a href="http://gomez.pl/' . $_GLOBAL['lang'] . '/brands/pepe_jeans_london/"><img src="http://gomez.pl/images/baner_top/2014_05_06/pj.jpg" data-thumb="http://gomez.pl/images/baner_top/2014_05_06/pj.jpg" alt="" title="" data-transition="fade"/></a>
							<a href="http://gomez.pl/' . $_GLOBAL['lang'] . '/brands/hugo_boss_green/"><img src="http://gomez.pl/images/baner_top/2014_04_25/bg.jpg" data-thumb="http://gomez.pl/images/baner_top/2014_04_25/bg.jpg" alt="" title="" data-transition="fade"/></a>
							<a href="http://gomez.pl/' . $_GLOBAL['lang'] . '/brands/armani_jeans/"><img src="http://gomez.pl/images/baner_top/2014_02_07/aj_ss14.jpg" data-thumb="http://gomez.pl/images/baner_top/2014_02_07/aj_ss14.jpg" alt="" title="" data-transition="fade"/></a>
			                <a href="http://gomez.pl/' . $_GLOBAL['lang'] . '/brands/liu_jo/"><img src="http://gomez.pl/images/baner_top/2014_04_25/liujo.jpg" data-thumb="http://gomez.pl/images/baner_top/2014_04_25/liujo.jpg" alt="" title="" data-transition="fade"/></a>
							<a href="http://gomez.pl/' . $_GLOBAL['lang'] . '/brands/hugo_boss_orange/"><img src="http://gomez.pl/images/baner_top/2014_04_25/bo.jpg" data-thumb="http://gomez.pl/images/baner_top/2014_04_25/bo.jpg" alt="" title="" data-transition="fade"/></a>
							<a href="http://gomez.pl/' . $_GLOBAL['lang'] . '/brands/tommy_hilfiger/"><img src="http://gomez.pl/images/baner_top/2014_02_07/th_ss14.jpg" data-thumb="http://gomez.pl/images/baner_top/2014_02_07/th_ss14.jpg" alt="" title="" data-transition="fade"/></a>
							
						    
							
							
							
							
							
							
							
							
							
							
							
							
							
							
							
							
						
							
							
						</div>			
					</div>';


          $query = "select * from " . dn('baners') . " where page=2 and langid=" . $_GLOBAL['langid'] . ";";
          $re    = $db->query($query);
          $baner = $db->fetch($re);

          $banertpl = my_fread('files/prototyp/baner' . $baner['template'] . '.tpl');

          $query = "select * from " . dn('baners_files') . " where baners=" . $baner['id'] . " and langid = " . $_GLOBAL['langid'] . ";";
          $re    = $db->query($query);
          while ($item = $db->fetch($re)) {
            $tml = '';
            if ($item['type'] == 'jpg') {
              if (!empty($item['link'])) {
                $tml = '<a class="bottomBannersMargin" href="' . $item['link'] . '"><img src="' . $_GLOBAL['page_url'] . 'files/baners/' . $item['plik'] . '?p=' . time() . '" alt="" /></a>';
              } else {
                $tml = '<img src="' . $_GLOBAL['page_url'] . 'files/baners/' . $item['plik'] . '?p=' . time() . '" alt="" />';
              }
            } else
              if ($item['type'] == 'swf') {
                if ($baner['template'] == 1) {
                  $sizer[1]['width']  = 990;
                  $sizer[1]['height'] = 375;
                } else
                  if ($baner['template'] == 2) {
                    $sizer[1]['width']  = 492;
                    $sizer[1]['height'] = 374;
                    $sizer[2]['width']  = 492;
                    $sizer[2]['height'] = 374;
                  } else
                    if ($baner['template'] == 3) {
                      $sizer[1]['width']  = 492;
                      $sizer[1]['height'] = 184;
                      $sizer[2]['width']  = 492;
                      $sizer[2]['height'] = 184;
                      $sizer[3]['width']  = 492;
                      $sizer[3]['height'] = 184;
                      $sizer[4]['width']  = 492;
                      $sizer[4]['height'] = 184;
                    } else
                      if ($baner['template'] == 4) {
                        $sizer[1]['width']  = 741;
                        $sizer[1]['height'] = 374;
                        $sizer[2]['width']  = 243;
                        $sizer[2]['height'] = 374;
                      } else
                        if ($baner['template'] == 5) {
                          $sizer[1]['width']  = 243;
                          $sizer[1]['height'] = 374;
                          $sizer[2]['width']  = 243;
                          $sizer[2]['height'] = 374;
                          $sizer[3]['width']  = 243;
                          $sizer[3]['height'] = 374;
                          $sizer[4]['width']  = 243;
                          $sizer[4]['height'] = 374;
                        } else
                          if ($baner['template'] == 6) {
                            $sizer[1]['width']  = 243;
                            $sizer[1]['height'] = 374;
                            $sizer[2]['width']  = 741;
                            $sizer[2]['height'] = 374;
                          }

                $tml = '
                                <object data="files/baners/' . $item['plik'] . '" type="application/x-shockwave-flash" style="border: solid 0px red;" width="' . $sizer[$item['position']]['width'] . '" height="' . $sizer[$item['position']]['height'] . '" id="flash_id">
                                <param name="allowScriptAccess" value="sameDomain" />
                                <param name="movie" value="files/baners/' . $item['plik'] . '" />
                                <param name="quality" value="high" />
                                <param name="wmode" value="transparent">
                                <param name="scale" value="scale" />
                                <param name="menu" value="false" />
                                <embed src="files/baners/' . $item['plik'] . '" menu="false" scale="scale" wmode="transparent" quality="high" width="' . $sizer[$item['position']]['width'] . '" height="' . $sizer[$item['position']]['height'] . '" allowscriptaccess="sameDomain" type="application/x-shockwave-flash" pluginspage="http://www.macromedia.com/go/getflashplayer" />
                                </object>';
              } else
                if ($item['type'] == 'flv') {
                } else
                  if ($item['type'] == 'mp4') {
                    if ($baner['template'] == 1) {
                      $sizer[1]['width']  = 990;
                      $sizer[1]['height'] = 375;
                    } else
                      if ($baner['template'] == 2) {
                        $sizer[1]['width']  = 492;
                        $sizer[1]['height'] = 374;
                        $sizer[2]['width']  = 492;
                        $sizer[2]['height'] = 374;
                      } else
                        if ($baner['template'] == 3) {
                          $sizer[1]['width']  = 492;
                          $sizer[1]['height'] = 184;
                          $sizer[2]['width']  = 492;
                          $sizer[2]['height'] = 184;
                          $sizer[3]['width']  = 492;
                          $sizer[3]['height'] = 184;
                          $sizer[4]['width']  = 492;
                          $sizer[4]['height'] = 184;
                        } else
                          if ($baner['template'] == 4) {
                            $sizer[1]['width']  = 741;
                            $sizer[1]['height'] = 374;
                            $sizer[2]['width']  = 243;
                            $sizer[2]['height'] = 374;
                          } else
                            if ($baner['template'] == 5) {
                              $sizer[1]['width']  = 243;
                              $sizer[1]['height'] = 374;
                              $sizer[2]['width']  = 243;
                              $sizer[2]['height'] = 374;
                              $sizer[3]['width']  = 243;
                              $sizer[3]['height'] = 374;
                              $sizer[4]['width']  = 243;
                              $sizer[4]['height'] = 374;
                            } else
                              if ($baner['template'] == 6) {
                                $sizer[1]['width']  = 243;
                                $sizer[1]['height'] = 374;
                                $sizer[2]['width']  = 741;
                                $sizer[2]['height'] = 374;
                              }


                    $tml = '<object width="' . $sizer[$item['position']]['width'] . '" height="' . $sizer[$item['position']]['height'] . '" type="application/x-shockwave-flash" data="' . $_GLOBAL['page_url'] . 'files/player.swf">
				<param name="movie" value="' . $_GLOBAL['page_url'] . 'files/player.swf" />
				<param name="flashvars" value="controlbar=over&amp;image=' . $_GLOBAL['page_url'] . 'files/baners/' . str_replace('mp4', 'jpg', $item['plik']) . '&amp;file=' . $_GLOBAL['page_url'] . 'files/baners/' . $item['plik'] . '" />
                                <embed src="' . $_GLOBAL['page_url'] . 'files/player.swf" FlashVars="controlbar=over&amp;image=' . $_GLOBAL['page_url'] . 'files/baners/' . str_replace('mp4', 'jpg', $item['plik']) . '&amp;file=' . $_GLOBAL['page_url'] . 'files/baners/' . $item['plik'] . '"  menu="false" scale="scale" wmode="transparent" quality="high" width="' . $sizer[$item['position']]['width'] . '" height="' . $sizer[$item['position']]['height'] . '" allowscriptaccess="sameDomain" type="application/x-shockwave-flash" pluginspage="http://www.macromedia.com/go/getflashplayer" />
                            </object>';
                  }
            $banertpl = str_replace('{BANER' . $item['position'] . '}', $tml, $banertpl);
          }
          $banerbottom = $banertpl;

          $tpl = str_replace('{BANERTOP}', $banertop, $tpl);
          $tpl = str_replace('{BANERBOTTOM}', $banerbottom, $tpl);
          $tpl = str_replace('{LOGOTYPES}', $logotypes, $tpl);

        } else redirect($_GLOBAL['page_url']);
        //redirect("sklep,1,witamy_w_gomez.htm");

//      $tpl = get_template("home",array());   
//      $A["{WSURL}"] = $T["st_url"];
//      if($T["st_url_tryb"]=="_blank") $A["{WSTARGET}"] = ' target="_blank"';
      } else {
        $tpl = get_template("cms", array());
      }

      if ($T["st_wiecej"] != "") {
        $W = explode("|^|", s($T["st_wiecej"]));
        foreach ($W as $val) {
          $B = explode("|=|", $val);
          $A["{WIECEJ}"] .= '<a href="' . $B[1] . ($B[2] == "_blank" ? ' rel="external"' : '') . '">' . $B[0] . '</a>';
        }
      } else {
        $DD[] = "IT_WIECEJ";
      }

      $Q[] = get_template($tpl, $A, $DD, 0);

      return join(T_JOIN, $Q);
    }

    /**
     * Lista stron z CMS-a
     *
     * @return string
     */

    function get_lista() {
      global $db, $Cuzytkownik;
      $Q[] = "st_st_id=" . $this->id;
      $Q[] = "st_widoczna='1'";

      $T     = $db->onerow("select count(*) from " . dn("cms") . " where " . join(" and ", $Q));
      $Cnawi = new nawigacja($T[0], $this->ppp, $_SERVER["REQUEST_URI"]);

      $q = "select st_id, st_st_id, st_tytul, st_url,st_url_tryb, st_typ,st_plik,st_wstep,st_uz_id,st_modul,st_data from " . dn("cms") . " ";
      $q .= "where " . join(" and ", $Q) . " ";
      $q .= "order by  st_pozycja desc, st_id desc ";
      $q .= "limit " . $Cnawi->get_min() . "," . $Cnawi->get_ppp();

      $nr = $db->query($q);
      $DD = array();
      $id = $this->id;

      $B["{TRESC}"]    = "";
      $B["{ADMINOPT}"] = "";

      if ($this->WYNIK["fo"]["st_tresc"] != "" and $this->WYNIK["fo"]["st_typ"] == "lista") {
        $B["{TRESC}"] .= s($this->WYNIK["fo"]["st_tresc"]);
      } else {
        $DD[] = "IT_TRESC";
      }

      if ($this->WYNIK["fo"]["st_html"] != "" and $this->WYNIK["fo"]["st_typ"] == "lista") $B["{TRESC_POST}"] = s($this->WYNIK["fo"]["st_html"]);

      if ($this->mode != "szukaj" and $Cuzytkownik->test_right("cms", $this->id)) {
        $B["{ADMINOPT}"] .= '<p><img src="' . $_GLOBAL['page_url'] . 'images/icon_addsub_txt_b.gif" class="imgbut" onclick="my_panel(\'strona\',\'' . TEMPLATE . '\',\'0,' . $this->id . '\');" alt="dodaj podstrone" title="dodaj podstrone" /></p>';
      }

      $tpl = get_template("cms_lista", array(), '');

      $tpl         = get_tag($tpl, "ITEM", $item);
      $l           = 0;
      $B["{ITEM}"] = "";

      while ($T = $db->fetch($nr)) {
        ++$l;
        $A["{URL}"]    = m_url("cms", $T["st_id"], s($T["st_tytul"]), $T["st_url"], $T["st_typ"], $T["st_modul"]) . $url;
        $A["{TARGET}"] = $T["st_url_tryb"] == "_blank" ? ' target="_blank"' : '';

        $A["{TYTUL}"] = strip_tags(s($T["st_tytul"]));
        $A["{WSTEP}"] = nl2br(strip_tags(s($T["st_wstep"])));

        if ($T["st_data"] != "" and $this->WYNIK["aktualnosc"]) $A["{DATA}"] = md(s($T["st_data"])); else $DD[] = "IT_DATA";

        if ($Cuzytkownik->test_right("cms", $T["st_id"]))
          $A["{OPT}"] = '<br/><img src="' . $_GLOBAL['page_url'] . 'images/icon_edit_txt.gif" onclick="my_panel(\'strona\',\'' . TEMPLATE . '\',\'' . $T["st_id"] . ',' . $this->id . ',0,0\');" class="imgbut" alt="edytuj strone" title="edytuj strone"/>';
        else $A["{OPT}"] = '';

        $B["{ITEM}"] .= get_template($item, $A, $DD, 0);
      }

      if (!$l and $this->mode == "szukaj") {
        $B["{ITEM}"] = '<br/><div class="center">Niestety nie znaleziono wpisów pasujących  do zapytania.</div>';
      }

      $B["{POZYCJE}"] = $Cnawi->get_pozycje();
      $B["{STRONY}"]  = $Cnawi->prn_strony();

      return get_template($tpl, $B, $DD, 0);
    }


    /**
     * Generuje moduł
     *
     * @param integer $id identyfikator
     *
     * @return string zawartość
     */

    function get_modul($id) {
      global $_GLOBAL;
      if (!file_exists("modules/" . $_GLOBAL["modul"][$id]["plik"] . ".php")) return 'Modul &quot;' . $_GLOBAL["modul"][$id]["nazwa"] . '&quot; niedostepny'; // . "modules/".$_GLOBAL["modul"][$id]["plik"].".php";
      include("modules/" . $_GLOBAL["modul"][$id]["plik"] . ".php");
      $Cobj                 = new $_GLOBAL["modul"][$id]["plik"];
      $ret                  = $Cobj->get_strona();
      $this->WYNIK["local"] = $Cobj->get_local();

      return $ret;
    }

    /**
     * Funkcja uzupełnia metatagi o dane bieżącej strony
     *
     * @param string $str Oryginalna wartość TAGu
     * @param string $typ Typ TAGu
     *
     * @return string Nowa wartość TAGu
     */
    function get_meta($str, $typ) {
      global $_GLOBAL;

      switch ($typ) {
        case "title":
          if ($this->id != 1) return $_GLOBAL["title"] . " - " . ((!empty($str)) ? $str . ' - ' : '') . $this->get_tytul(); else return $str;
        case "description":
          if ($this->id != 1) return $_GLOBAL["description"] . " - " . ((!empty($str)) ? $str . ' - ' : '') . $this->get_tytul(); else return $str;
      }

      return $str;
    }
  }

?>