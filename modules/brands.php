<?php if(!defined('SMDESIGN')) die();

/**
 * Klasa do obsługi strony Brands
 */

class brands extends mb {
    var $path = './files/brands_view/';
  function get_local() {
  global $Ccms;
    $R[] = $Ccms->get_tytul();
    return $R;
  }
  
    function itemBrandImg($id) {
        global $db, $_GLOBAL;

        $query = "select * from " . dn('brands') . " where id=" . $id . ";";
        $res = $db->query($query);
        $dane = $db->fetch($res);
        $out = '<img src="' . $_GLOBAL['page_url'] . 'files/brands/' . $dane['file'] . '" alt="'.$dane['name'].'" style="margin-top: 22px;">';// .
//                '../files/brands/' . $dane['file'] . '' .
//                print_r($dane,true) . 'aa<br>' . $query;// . $dane['file'];
        return $out;
    }
  
    function get_value($i,$j) {
        global $_POST, $_GET,$db,$_GLOBAL,$sURL;
        
        $out = '';

        $query = "select * from " . dn('brends_page_view') . " where x=" . (int)$i . " and y=" . (int)$j . ";";
        $res = $db->query($query);
        $dane = $db->fetch($res);

        if($dane['brand'] > 0)
        {
            $query = "select * from " . dn('brands') . " where id=" . (int)$dane['brand'] . ";";
            $res = $db->query($query);
            $bdane = $db->fetch($res);
            //echo print_r($bdane,true).'<br><br>';
            if(!empty($bdane['link']))
            {                                                                                                                                                      // &b=y' . ((isset($_GET['sale']))?'&sale=1':'') . '
                $brand = '<div class="box" style="text-align: center;vertical-align: middle"><a href="' . $_GLOBAL['page_url'] . $_GLOBAL['lang'] . '/' . $sURL[0] . '/' . $bdane['link'] . '">' . $this->itemBrandImg($dane['brand']) . '</a></div>';
            } else
            {
                $brand = '<div class="box" style="text-align: center;vertical-align: middle"><a href="">' . $this->itemBrandImg($dane['brand']) . '</div>';
            }
        } else
        {
//            $brand = $this->path . $dane['file'];
            $brand = '<div class="box stripped">';
            if($dane['type']=='jpg')
            {
                $brand .= '<img src="' . $_GLOBAL['page_url'] . '' . $this->path . $dane['file'] . '" alt="">';
            } else
            if($dane['type']=='swf')
            {
                $brand .= '<object classid="clsid:d27cdb6e-ae6d-11cf-96b8-444553540000" style="border: solid 0px red;" codebase="http://fpdownload.macromedia.com/pub/shockwave/cabs/flash/swflash.cab#version=8,0,0,0" width="155" height="105" id="flash_id" align="middle">
                                <param name="allowScriptAccess" value="sameDomain" />
                                <param name="movie" value="' . $_GLOBAL['page_url'] . $this->path . $dane['file'] . '" />
                                <param name="quality" value="high" />
                                <param name="wmode" value="transparent">
                                <param name="scale" value="scale" />
                                <param name="menu" value="false" />
                                <embed src="' . $_GLOBAL['page_url'] . $this->path . $dane['file'] . '" menu="false" scale="scale" wmode="transparent" quality="high" width="155" height="105" name="flash_id" align="middle" allowscriptaccess="sameDomain" type="application/x-shockwave-flash" pluginspage="http://www.macromedia.com/go/getflashplayer" />
                                </object>';// . $this->path . $dane['file'] . '';
            }else
            if($dane['type']=='flv')
            {
                $brand = '';// . $this->path . $dane['file'] . '';
            } else
            if($dane['type']=='mp4') {
                $brand .= '<object width="155" height="105" type="application/x-shockwave-flash" data="' . $_GLOBAL['page_url'] . 'files/player.swf">
				                <param name="movie" value="' . $_GLOBAL['page_url'] . 'files/player.swf" />
                                <param name="wmode" value="transparent">
				                <param name="flashvars" value="controlbar=over&amp;image=' . $_GLOBAL['page_url'] . $this->path . str_replace('mp4','jpg',$dane['file']) .'&amp;file=' . $_GLOBAL['page_url'] . $this->path . $dane['file'] . '" />
                                <embed src="' . $_GLOBAL['page_url'] . 'files/player.swf" FlashVars="controlbar=over&amp;image=' . $_GLOBAL['page_url'] . $this->path . str_replace('mp4','jpg',$dane['file']) .'&amp;file=' . $_GLOBAL['page_url'] . $this->path . $dane['file'] . '"  menu="false" scale="scale" wmode="transparent" quality="high" width="155" height="105" name="flash_id" align="middle" allowscriptaccess="sameDomain" type="application/x-shockwave-flash" pluginspage="http://www.macromedia.com/go/getflashplayer" />
                            </object>';
            }
            $brand .= '</div>';
        }
        $out = $brand;
        return $out;
  }
  
  function get_strona() {
  global $Ccms,$_GLOBAL,$db;
    $out = '<div id="brands-container">';
    
    for($i=1;$i<=7;$i++)
    {
        for($j=1;$j<=6;$j++)
        {
            $out .= '' . $this->get_value($i, $j) .  '';
        }
    }
    
    $out .= '</div><!-- brands-container -->

				<div class="spacer10"></div>';
    return $out;
  }
}

$val = '<link rel="stylesheet" type="text/css" media="all" href="' . $_GLOBAL['page_url'] . 'template/'.TEMPLATE.'/css/brands/styles.css" />';

get_head($val);

unset($_SESSION[SID]['shop']['brands']);
unset($_SESSION[SID]['shop']['kolor']);
unset($_SESSION[SID]['shop']['size']);
unset($_SESSION[SID]['shop']['faktura']);
unset($_SESSION[SID]['shop']['fason']);
unset($_SESSION[SID]['shop']['cat']);

?>