<?php if(!defined('SMDESIGN')) die();
/**
 * Strona startowa
 * 
 * Tu można podstawić treść zastępczą dla strony głównej
 * 
 * @author Michał Bzowy
 * @copyright Copyright (c) 2008, Michał Bzowy
 * @since 2008-08-04 12:36:48
 * @link www.imt-host.pl
 */


class fasion_store extends mb {
    var $path = './files/brands_view/';
    function get_local() {
        global $Ccms;
        $R[] = $Ccms->get_tytul();
        return $R;
    }
  
    function get_baner($id) {
        global $db,$_GLOBAL;
        $query = "select * from " . dn('news') . " where page=1 and id=".$id." and (active='y' or (active_start<'" . date("Y-n-d") . "' and active_stop<'" . date('Y-n-d') . "'))";
        $query = "select * from " . dn('news') . " where page=1 and alias='".$id."' and (active='y' or (active_start<'" . date("Y-n-d") . "' and active_stop<'" . date('Y-n-d') . "')) and langid=" . $_GLOBAL['langid'] . ";";
        $re = $db->query($query);
        $item = $db->fetch($re);
//print_r($item);
        $banertpl = my_fread('files/prototyp/baner4.tpl');

        if ((file_exists('./files/news/' . $item['file0']) && is_file('./files/news/' . $item['file0'])) &&
        (file_exists('./files/news/' . $item['file1']) && is_file('./files/news/' . $item['file1'])) ) {
					
            // BANNER1
            $tml = '';
            $img = '';

            $roz = explode('.', $item['file0']);
            if($roz[count($roz)-1]=='jpg') 
            {
                if(!empty($item['link0'])) $img = '<a href="' . $item['link0'] . '"><img src="' . $_GLOBAL['page_url'] . 'files/news/' . $item['file0'] . '" alt=""></a>';
                else $img = '<img src="' . $_GLOBAL['page_url'] . 'files/news/' . $item['file0'] . '" alt="">';
            }
            else
            if($roz[count($roz)-1]=='swf')
            {
                $img = '<object classid="clsid:d27cdb6e-ae6d-11cf-96b8-444553540000" codebase="http://fpdownload.macromedia.com/pub/shockwave/cabs/flash/swflash.cab#version=8,0,0,0" width="788" height="374" id="flash_id" align="middle">
                                <param name="allowScriptAccess" value="sameDomain" />
                                <param name="movie" value="' . $_GLOBAL['page_url'] . 'files/news/' . $item['file0'] . '" />
                                <param name="quality" value="high" />
                                <param name="wmode" value="transparent">
                                <param name="scale" value="scale" />
                                <param name="menu" value="false" />
                                <embed src="' . $_GLOBAL['page_url'] . 'files/news/' . $item['file0'] . '" menu="false" scale="scale" wmode="transparent" quality="high" width="788" height="374" name="flash_id" align="middle" allowscriptaccess="sameDomain" type="application/x-shockwave-flash" pluginspage="http://www.macromedia.com/go/getflashplayer" />
                                </object>';
            } else
            if($roz[count($roz)-1]=='mp4')
            {
                $img = '<object width="788" height="374" type="application/x-shockwave-flash" data="' . $_GLOBAL['page_url'] . 'files/player.swf" style="background-color: black">
				<param name="movie" value="' . $_GLOBAL['page_url'] . 'files/player.swf" />
                                <param name="wmode" value="transparent">
				<param name="flashvars" value="controlbar=over&amp;image=' . $_GLOBAL['page_url'] . 'files/news/' . str_replace('mp4','jpg',$item['file0']) .'&amp;file=' . $_GLOBAL['page_url'] . 'files/news/' . $item['file0'] . '" />
                                <embed src="' . $_GLOBAL['page_url'] . 'files/player.swf" FlashVars="controlbar=over&amp;image=' . $_GLOBAL['page_url'] . 'files/news/' . str_replace('mp4','jpg',$item['file0']) .'&amp;file=' . $_GLOBAL['page_url'] . 'files/news/' . $item['file0'] . '"  menu="false" scale="scale" wmode="transparent" quality="high" width="788" height="374" name="flash_id" align="middle" allowscriptaccess="sameDomain" type="application/x-shockwave-flash" pluginspage="http://www.macromedia.com/go/getflashplayer" />
                            </object>';
            }
            
//            if(!empty($item['link0'])) {
//  	        if($roz[count($roz)-1] != 'swf' || $roz[count($roz)-1] != 'mp4') $tml = $img;
//  	        else $tml = '<a href="' . $item['link0'] . '">' . $img . '</a>';
//            } else {
//                $tml = $img;
//            }
            
            $tml = $img;
            
            $banertpl = str_replace('{BANER1}', $tml, $banertpl);

            // BANNER2
            $tml = '';
            $img = '';
            
            $roz = explode('.', $item['file1']);
            if($roz[count($roz)-1]=='jpg') 
            {
                if(!empty($item['link1'])) $img = '<a href="' . $item['link1'] . '"><img src="' . $_GLOBAL['page_url'] . 'files/news/' . $item['file1'] . '" alt=""></a>';
                else $img = '<img src="' . $_GLOBAL['page_url'] . 'files/news/' . $item['file1'] . '" alt="">';
            }
            else
            if($roz[count($roz)-1]=='swf')
            {
                $img = '<object classid="clsid:d27cdb6e-ae6d-11cf-96b8-444553540000" codebase="http://fpdownload.macromedia.com/pub/shockwave/cabs/flash/swflash.cab#version=8,0,0,0" width="194" height="374" id="flash_id" align="middle">
                                <param name="allowScriptAccess" value="sameDomain" />
                                <param name="movie" value="' . $_GLOBAL['page_url'] . 'files/news/' . $item['file1'] . '" />
                                <param name="quality" value="high" />
                                <param name="wmode" value="transparent">
                                <param name="scale" value="scale" />
                                <param name="menu" value="false" />
                                <embed src="' . $_GLOBAL['page_url'] . 'files/news/' . $item['file1'] . '" menu="false" scale="scale" wmode="transparent" quality="high" width="194" height="374" name="flash_id" align="middle" allowscriptaccess="sameDomain" type="application/x-shockwave-flash" pluginspage="http://www.macromedia.com/go/getflashplayer" />
                                </object>';
            } else
            if($roz[count($roz)-1]=='mp4')
            {
                $img = '<object width="194" height="373" type="application/x-shockwave-flash" data="' . $_GLOBAL['page_url'] . 'files/player.swf">
				<param name="movie" value="' . $_GLOBAL['page_url'] . 'files/player.swf" />
                                <param name="wmode" value="transparent">
				<param name="flashvars" value="controlbar=over&amp;image=' . $_GLOBAL['page_url'] . 'files/news/' . str_replace('mp4','jpg',$item['file1']) .'&amp;file=' . $_GLOBAL['page_url'] . 'files/news/' . $item['file1'] . '" />
                                <embed src="' . $_GLOBAL['page_url'] . 'files/player.swf" FlashVars="controlbar=over&amp;image=' . $_GLOBAL['page_url'] . 'files/news/' . str_replace('mp4','jpg',$item['file1']) .'&amp;file=' . $_GLOBAL['page_url'] . 'files/news/' . $item['file1'] . '"  menu="false" scale="scale" wmode="transparent" quality="high" width="194" height="374" name="flash_id" align="middle" allowscriptaccess="sameDomain" type="application/x-shockwave-flash" pluginspage="http://www.macromedia.com/go/getflashplayer" />
                            </object>';
            }
            
//            if(!empty($item['link1'])) {
//	      	  $tml = '<a href="' . $item['link1'] . '">' . $img . '</a>';
//            } else {
//                $tml = $img;//'<img src="files/news/' . $item['file1'] . '" alt="">';
//            }
            
            $tml = $img;
            
	    $banertpl = str_replace('{BANER2}', $tml, $banertpl);

        } else {
            $banertpl = "";
        }

        return $banertpl;
    }
    
    
    function getPrd($prd, $link = '')
    {//sklep,9,9526,koszula_thomas.htm
        global $db,$_GLOBAL;
        $out = '';
        $pathPrd = './files/news/';
  
        if(file_exists($pathPrd . $prd) && is_file($pathPrd.$prd)) 
        {
            if($link != '')
            {
                $out = '<a href="' . $link . '"><img src="' . $_GLOBAL['page_url'] . $pathPrd . $prd . '" alt=""></a>';
            } else
            {
                $out = '<img src="' . $_GLOBAL['page_url'] . $pathPrd . $prd . '" alt="">';
            }
        }
        
        return $out;
    }
    
    function getBaners($id)
    {
        global $db,$_GLOBAL;
        $out = '';
        $query = "select * from " . dn('news') . " where id=" . (int)$id . ";";
        $res = $db->query($query);
        $item = $db->fetch($res);
        
        $pa = $_GLOBAL['page_url'] . 'files/news_baners/';
        for($i=1;$i<=16;$i++)
        {
            if(!empty($item['bfile'.$i]))
            {
                if(!empty($item['blink'.$i]))
                {
                    $out .= '<a href="' . $item['blink'.$i] . '"><img src="' . $pa . $item['bfile'.$i] . '" alt=""></a>';
                } else
                {
                    $out .= '<img src="' . $pa . $item['bfile'.$i] . '" alt="">';
                }
            }
        }
        
        return $out;
    }
    
    function get_strona() {
        global $Ccms,$_GLOBAL,$db, $sURL;
        $out = '';
      
        
//        $url = str_replace('/gomez/','',$_SERVER['REQUEST_URI']);
//        $url = str_replace('.htm','',$url);
//        $url = explode(',',$url);
//           print_r($sURL);
        if(count($sURL)==2) {
            $news_id = $sURL[1];
          
//            $query = "select * from " . dn('news') . "
//                    where page=1 and (active='y' or (active_start<'" . date("Y-n-d") . "' and active_stop<'" . date('Y-n-d') . "')) and
//                    id=" . $news_id . "
//                    order by timestamp DESC;";
            $query = "select * from " . dn('news') . "
                    where page=1 and (active='y' and ((active_start<'" . date("Y-m-d") . "' or active_start is NULL) and (active_stop>='" . date('Y-m-d') . "' or active_stop is NULL))) and
                    alias='" . $news_id . "' and langid=" . $_GLOBAL['langid'] . "
                    order by timestamp DESC;";
            
            $res = $db->query($query);
            $item = $db->fetch($res);
//print_r($item);            
            $img2 = '';
            if(file_exists('./files/news/' . $item['file2']) && is_file('./files/news/' . $item['file2'])) {
                
                $roz = explode('.', $item['file2']);
                if($roz[count($roz)-1]=='jpg')
                {
                    $img2 = '<img src="' . $_GLOBAL['page_url'] . 'files/news/' . $item['file2'] . '" alt="">';
                } else
                if($roz[count($roz)-1]=='swf')
                {
                    $img2 = '<object classid="clsid:d27cdb6e-ae6d-11cf-96b8-444553540000" codebase="http://fpdownload.macromedia.com/pub/shockwave/cabs/flash/swflash.cab#version=8,0,0,0" width="194" height="374" id="flash_id" align="middle">
                                <param name="allowScriptAccess" value="sameDomain" />
                                <param name="movie" value="' . $_GLOBAL['page_url'] . 'files/news/' . $item['file2'] . '" />
                                <param name="quality" value="high" />
                                <param name="wmode" value="transparent">
                                <param name="scale" value="scale" />
                                <param name="menu" value="false" />
                                <embed src="' . $_GLOBAL['page_url'] . 'files/news/' . $item['file2'] . '" menu="false" scale="scale" wmode="transparent" quality="high" width="194" height="374" name="flash_id" align="middle" allowscriptaccess="sameDomain" type="application/x-shockwave-flash" pluginspage="http://www.macromedia.com/go/getflashplayer" />
                                </object>';
                } else
                if($roz[count($roz)-1]=='mp4')
                {
                    $img2 = '<object width="194" height="374" type="application/x-shockwave-flash" data="' . $_GLOBAL['page_url'] . 'files/player.swf">
				<param name="movie" value="' . $_GLOBAL['page_url'] . 'files/player.swf" />
                                <param name="wmode" value="transparent">
				<param name="flashvars" value="controlbar=over&amp;image=' . $_GLOBAL['page_url'] . 'files/news/' . str_replace('mp4','jpg',$item['file2']) .'&amp;file=../files/news/' . $item['file2'] . '" />
                                <embed src="' . $_GLOBAL['page_url'] . 'files/player.swf" FlashVars="controlbar=over&amp;image=' . $_GLOBAL['page_url'] . 'files/news/' . str_replace('mp4','jpg',$item['file2']) .'&amp;file=../files/news/' . $item['file2'] . '"  menu="false" scale="scale" wmode="transparent" quality="high" width="194" height="374" name="flash_id" align="middle" allowscriptaccess="sameDomain" type="application/x-shockwave-flash" pluginspage="http://www.macromedia.com/go/getflashplayer" />
                            </object>';
                } 
                
            }

            // BIG BANNERS                
            $bigbanners = $this->get_baner($news_id);
            $out .= $bigbanners;

            $out .= '<div class="newseventscontent">';
            $out .= '<div class="ne-content scroll-pane">' . $item['content'] . '</div>';
            $out .= '<div class="ne-prd1">' . $this->getPrd($item['prd1'],$item['prdlink1']) . '</div>';
            $out .= '<div class="ne-prd2">' . $this->getPrd($item['prd2'],$item['prdlink2']) . '</div>';
            if($img2 != '') $out .= '<div class="ne-img">' . $img2 . '</div>';
            
            $bnr = '';
            $bnr = $this->getBaners($item['id']);
            
            if($bnr!='') $out .= '<div class="ne-baners">'.$bnr.'</div>';
            
            $out .= '</div>';
        } else {
            $query = "select * from " . dn('news') . "
                  where page=1 and (active='y' and ((active_start<'" . date("Y-m-d") . "' or active_start is NULL) and (active_stop>='" . date('Y-m-d') . "' or active_stop is NULL))) and
                        langid=" . $_GLOBAL['langid'] . "
                  order by timestamp DESC LIMIT 1";
	    $res = $db->query($query);
            $item = $db->fetch($res);

            //$news_id = $item["id"];
            $news_id = $item["alias"];
					
            // BIG BANNERS                
            $bigbanners = $this->get_baner($news_id);
            $out .= $bigbanners;					
        }
            
    
    		// SMALL BANNERS
        $out .= '<div id="small-banners">';
                
        //pobieranie z bazy newsów        
        $query = "select * from " . dn('news') . "
                  where page=1 and alias<>'".$news_id."' and (active='y' and ((active_start<'" . date("Y-m-d") . "' or active_start is NULL) and 
                        (active_stop>='" . date('Y-m-d') . "' or active_stop is NULL))) and langid=" . $_GLOBAL['langid'] . "
                  order by timestamp DESC LIMIT 5";

        $res = $db->query($query);
        while($item = $db->fetch($res))
        {
            $out .= '<div class="box">
                                <div class="ic_container">
                                    <img src="' . $_GLOBAL['page_url'] . 'files/news/' . $item['file'] . '" width="194" height="190" alt=""/>
                                    <div class="ic_title">' . $item['nazwa'] . '</div>
                                    <div class="overlay" style="display:none;"></div>
                                    <div class="ic_caption">
                                        <p>' . $item['abstract'] . '</p>
					<a href="' . $_GLOBAL['page_url'] . $_GLOBAL['lang'] . '/fasion_store/' . $item['alias'] . '/">Czytaj więcej...</a>
                                    </div>
				</div>
                            </div><!-- box -->	';
        }
                
        $out .= '</div><!-- small-banners -->';
    
        return $out;
    }
}



$val = '<link rel="stylesheet" type="text/css" media="all" href="' . $_GLOBAL['page_url'] . 'template/' . TEMPLATE . '/css/events/styles.css" />	
	<script src="' . $_GLOBAL['page_url'] . 'template/' . TEMPLATE . '/js/events/custom.js" type="text/javascript" language="javascript" charset="utf-8"></script>';

get_head($val);

?>