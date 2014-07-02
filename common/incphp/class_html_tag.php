<?php
/***********************************************/
/*                                             */
/*    Created by: Michał Bzowy                 */
/*          Date: 2004-07-01                   */
/*                                             */
/***********************************************/
if ( !defined('SMDESIGN') ) {
	die("Hacking attempt");
}

#-------------------------------------------------------------------------------
# CLASS HTML_TAG
#
# Zbiór metod do generowania kodu HTML elementów strony
#
# parameters   : 
#
#-------------------------------------------------------------------------------

class html_tag_b {
  var $EM;
#
# Liczniki otwar� poszczeg�lnych element�w
#
  var $m_cnt_table=0;
  var $m_cnt_tr=0;
  var $m_cnt_td=0;
  var $m_cnt_form=0;
#
# Zapis parametr�w aktualnie otwartych element�w
#
  var $m_ht_table;
  
#-------------------------------------------------------------------------------
# html_tag()
#
# Konstruktor obiektu
#
# parameters   : 
#
#-------------------------------------------------------------------------------
  function html_tag_b() {
    $this->EM = array(
      ":)"=>'smile.gif',
      ":("=>'sad.gif',
      ";)"=>'wink.gif',
      "8)"=>'cool.gif',
      ":D"=>'lol.gif',
      ":o"=>'surprised.gif',
      ":|"=>'neutral.gif',
      ":x"=>'mad.gif',
      ":arrow:"=>'arrow.gif',
      ":D"=>'biggrin.gif',
      ":?"=>'confused.gif',
      ":cry:"=>'cry.gif',
      ":shock:"=>'eek.gif',
      ":evil:"=>'evil.gif',
      ":!:"=>'exclaim.gif',
      ":idea:"=>'idea.gif',
      ":mrgreen:"=>'mrgreen.gif',
      ":?:"=>'question.gif',
      ":P"=>'razz.gif',
      ":oops:"=>'redface.gif',
      ":roll:"=>'rolleyes.gif',
      ":twisted:"=>'twisted.gif',
      ":muza: "=>'01.gif',
      ":dom: "=>'02.gif',
      ":kwiatek: "=>'03.gif',
      ":tee: "=>'04.gif',
      ":burza: "=>'05.gif',
      ":love: "=>'06.gif',
      ":randka: "=>'07.gif',
      ":amor: "=>'08.gif',
      ":dzyn: "=>'09.gif',
      ":beer: "=>'10.gif'
      );
  }
  
#-------------------------------------------------------------------------------
# table_op($border=0, $cellpadding=0, $cellspacing=0, $style="", $event="")
#
# Wy�wietla kod otwarcia tabeli
#
# parameters   : 
#
#-------------------------------------------------------------------------------
  function table_op($border="", $cellpadding="", $cellspacing="", $style="", $event="") {
    if ($style!="") $style = $this->resolve_style($style);
    if ($event!="") $event = " " . $event;
    if ($border!="" or $border=="0") $border = ' border="' . $border .'"';
    if ($cellpadding!="" or $cellpadding=="0") $cellpadding = ' cellpadding="' . $cellpadding .'"';
    if ($cellspacing!="" or $cellspacing=="0") $cellspacing = ' cellspacing="' . $cellspacing .'"';
    $ret = '<table'.$border.$cellpadding.$cellspacing. $style . $event.'>';
    $this->m_cnt_table++;
    $this->ht_table[$this->m_cnt_table] = array();
    return $ret;  
  }

#-------------------------------------------------------------------------------
# table_cl()
#
# Wy�wietla kod zamkni�cia tabeli
#
# parameters   : 
#
#-------------------------------------------------------------------------------
  function table_cl() {
    $ret = "";
    if(isset($this->m_ht_table[$this->m_cnt_table]))
      $open = $this->m_ht_table[$this->m_cnt_table];
    else $open = array();
    if (isset($open[1]) and $open[1]=="td") $ret .= "</td>\n";
    if (isset($open[1]) and $open[1]=="th") $ret .= "</th>\n";
    if (isset($open[0]) and $open[0]) $ret .= "</tr>\n";
    $ret .= "</table>\n";
    unset($this->m_ht_table[$this->m_cnt_table]);
    $this->m_cnt_table--;
    if ($this->m_cnt_table < 0 ) {print("Zbyt duzo zamkniec TABLE_CL<br>"); exit();}
    return $ret;
  }
#-------------------------------------------------------------------------------
# tr($style="")
#
# Wy�wietla kod wiersza
#
# parameters   : 
#
#-------------------------------------------------------------------------------
  function tr($style="", $event="") {
    $ret = "";
    if(isset($this->m_ht_table[$this->m_cnt_table]))
      $open = $this->m_ht_table[$this->m_cnt_table];
    else $open = array();
    if (isset($open[1]) and $open[1]=="td") $ret  = "</td>\n";
    if (isset($open[1]) and $open[1]=="th") $ret .= "</th>\n";
    if (isset($open[0]) and $open[0]) $ret .= "</tr>\n";
  
    if ($style!="") $style = $this->resolve_style($style);
    if ($event!="") $event = " ".$event;

    $ret .= "<tr" . $style . $event. ">";
    $this->m_ht_table[$this->m_cnt_table][0] = true;
    $this->m_ht_table[$this->m_cnt_table][1] = "";

    return $ret;
  }

#-------------------------------------------------------------------------------
# td($style="")
#
# Wy�wietla kod kom�rki
#
# parameters   : 
#
#-------------------------------------------------------------------------------
  function td($style="", $event="", $type="td") {
    $ret = "";
 
    if(isset($this->m_ht_table[$this->m_cnt_table])) $open = $this->m_ht_table[$this->m_cnt_table];
    if (!isset($open[0])) {
      $ret = "<tr>".chr(10);
      $this->m_ht_table[$this->m_cnt_table][0] = true;
    }
    if (isset($open[1]) and $open[1]=="td") $ret .= "</td>".chr(10);
    elseif (isset($open[1]) and $open[1]=="th") $ret .= "</th>".chr(10);
  
    if ($event!="") $event = " " . $event;
    if ($style != "") $style = $this->resolve_style($style);
  
    $ret .= "<".$type. $style . $event.">";
  
    $this->m_ht_table[$this->m_cnt_table][1] = $type;
    return $ret;
  }

  function th($style="",$action="") {
    return $this->td($style, $action, "th");
  }
#-------------------------------------------------------------------------------
# form_op($action, $name="", $method="POST", $enctype="", $js="")
#
# Wy�wietla kod otwarcia formularza
#
# parameters   : 
#
#-------------------------------------------------------------------------------
  function form_op($action="", $name="", $method="post", $enctype="", $js="", $target="") {
    if($action == "") $action = $_SERVER["PHP_SELF"];
    if($name != "") $name = ' id="'.$name.'" name="'.$name.'"';
    if($enctype != "") $enctype = ' enctype="'.$enctype.'"';  
    if($js != "") $js = " ".$js;
    if($target != "") $target = ' target="'.$target.'"';
    $this->m_cnt_form++;
    return '<form action="'.$action.'"'. $name .' method="'.$method.'"'. $enctype . $js . $target . '>';
  }
  
#-------------------------------------------------------------------------------
# form_cl()
#
# Wy�wietla kod zamkni�cia formularza
#
# parameters   : 
#
#-------------------------------------------------------------------------------
  function form_cl() {
    $ret = "</form>";
    $this->m_cnt_form--;
    if ($this->m_cnt_form < 0 ) {print("Zbyt duzo zamkniec FORM_CL<br>"); exit();}
    return $ret;
  }

  function select_html($name, $arr_option, $selected=-1, $style="", $first="N", $first_text="", $js="") {
    $ret = "";
    if($style != "") $style = $this->resolve_style($style);
    if($js != "") $js = " ".$js;
  
    $ret .= '<select name="'. $name .'"' . $style . $js .'>';
    if ($first=="Y") $ret .= $this->option(-1, $first_text, $selected);
    if(is_array($arr_option))
      foreach ($arr_option as $value=>$name) {
        $ret .= $this->option($value, $name, $selected);
    }
    $ret .= "</select>\n";
    return $ret;
  }
  
  function option($value, $name, $selected=-1) {
    $ret = '<option value="' . ($value=="0"?0:h($value)) . '"';
    if ($value == $selected) $ret .= ' selected';
    $ret .= ">" . s($name) . "</option>\n";
    return $ret;
  }
  
  function select_html_multi($name, $arr_option, $selected=-1, $style="", $first="N", $first_text="", $con="<br/>", $js="") {
    if($style != "") $style = $this->resolve_style($style);
    if($js != "") $js = " ".$js;
    if (is_array($selected)) $selected_multi = array_count_values($selected);
    else $selected_multi=array();
  
    $ret = '<select name="' . $name . '[]" multiple' . $style . $js .'>';
    if ($first=="Y") $this->option_multi(-1, $first_text, $selected_multi['-1']);
    if(is_array($arr_option))
      foreach ($arr_option as $value=>$name) {
        $ret .= $this->option_multi($value, $name, isset($selected_multi[$value])?$selected_multi[$value]:0);
    }
    $ret .= '</select>';
    return $ret;
  }
  
  function option_multi($value, $name, $selected) {
    $ret = '<option value="'.h($value).'"';
    if ($selected>0) $ret .= " selected";
    $ret .= '>' . s($name) . '</option>';
    return $ret;
  }
  
  function textarea($name, $value, $style="", $cols="", $rows="",$js="" ) {
    if($cols != "")  $cols  =' cols="'.$cols.'"'; 
    if($rows != "")  $rows  =' rows="'.$rows.'"';
    if($style != "") $style = $this->resolve_style($style);
    if($js != "") $js = " ".$js;
    $ret = '<textarea'.$style.$cols.$rows.$js.' name="'. $name .'" id="'.$name.'">' . h($value, false) . '</textarea>'.chr(10);
    return $ret;
  }
  /*Trzeba doda� bbcode.js i bbcode.css, smiles images/smiles */
  function textareabb($value, $width="", $height="", $smile=18) {
    $EM = $this->EM;
  
    $Q[] = $this->table_op("","","",".bbcode");
      $Q[] = $this->tr();
        $Q[] = $this->td(); $Q[] = $this->submit("addbbcode0","B","s.fw.bold .bbinput","button",'onClick="bbstyle(0)" onMouseOver="helpline(\'b\')"',"b");
        $Q[] = $this->td(); $Q[] = $this->submit("addbbcode4","U","s.td.underline .bbinput","button",'onClick="bbstyle(4)" onMouseOver="helpline(\'u\')"',"u");
        $Q[] = $this->td(); $Q[] = $this->submit("addbbcode2","I","s.fs.italic .bbinput","button",'onClick="bbstyle(2)" onMouseOver="helpline(\'i\')"',"i");
        $Q[] = $this->td(); $Q[] = $this->submit("addbbcode6","Cytat",".bbinput2","button",'onClick="bbstyle(6)" onMouseOver="helpline(\'q\')"',"q");
        $Q[] = $this->td(); $Q[] = $this->submit("addbbcode8","Kod",".bbinput2","button",'onClick="bbstyle(8)" onMouseOver="helpline(\'c\')"',"c");
        $Q[] = $this->td(); $Q[] = $this->submit("addbbcode14","IMG",".bbinput2","button",'onClick="bbstyle(14)" onMouseOver="helpline(\'p\')"',"p");
        $Q[] = $this->td(); $Q[] = $this->submit("addbbcode16","URL",".bbinput2","button",'onClick="bbstyle(16)" onMouseOver="helpline(\'w\')"',"w");
        $Q[] = $this->td();
        $Q[] = '<select name="addbbcode18" onChange="bbfontstyle(\'[color=\' + this.form.addbbcode18.options[this.form.addbbcode18.selectedIndex].value + \']\', \'[/color]\');this.selectedIndex=0;" onMouseOver="helpline(\'s\')" class="bbinput">
    					  <option style="color:black;" value="#444444">Kolor</option>
    					  <option style="color:darkred;" value="darkred">Ciemnoczerwony</option>
    					  <option style="color:red;" value="red">Czerwony</option>
    
    					  <option style="color:orange;" value="orange">Pomara�czowy</option>
    					  <option style="color:brown;" value="brown">Br�zowy</option>
    					  <option style="color:yellow;" value="yellow">��ty</option>
    					  <option style="color:green;" value="green">Zielony</option>
    					  <option style="color:olive;" value="olive">Oliwkowy</option>
    					  <option style="color:cyan;" value="cyan">B��kitny</option>
    
    					  <option style="color:blue;" value="blue">Niebieski</option>
    					  <option style="color:darkblue;" value="darkblue">Ciemnoniebieski</option>
    					  <option style="color:indigo;" value="indigo">Purpurowy</option>
    					  <option style="color:violet;" value="violet">Fioletowy</option>
    					  <option style="color:white;" value="white">Bia�y</option>
    					  <option style="color:black;" value="black">Czarny</option>
    
    					</select>';
        $Q[] = $this->td();
        $Q[] = '<a href="javascript:bbstyle(-1)" onMouseOver="helpline(\'a\')">Zamknij Tagi</a>';
        
     $Q[] = $this->table_cl();
    
    
    $Q[] = $this->textarea("message",s($value),".txt s.w.".$width."px s.h.".$height."px","edit","","",'onselect="storeCaret(this);" onclick="storeCaret(this);" onkeyup="storeCaret(this);"');
    $Q[] = $this->div("#bbsmiles");
    $i=0;
    foreach($EM as $key=>$val) {
      $Q[] = '<a href="javascript:;" onclick="emoticon(\''.$key.'\');"><img src="images/smiles/icon_'.$val.'" alt="'.$key.'"/></a>';
      if(++$i%$smile==0)$Q[] = '<br/>';
    }
    $Q[] = $this->div();
    $Q[] = $this->div("#bbhelp")."".$this->div();
    onload("helpline('x');");
    return join($Q);
  }
  function get_emot($str) {
  global $_GLOBAL;
    $EM = array(
      ":arrow:"=>'<img src="'.$_GLOBAL["page_url"].'/images/smiles/icon_arrow.gif" alt="strza�a" class="emot" />',
      ":D"=>'<img src="'.$_GLOBAL["page_url"].'/images/smiles/icon_biggrin.gif" alt="weso�y" class="emot" />',
      ":?"=>'<img src="'.$_GLOBAL["page_url"].'/images/smiles/icon_confused.gif" alt="zmieszany" class="emot" />',
      "8)"=>'<img src="'.$_GLOBAL["page_url"].'/images/smiles/icon_cool.gif" alt="cool" class="emot" />',
      ":cry:"=>'<img src="'.$_GLOBAL["page_url"].'/images/smiles/icon_cry.gif" alt="p�acz" class="emot" />',
      ":shock:"=>'<img src="'.$_GLOBAL["page_url"].'/images/smiles/icon_eek.gif" alt="zaskoczony" class="emot" />',
      ":evil:"=>'<img src="'.$_GLOBAL["page_url"].'/images/smiles/icon_evil.gif" alt="diabe�" class="emot" />',
      ":!:"=>'<img src="'.$_GLOBAL["page_url"].'/images/smiles/icon_exclaim.gif" alt="wa�ne" class="emot" />',
      ":idea:"=>'<img src="'.$_GLOBAL["page_url"].'/images/smiles/icon_idea.gif" alt="pomys�" class="emot" />',
      ":lol:"=>'<img src="'.$_GLOBAL["page_url"].'/images/smiles/icon_lol.gif" alt="" class="emot" />',
      ":x"=>'<img src="'.$_GLOBAL["page_url"].'/images/smiles/icon_mad.gif" alt="w�ciek�y" class="emot" />',
      ":mrgreen:"=>'<img src="'.$_GLOBAL["page_url"].'/images/smiles/icon_mrgreen.gif" alt="" class="emot" />',
      ":|"=>'<img src="'.$_GLOBAL["page_url"].'/images/smiles/icon_neutral.gif" alt="oboj�tny" class="emot" />',
      ":?:"=>'<img src="'.$_GLOBAL["page_url"].'/images/smiles/icon_question.gif" alt="pytanie" class="emot" />',
      ":P"=>'<img src="'.$_GLOBAL["page_url"].'/images/smiles/icon_razz.gif" alt="" class="emot" />',
      ":oops:"=>'<img src="'.$_GLOBAL["page_url"].'/images/smiles/icon_redface.gif" alt="zawstydzony" class="emot" />',
      ":roll:"=>'<img src="'.$_GLOBAL["page_url"].'/images/smiles/icon_rolleyes.gif" alt="zakr�cony" class="emot" />',
      ":("=>'<img src="'.$_GLOBAL["page_url"].'/images/smiles/icon_sad.gif" alt="smutny" class="emot" />',
      ":)"=>'<img src="'.$_GLOBAL["page_url"].'/images/smiles/icon_smile.gif" alt="weso�y" class="emot" />',
      ":o"=>'<img src="'.$_GLOBAL["page_url"].'/images/smiles/icon_surprised.gif" alt="zaskoczony" class="emot" />',
      ":twisted:"=>'<img src="'.$_GLOBAL["page_url"].'/images/smiles/icon_twisted.gif" alt="" class="emot" />',
      ";)"=>'<img src="'.$_GLOBAL["page_url"].'/images/smiles/icon_wink.gif" alt="nie na serio" class="emot" />',

      ":muza:"=>'<img src="'.$_GLOBAL["page_url"].'/images/smiles/icon_01.gif" alt="muza" class="emot" />',
      ":dom:"=>'<img src="'.$_GLOBAL["page_url"].'/images/smiles/icon_02.gif" alt="dom" class="emot" />',
      ":kwiatek:"=>'<img src="'.$_GLOBAL["page_url"].'/images/smiles/icon_03.gif" alt="kwiatek" class="emot" />',
      ":tee:"=>'<img src="'.$_GLOBAL["page_url"].'/images/smiles/icon_04.gif" alt="tee" class="emot" />',
      ":burza:"=>'<img src="'.$_GLOBAL["page_url"].'/images/smiles/icon_05.gif" alt="burza" class="emot" />',
      ":love:"=>'<img src="'.$_GLOBAL["page_url"].'/images/smiles/icon_06.gif" alt="love" class="emot" />',
      ":randka:"=>'<img src="'.$_GLOBAL["page_url"].'/images/smiles/icon_07.gif" alt="randka" class="emot" />',
      ":amor:"=>'<img src="'.$_GLOBAL["page_url"].'/images/smiles/icon_08.gif" alt="amor" class="emot" />',
      ":dzyn:"=>'<img src="'.$_GLOBAL["page_url"].'/images/smiles/icon_09.gif" alt="dzyn" class="emot" />',
      ":beer:"=>'<img src="'.$_GLOBAL["page_url"].'/images/smiles/icon_10.gif" alt="beer" class="emot" />'
      
      
      
      );
    return strtr($str,$EM);
  }
  function bbcode2html ($str,$plik1="",$plik2="",$maxwidth=0) {
  global $_GLOBAL;
    $str=htmlspecialchars(trim($str));
    $str = $this->get_emot($str);

    $IN = array (
      "#\[b\](.*?)\[/b\]#si",
      "#\[i\](.*?)\[/i\]#si",
      "#\[u\](.*?)\[/u\]#si",
      "#\[quote\](.*?)\[/quote]#si",
      "#\[quote=(http://)?(.*?)\](.*?)\[/quote]#si",
      "#\[code\](.*?)\[/code]#si",
      "#\[color=(http://)?(.*?)\](.*?)\[/color\]#si",
      '/(\[url\])(.+)(\[\/url\])/',
      '/(\[url=)(.+)(\])(.+)(\[\/url\])/'
    );
    
    $OUT = array (
      '<strong>\\1</strong>',
      '<em>\\1</em>',
      '<underline>\\1</underline>',
      "<p class=\"bbnapisal\">cytat:</p><p class=\"bbcytat\">\\1</p>",
      "<p class=\"bbnapisal\">\\2 napisa�:</p><p class=\"bbcytat\">\\3</p>",
      "<code/>\\1</code>",
      "<span style=\"color:\\2\">\\3</span>",
      '<a href="\\2" target="_blank">\\2</a>',
      '<a href="\\2" target="_blank">\\4</a>'
    );
    if($maxwidth!=0) {
      preg_match_all('#\[img\](.*?)\[/img\]#si',$str,$MATCH);
      for ($i=0; $i< count($MATCH[0]); $i++) {
        $err = 0;
        if(!(list($x, $y)=@GetImageSize ($MATCH[1][$i]))) {
//          $str = str_replace($MATCH[0][$i],"",$str);
//          continue;
          $err = 1;
        }
        if($x>$maxwidth or $err) {
          $str = str_replace($MATCH[0][$i],"<img src=\"".$MATCH[1][$i]."\" border=\"0\" alt=\"\" border=\"0\" class=\"imgbbcode\" style=\"width:".$maxwidth."px;\"/>",$str);
        } else {
          $str = str_replace($MATCH[0][$i],"<img src=\"".$MATCH[1][$i]."\" border=\"0\" alt=\"\" border=\"0\" class=\"imgbbcode\"/>",$str);
        }
      }
      
    } else {
      $IN[] = "#\[img\](.*?)\[/img\]#si";
      $OUT[] = "<img src=\"\\1\" border=\"0\" alt=\"\" border=\"0\" class=\"imgbbcode\"/>";
      
    }

    $str = preg_replace($IN, $OUT, $str);
    $str = str_replace('&amp;plusmn;', '&plusmn;', $str);
    $str = str_replace('&amp;trade;', '&trade;', $str);
    $str = str_replace('&amp;bull;', '&bull;', $str);
    $str = str_replace('&amp;deg;', '&deg;', $str);
    $str = str_replace('&amp;copy;', '&copy;', $str);
    $str = str_replace('&amp;reg;', '&reg;', $str);
    $str = str_replace('&amp;hellip;', '&hellip;', $str);
    
    
    
    
    // b��dne kodowanie m.in. z phpmyadmina
    $str = str_replace('&amp;#261;', '�', $str);
    $str = str_replace('&amp;#263;', '�', $str);
    $str = str_replace('&amp;#281;', '�', $str);
    $str = str_replace('&amp;#322;', '�', $str);
    $str = str_replace('&amp;#347;', '�', $str);
    $str = str_replace('&amp;#378;', '�', $str);
    $str = str_replace('&amp;#380;', '�', $str);
    
    // znaki specjalne z m$ word
    $str = str_replace('&amp;#177;', '�', $str);
    $str = str_replace('&amp;#8217;', '\'', $str);
    $str = str_replace('&amp;#8222;', '"', $str);
    $str = str_replace('&amp;#8221;', '"', $str);
    $str = str_replace('&amp;#8220;', '"', $str);
    $str = str_replace('&amp;#8211;', '-', $str);
    $str = str_replace('&amp;#8230;', '&hellip;', $str);

    debug($plik1);
    if($plik1!="") $str = str_replace("[img]",'<img src="'.$plik1.'" alt="" class="imgbbcode" border="0"/>',$str);
    if($plik2!="") $str = str_replace("[att]",'<a href="'.$plik2.'" target="_blank">[PLIK]</a>',$str);
    return nl2br($str);
  }
  function filefield($name, $style="",$js="") {
    if($js != "") $js = " ".$js;
    if($style != "") $style= " ".$this->resolve_style($style);
  
    $ret = '<input type="file" name="'.$name.'"'.$style.$js.' />';
    return $ret;
  }
  
  function text($name, $value, $style="", $js="", $type="text",$maxlength="") {
    if($style != "") $style = $this->resolve_style($style);
    if($js != "") $js = " ".$js;
    if($maxlength != "") $maxlength = ' maxlength="'.$maxlength.'"';
    $ret = '<input type="'.$type.'" name="'. $name .'" value="' . h($value, false) . '"'. $maxlength . $style . $js . ' />';
    return $ret;
  }
  
  function checkbox($name, $checked="",$event="", $style="") {
    if ($style != "") $style = $this->resolve_style($style);
    $ret = '<input type="checkbox" name="'. $name .'" id="'. $name .'"';
    if ($checked == "true" || $checked == 'T' || $checked == "on" || $checked == "y") $ret .= " checked";
    if ($event) $ret .= " ".$event;
    $ret .= $style." />\n";
    return $ret;
  }
  
  function radio($name, $value, $checked=-1, $style="",$js="") {
    if ($style != "") $style = $this->resolve_style($style);
    else $style = ' class="'.$name.'" id="'.$name.$value.'"';
    if($js != "") $js = " ".$js;
    $ret = '<input type="radio" name="'. $name .'" value="'. $value .'" '.$style;
    if ($value == $checked) $ret .= " checked";
    $ret .= $js." />\n";
    return $ret;
  }
  
  function p($style="",$js = "") {
    if($js != "") $js = " ".$js;
    if($style == "0") return '<p' . $js . '>';
    if($style != "") return '<p' . $this->resolve_style($style) . $js . '>';
    return '</p>';
  }
  
  function div($style="", $js="") {
    if($js != "") $js = " ".$js;
    if($style == "0")  return '<div' . $js .'>';
    if($style != "") return '<div' . $this->resolve_style($style) . $js .'>';
    
    return '</div>';
  }
  
  function span($style="", $js="") {
    if($js != "") $js = " ".$js;
    if($style == "0") return '<span' . $js . '>';
    if($style != "") return '<span' . $this->resolve_style($style) . $js . '>';
    return '</span>';
  }

#-------------------------------------------------------------------------------
# hidden($name, $value) 
#
# Hidden field of form 
#
# parameters  : $name - name of the hidden field
#               $value - value of the hidden field 
#
#-------------------------------------------------------------------------------
  function hidden($name, $value, $style="",$action="") {
    if($action != "") $action = " ".$action;
    if($style != "") $style = $this->resolve_style($style);
    return '<input type="hidden" name="' . h($name) . '" value="'. $value .'" '.$style.$action.'/>';
  }
  
  function submit($name, $value, $style="", $type="submit", $event="", $key="") {
    if ($style != "") $style = $this->resolve_style($style);
    if ($event!="") $event = " " . $event;
    if ($key != "") $key = ' accesskey="'.$key.'"';
    if ($type == "image") $value = ' src="'.$value.'"';
    else $value = ' value="'.$value.'"';
  
    return '<input name="'. $name .'"'. $value . $style . ' type="'. $type .'"'. $event.$key. '/>';
  }

  function img($img="none", $width="", $height="", $alt="", $name="",$align="",$style="",$border=0) {
    if($width != "") $width = ' width="'.$width.'"';
    if($height != "") $height = ' height="'.$height.'"';
    if($name != "") $name = ' name="'.$name.'"';
    if($align == "") $align = "";
    if($align != "") $align = ' align="'.$align.'"';
    if($style != "") $style = $this->resolve_style($style);
    if($alt == "") $alt = $img;  
    
    $path = "";
    return '<img src="' . $img . '" border="'.$border.'" '.$width.$height.$name.$align.$style.' alt="'.$alt.'" />';
  }
  
#-------------------------------------------------------------------------------
# br($number=1) 
#
# Writes a <br> tab.
#
# parameters   : $number - number of <br> tags, default one tag
#
#-------------------------------------------------------------------------------
  function br($number=1) { 
    $ret = "";
    for($i=0; $i<$number; $i++) $ret .= "<br />";
    return $ret;
  }

#-------------------------------------------------------------------------------
# nbsp($number=1)  
#
#  Write a &nbsp special chcaracter
#
# parameters   : $number - number of &nbsp's, default one tag
#
#-------------------------------------------------------------------------------
  function nbsp($number=1) { 
    $ret = "";
    for($i=0; $i<$number; $i++) $ret .= "&nbsp;";
    return $ret;
  }
  
  function resolve_style($string) {
  
    $arr_style = explode(" ", $string);
    $arr_result = array();
    foreach ($arr_style as $value) {
      if ($value == "") continue;
      if ($value == "left" || $value == "right" || $value == "center" || $value == "justify") 
        $arr_result["style"][] = 'text-align:'.$value.';';
      elseif ($value == "top" || $value == "middle" || $value == "bottom" || $value == "baseline") 
        $arr_result["style"][] = 'vertical-align:'.$value.';';
      elseif (substr($value, 0, 1) == ".")
        $arr_result["class"] = 'class="' . substr($value,1) . '"';
      elseif (substr($value, 0, 1) == "#")
        $arr_result["id"] = "id='" . substr($value,1) . "'";
      elseif (substr($value, 0, 2) == "s.") {
        $arr_style = explode('.', substr($value, 2));
        $st=$arr_style[0];
        unset($arr_style[0]);
        $arr_result["style"][] = $this->property($st) . ":" . str_replace("|", " ", join(".",$arr_style)) . ";";
      }  
      elseif ($value == "disabled") 
        $arr_result["disabled"] = 'disabled="'.$value.'"';
      elseif (substr($value, 0, 4) == "url(") 
        $arr_result["style"][] = "background-image:".$value.";";
      elseif (substr($value, 0, 3) == "id.") 
        $arr_result["id"] = 'id="'.substr($value,3).'"';
      elseif (substr($value, 0, 3) == "al.") 
        $arr_result["align"] = 'align="'.substr($value,3).'"';
      elseif (substr($value, 0, 2) == "c.") 
        $arr_result["colspan"] =
         "colspan='" . substr($value,2) . "'";
      elseif (substr($value, 0, 2) == "r.") 
        $arr_result["rowspan"] = "rowspan='" . substr($value,2) . "'";
      else {print("Nieznany parametr RESOLVE_STYLE: $value<br>"); exit();}
    }
    if(isset($arr_result["style"]) and is_array($arr_result["style"])) $arr_result["style"] = 'style="' . join(" ",$arr_result["style"]) . '"';
    return " ".join(" ", $arr_result);
  
  }
  
  function property($string) { 
  
    $SHORT = array ("bg","bga","bgc","bgi",
                    "bgp","bgr","br","brb","brbw",
                    "brc","brl","brlw","brr","brrw",
                    "brs","brt","brtw","brw","c","clr",
                    "dp","f","ff","flo","fs","fst","fv",
                    "fw","h","lh","ls","lsi","lsp",
                    "lst","lts","m","mb","ml",
                    "mr","mt","p","pb","pl","pr",
                    "pt","ta","td","ti","tt",
                    "va","w","whs","ws",
                    "pbra","pbrb","cu","di",
                    "lf","rt","t","po");
  
    $FULL = array ( "background","background-attachment","background-color","background-image",
                    "background-position","background-repeat","border","border-bottom","border-bottom-width",
                    "border-color","border-left","border-left-width","border-right","border-right-width",
                    "border-style","border-top","border-top-width","border-width","color","clear",
                    "display","font","font-family","float","font-size","font-style","font-variant",
                    "font-weight","height","line-height","list-style","list-style-image","list-style-position",
                    "list-style-type","letter-spacing","margin","margin-bottom","margin-left",
                    "margin-right","margin-top","padding","padding-bottom","padding-left","padding-right",
                    "padding-top","text-align","text-decoration","text-indent","text-transform",
                    "vertical-align","width","white-space","word-spacing",
                    "page-break-after","page-break-before","cursor","display",
                    "left", "right", "top","position");
  
    foreach ($SHORT as $key=>$value) {
      if ($value==$string) return $FULL[$key];
    }
  
    return $string;
  
  }
  function prn_hidden($TAB) {
    if(!is_array($TAB)) return;
    
    foreach($TAB as $key=>$val) $ret .= $this->hidden($key,$val);
    return $ret;
  }

  function iframe($src,$w="100%",$h="100%",$id="edit",$mw=0,$mh=0,$fb=0) {
    return '<iframe id="'.$id.'" src="'.$src.'" width="'.$w.'" height="'.$h.'" marginwidth="'.$mw.'" marginheight="'.$mh.'" frameborder="'.$fb.'"></iframe>';  
  }
}

$b = new html_tag_b();
?>