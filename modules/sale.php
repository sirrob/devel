<?php if(!defined('SMDESIGN')) die();
/**
 * Strona z SALE
 * 
 * Obsługa strony SALE w gomez.pl
 * 
 * @author Maciej Szczachor
 * @copyright Copyright (c) 2012, Maciej Szczachor
 * @since 2012-03-27 14:02:00
 * @link karmac.pl
 */


class sale extends mb {
    var $path = './files/brands_view/';
    function get_local() {
        global $Ccms;
        $R[] = $Ccms->get_tytul();
        return $R;
    }
  
    function get_baner($id)
    {
        global $db,$_GLOBAL;
        $query = "select * from " . dn('baners_big') . " where page=" . (int)$id . " and langid = ".$_GLOBAL['langid'].";";
        $re = $db->query($query);
        $baner = $db->fetch($re);

      
            $banertpl = my_fread('files/prototyp/baner_big_' . $baner['template'] . '.tpl');
                
            $query = "select * from " . dn('baners_big_files') . " where baners=" . $baner['id'] . " and langid = ".$_GLOBAL['langid'].";";
            $re = $db->query($query);
            while($item = $db->fetch($re))
            {
                //echo $banertpl . '<br>';
                $tml = '';
                if($item['type']=='jpg')
                {
                    if(!empty($item['link']))
                    {
                        $tml = '<a href="' . $item['link'] . '"><img src="' . $_GLOBAL['page_url'] . 'files/baners_big/' . $item['plik'] . '" alt=""></a>';
                    } else
                    {
                        $tml = '<img src="' . $_GLOBAL['page_url'] . 'files/baners_big/' . $item['plik'] . '" alt="">';
                    }
                } else
                if($item['type']=='swf')
                {
                    if($baner['template']==1)
                    {
                        $sizer[1]['width'] = 504;
                        $sizer[1]['height'] = 184;
                        $sizer[2]['width'] = 250;
                        $sizer[2]['height'] = 184;
                    } else
                    if($baner['template']==2)
                    {
                        $sizer[1]['width'] = 504;
                        $sizer[1]['height'] = 184;
                        $sizer[2]['width'] = 250;
                        $sizer[2]['height'] = 184;
                        $sizer[3]['width'] = 504;
                        $sizer[3]['height'] = 184;
                        $sizer[4]['width'] = 250;
                        $sizer[4]['height'] = 184;
                    } else
                    if($baner['template']==3)
                    {
                        $sizer[1]['width'] = 504;
                        $sizer[1]['height'] = 184;
                        $sizer[2]['width'] = 250;
                        $sizer[2]['height'] = 184;
                        $sizer[3]['width'] = 504;
                        $sizer[3]['height'] = 184;
                        $sizer[4]['width'] = 250;
                        $sizer[4]['height'] = 184;
                        $sizer[5]['width'] = 504;
                        $sizer[5]['height'] = 184;
                        $sizer[6]['width'] = 250;
                        $sizer[6]['height'] = 184;
                    } else
                    if($baner['template']==4)
                    {
                        $sizer[1]['width'] = 504;
                        $sizer[1]['height'] = 184;
                        $sizer[2]['width'] = 250;
                        $sizer[2]['height'] = 184;
                        $sizer[3]['width'] = 504;
                        $sizer[3]['height'] = 184;
                        $sizer[4]['width'] = 250;
                        $sizer[4]['height'] = 184;
                        $sizer[5]['width'] = 504;
                        $sizer[5]['height'] = 184;
                        $sizer[6]['width'] = 250;
                        $sizer[6]['height'] = 184;
                        $sizer[7]['width'] = 504;
                        $sizer[7]['height'] = 184;
                        $sizer[8]['width'] = 250;
                        $sizer[8]['height'] = 184;
                    } else
                    if($baner['template']==5)
                    {
                        $sizer[1]['width'] = 760;
                        $sizer[1]['height'] = 184;
                    } else
                    if($baner['template']==6)
                    {
                        $sizer[1]['width'] = 760;
                        $sizer[1]['height'] = 184;
                        $sizer[2]['width'] = 760;
                        $sizer[2]['height'] = 184;
                    } else
                    if($baner['template']==7)
                    {
                        $sizer[1]['width'] = 760;
                        $sizer[1]['height'] = 184;
                        $sizer[2]['width'] = 760;
                        $sizer[2]['height'] = 184;
                        $sizer[3]['width'] = 760;
                        $sizer[3]['height'] = 184;
                    } else
                    if($baner['template']==8)
                    {
                        $sizer[1]['width'] = 760;
                        $sizer[1]['height'] = 184;
                        $sizer[2]['width'] = 760;
                        $sizer[2]['height'] = 184;
                        $sizer[3]['width'] = 760;
                        $sizer[3]['height'] = 184;
                        $sizer[4]['width'] = 760;
                        $sizer[4]['height'] = 184;
                    } else
                    if($baner['template']==9)
                    {
                        $sizer[1]['width'] = 760;
                        $sizer[1]['height'] = 380;
                        $sizer[2]['width'] = 377;
                        $sizer[2]['height'] = 184;
                        $sizer[3]['width'] = 377;
                        $sizer[3]['height'] = 184;
                    } else
                    if($baner['template']==10)
                    {
                        $sizer[1]['width'] = 760;
                        $sizer[1]['height'] = 380;
                        $sizer[2]['width'] = 377;
                        $sizer[2]['height'] = 184;
                        $sizer[3]['width'] = 377;
                        $sizer[3]['height'] = 184;
                        $sizer[4]['width'] = 377;
                        $sizer[4]['height'] = 184;
                        $sizer[5]['width'] = 377;
                        $sizer[5]['height'] = 184;
                    } else
                    if($baner['template']==11)
                    {
                        $sizer[1]['width'] = 377;
                        $sizer[1]['height'] = 573;
                        $sizer[2]['width'] = 377;
                        $sizer[2]['height'] = 573;
                        $sizer[3]['width'] = 377;
                        $sizer[3]['height'] = 184;
                        $sizer[4]['width'] = 377;
                        $sizer[4]['height'] = 184;
                    } else
                    if($baner['template']==12)
                    {
                        $sizer[1]['width'] = 760;
                        $sizer[1]['height'] = 380;
                        $sizer[2]['width'] = 760;
                        $sizer[2]['height'] = 380;
                    }
                
                    $tml = '<object classid="clsid:d27cdb6e-ae6d-11cf-96b8-444553540000" style="border: solid 0px red;" codebase="http://fpdownload.macromedia.com/pub/shockwave/cabs/flash/swflash.cab#version=8,0,0,0" width="' . $sizer[$item['position']]['width'] . '" height="' . $sizer[$item['position']]['height'] . '" id="flash_id" align="middle">
                                <param name="allowScriptAccess" value="sameDomain" />
                                <param name="movie" value="' . $_GLOBAL['page_url'] . 'files/baners_big/' . $item['plik'] . '" />
                                <param name="quality" value="high" />
                                <param name="wmode" value="transparent">
                                <param name="scale" value="scale" />
                                <param name="menu" value="false" />
                                <embed src="' . $_GLOBAL['page_url'] . 'files/baners_big/' . $item['plik'] . '" menu="false" scale="scale" wmode="transparent" quality="high" width="' . $sizer[$item['position']]['width'] . '" height="' . $sizer[$item['position']]['height'] . '" name="flash_id" align="middle" allowscriptaccess="sameDomain" type="application/x-shockwave-flash" pluginspage="http://www.macromedia.com/go/getflashplayer" />
                                </object>   ';
 
                } else
                if($item['type']=='flv')
                {} else
                if($item['type']=='mp4')
                {
                    if($baner['template']==1)
                    {
                        $sizer[1]['width'] = 504;
                        $sizer[1]['height'] = 184;
                        $sizer[2]['width'] = 250;
                        $sizer[2]['height'] = 184;
                    } else
                    if($baner['template']==2)
                    {
                        $sizer[1]['width'] = 504;
                        $sizer[1]['height'] = 184;
                        $sizer[2]['width'] = 250;
                        $sizer[2]['height'] = 184;
                        $sizer[3]['width'] = 504;
                        $sizer[3]['height'] = 184;
                        $sizer[4]['width'] = 250;
                        $sizer[4]['height'] = 184;
                    } else
                    if($baner['template']==3)
                    {
                        $sizer[1]['width'] = 504;
                        $sizer[1]['height'] = 184;
                        $sizer[2]['width'] = 250;
                        $sizer[2]['height'] = 184;
                        $sizer[3]['width'] = 504;
                        $sizer[3]['height'] = 184;
                        $sizer[4]['width'] = 250;
                        $sizer[4]['height'] = 184;
                        $sizer[5]['width'] = 504;
                        $sizer[5]['height'] = 184;
                        $sizer[6]['width'] = 250;
                        $sizer[6]['height'] = 184;
                    } else
                    if($baner['template']==4)
                    {
                        $sizer[1]['width'] = 504;
                        $sizer[1]['height'] = 184;
                        $sizer[2]['width'] = 250;
                        $sizer[2]['height'] = 184;
                        $sizer[3]['width'] = 504;
                        $sizer[3]['height'] = 184;
                        $sizer[4]['width'] = 250;
                        $sizer[4]['height'] = 184;
                        $sizer[5]['width'] = 504;
                        $sizer[5]['height'] = 184;
                        $sizer[6]['width'] = 250;
                        $sizer[6]['height'] = 184;
                        $sizer[7]['width'] = 504;
                        $sizer[7]['height'] = 184;
                        $sizer[8]['width'] = 250;
                        $sizer[8]['height'] = 184;
                    } else
                    if($baner['template']==5)
                    {
                        $sizer[1]['width'] = 760;
                        $sizer[1]['height'] = 184;
                    } else
                    if($baner['template']==6)
                    {
                        $sizer[1]['width'] = 760;
                        $sizer[1]['height'] = 184;
                        $sizer[2]['width'] = 760;
                        $sizer[2]['height'] = 184;
                    } else
                    if($baner['template']==7)
                    {
                        $sizer[1]['width'] = 760;
                        $sizer[1]['height'] = 184;
                        $sizer[2]['width'] = 760;
                        $sizer[2]['height'] = 184;
                        $sizer[3]['width'] = 760;
                        $sizer[3]['height'] = 184;
                    } else
                    if($baner['template']==8)
                    {
                        $sizer[1]['width'] = 760;
                        $sizer[1]['height'] = 184;
                        $sizer[2]['width'] = 760;
                        $sizer[2]['height'] = 184;
                        $sizer[3]['width'] = 760;
                        $sizer[3]['height'] = 184;
                        $sizer[4]['width'] = 760;
                        $sizer[4]['height'] = 184;
                    } else
                    if($baner['template']==9)
                    {
                        $sizer[1]['width'] = 760;
                        $sizer[1]['height'] = 380;
                        $sizer[2]['width'] = 377;
                        $sizer[2]['height'] = 184;
                        $sizer[3]['width'] = 377;
                        $sizer[3]['height'] = 184;
                    } else
                    if($baner['template']==10)
                    {
                        $sizer[1]['width'] = 760;
                        $sizer[1]['height'] = 380;
                        $sizer[2]['width'] = 377;
                        $sizer[2]['height'] = 184;
                        $sizer[3]['width'] = 377;
                        $sizer[3]['height'] = 184;
                        $sizer[4]['width'] = 377;
                        $sizer[4]['height'] = 184;
                        $sizer[5]['width'] = 377;
                        $sizer[5]['height'] = 184;
                    } else
                    if($baner['template']==11)
                    {
                        $sizer[1]['width'] = 377;
                        $sizer[1]['height'] = 573;
                        $sizer[2]['width'] = 377;
                        $sizer[2]['height'] = 573;
                        $sizer[3]['width'] = 377;
                        $sizer[3]['height'] = 184;
                        $sizer[4]['width'] = 377;
                        $sizer[4]['height'] = 184;
                    } else
                    if($baner['template']==12)
                    {
                        $sizer[1]['width'] = 760;
                        $sizer[1]['height'] = 380;
                        $sizer[2]['width'] = 760;
                        $sizer[2]['height'] = 380;
                    }
                    
                    $tml = '<object width="' . $sizer[$item['position']]['width'] . '" height="' . $sizer[$item['position']]['height'] . '" type="application/x-shockwave-flash" data="' . $_GLOBAL['page_url'] . 'files/player.swf">
				<param name="movie" value="' . $_GLOBAL['page_url'] . 'files/player.swf" />
                                    <param name="wmode" value="transparent">
				<param name="flashvars" value="controlbar=over&amp;image=' . $_GLOBAL['page_url'] . 'files/baners_big/' . str_replace('mp4','jpg',$item['plik']) .'&amp;file=' . $_GLOBAL['page_url'] . 'files/baners_big/' . $item['plik'] . '" />
                                <embed src="' . $_GLOBAL['page_url'] . 'files/player.swf" FlashVars="controlbar=over&amp;image=' . $_GLOBAL['page_url'] . 'files/baners_big/' . str_replace('mp4','jpg',$item['plik']) .'&amp;file=' . $_GLOBAL['page_url'] . 'files/baners_big/' . $item['plik'] . '"  menu="false" scale="scale" wmode="transparent" quality="high" width="' . $sizer[$item['position']]['width'] . '" height="' . $sizer[$item['position']]['height'] . '" name="flash_id" align="middle" allowscriptaccess="sameDomain" type="application/x-shockwave-flash" pluginspage="http://www.macromedia.com/go/getflashplayer" />
                            </object>';
 
                }
                //echo $tml . '<pre>' . print_r($item,true) . '</pre>';
                $banertpl = str_replace('{BANER' . $item['position'] . '}',$tml,$banertpl);
            }

        return $banertpl;
    }
    
    function get_strona() {
        global $Ccms,$_GLOBAL,$db,$sURL;
        $out = '';
        
        $url = str_replace('/gomez/','',$_SERVER['REQUEST_URI']);
        $url = str_replace('.htm','',$url);
        $url = explode(',',$url);
        
        if((!isset($sURL[1])) || (empty($sURL[1]))) $bid = 1;
        else
        if($sURL[1]=='women') $bid=2;
        else $bid = 3;
//print_r($sURL);
        $out .= $this->get_baner($bid);
        return $out;
    }
}

?>