<?php if(!defined('SMDESIGN')) die();

/**
 * @author Maciej Szczachor
 */


class gomez_club extends mb {
    var $path = 'files/gomez_club/';
    var $value = array();
    
  function get_local() {
  global $Ccms;
    $R[] = $Ccms->get_tytul();
    return $R;
  }
  
  function get_strona() {
  //global $Ccms,$_GLOBAL,$db;
    global $_GLOBAL,$L,$db;
    
    $out = '<div id="gc-main-container"><div id="gc-container">';
    $lp=1;
    for($i=1;$i<=3;$i++)
    {
        for($j=1;$j<=5;$j++)
        {
            $query = "select * from " . dn('gomez_club') . " 
                      where x=" . ($j-1) . " and y=" . ($i-1) . " and langid=" . $_GLOBAL['langid'] . ";";
            $res = $db->query($query);
            $dane = $db->fetch($res);
//            echo print_r($dane,true) . '<br>';
            if($lp==12)
            {
                $out .= '<a href="' . $_GLOBAL['page_url'] . $_GLOBAL['lang'] . '/uzytkownik/">
                            <div class="box_gc" style="background-image:url(' . $_GLOBAL['page_url'] . $this->path . $dane['file'] . ')">' . ((!empty($dane['text']))?'<div class="gctext">' . $dane['text'] . '</div>':'') . '</div>
                         </a><!-- box -->';
            } else
            if($lp==8)
            {
                $out .= '<a href="javascript:gomezclublog();"><div class="box_gc" style="background-image:url(' . $_GLOBAL['page_url'] . $this->path . $dane['file'] . ')">' . ((!empty($dane['text']))?'<div class="gctext">' . $dane['text'] . '</div>':'') . '</div></a><!-- box -->';
            } else 
            {
                $out .= '<div class="box_gc" style="background-image:url(' . $_GLOBAL['page_url'] . $this->path . $dane['file'] . ')">' . ((!empty($dane['text']))?'<div class="gctext">' . $dane['text'] . '</div>':'') . '</div><!-- box -->';
            }
            $lp++;
            
        }
    }
    
    $out .= '</div><!-- brands-container -->';
    $out .= '<div id="gc-container-log">
                <div id="gc-karta">
                    <div id="gc-form">
                        <form method="post" id="login-form">
                            <input type="hidden" name="a" value="l" />
                            <div id="gc-login-input-container"><input id="login-input" type="text" name="lo_login" value=" login... " onfocus="if(this.value==\' login... \')this.value=\'\'" onblur="if(this.value==\'\')this.value=\' login... \'" /></div>
                            <div id="gc-password-input-container"><input id="passowrd-input" type="password" name="lo_haslo" value="******" onfocus="if(this.value==\'******\')this.value=\'\'"  onblur="if(this.value==\'\')this.value=\'******\'" /></div>
                            <a class="submit" href="javascript:userminilogin();"><div id="gc-submit-pretender"></div></a>
                        </form>
                    </div>
                    <div id="gc-forget-container">
                        <a href="' . $_GLOBAL['page_url'] . $_GLOBAL['lang'] . '/uzytkownik/haslo/">' . $L['{T_ZAPOMNIALEM_HASLA}'] . '</a> / <a href="' . $_GLOBAL['page_url'] . $_GLOBAL['lang'] . '/uzytkownik/">' . $L['{T_ZAREJESTRUJ_SIE}'] . '</a>
                    </div>
                </div>                
            </div>';
    
    $out .= '<div class="spacer10"></div></div>';
    return $out;
  }
}

?>