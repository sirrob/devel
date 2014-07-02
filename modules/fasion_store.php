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
        // $query = "select * from " . dn('news') . " where page=1 and id=".$id." and (active='y' or (active_start<'" . date("Y-n-d") . "' and active_stop<'" . date('Y-n-d') . "'))";
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
            
            $tml = $img;
            
           $banertpl = str_replace('{BANER2}', $tml, $banertpl);

        } else {
            $banertpl = "";
        }

        return $banertpl;
    }
    
    
    function getPrd($prd, $link = '') {
        global $db,$_GLOBAL;

        $out = '';
        $pathPrd = './files/news/';

        if(file_exists($pathPrd . $prd) && is_file($pathPrd.$prd)) {
            if($link != '') {
                $out = '<a href="' . $link . '"><img src="' . $_GLOBAL['page_url'] . $pathPrd . $prd . '" alt=""></a>';
            } else {
                $out = '<img src="' . $_GLOBAL['page_url'] . $pathPrd . $prd . '" alt="">';
            }
        }
        
        return $out;
    }
    
    function getProductBaners($id) {
        global $db, $_GLOBAL;

        $query = "select * from " . dn('news') . " where id=" . (int)$id . ";";
        $res = $db->query($query);
        $item = $db->fetch($res);
        
        $path = $_GLOBAL['page_url'];
        for ($i = 1; $i <= 30; $i++) {
            $item['product_index'.$i] = trim($item['product_index'.$i]);
            if (isset($item['product_index'.$i]) AND !empty($item['product_index'.$i])) {
                $query = "  SELECT
                                sp.pr_id,
                                sp.pr_nazwa,
                                p.pr_plik,
                                sp.pr_punkt,
                                p.pr_cena_a_brutto,
                                p.pr_cena_w_brutto,
                                sp.pr_etykieta,
                                spd.pd_nazwa,
                                spd.pd_alias,
                                sk.ka_nazwa,
                                se.et_plik,
                                IF ((p.pr_cena_a_brutto - p.pr_cena_w_brutto)>0,1,0) AS upust
                            FROM mm_sklep_produkt sp
                            LEFT JOIN mm_produkt p ON p.pr_id = sp.pr_id
                            LEFT JOIN mm_sklep_producent spd ON spd.pd_id = sp.pr_pd_id
                            LEFT JOIN mm_sklep_kategoria_produkt skp ON skp.kp_pr_id = sp.pr_id
                            LEFT JOIN mm_sklep_kategoria sk ON sk.ka_id = skp.kp_ka_id
                            LEFT JOIN mm_sklep_etykieta se ON sp.pr_etykieta = se.et_id
                            WHERE p.pr_indeks = '".$item['product_index'.$i]."'
                            GROUP BY sp.pr_id";
                $result = $db->query($query);
                $product = $db->fetch($result);


                if($product["pr_plik"]=="") {
                    $plik = "files/product/images/".ceil($product["pr_id"]/500)."/m/".$product["pr_id"]."_0.jpg";  
                    if(file_exists($plik)) {
                        $product["pr_plik"] = $plik;
                    }
                }
        
                $product['pr_plik'] = str_replace('http:////','',$product['pr_plik']);
                
                if(substr($product["pr_plik"],0,8)=="http:///") {
                    $product["pr_plik"] = substr($product["pr_plik"],8);
                }    

                if(substr($product["pr_plik"],0,4)=="http") {
                    $product["pr_plik"] = substr($product["pr_plik"],8);
                    $product["pr_plik"] = substr($product["pr_plik"],strpos($product["pr_plik"],"/")+1);
                }
        
                $product["pr_plik"] = str_replace("/x/","/m/",$product["pr_plik"]);
                if(substr($product['pr_plik'],0,1)=='/') {
                    $product['pr_plik'] = substr($product['pr_plik'],1);
                }

                if ($_GLOBAL['langid'] != 1) {
                    $q = "select * from " . dn('sklep_product_translation') . " where pr_id=" . $product['pr_id'] . " and langid=" . $_GLOBAL['langid'] . " and name='nazwa';";
                    $r = $db->query($q);
                    $translation = $db->fetch($r);

                    $product_name = $translation['description'];
                } else {
                    $product_name = $product['pr_nazwa'];
                }                

                $product_image = $product['pr_plik'];
                $product_producer = $product['pd_nazwa'];
                $product_label = $product['et_plik'];
                $product_link = $_GLOBAL['page_url'] . $_GLOBAL['lang'] . '/produkt/' . $product['pr_id'] . '/' . conv2file($product["pd_nazwa"]) . '/' . conv2file($product['pr_nazwa']) . '/';
                $waluta = "zł";
                $product_price = ($product["pr_cena_w_brutto"]<$product["pr_cena_a_brutto"]?'<span class="sale">'.number_format($product["pr_cena_a_brutto"],2,".","").' ' . $waluta . '</span> '.number_format($product["pr_cena_w_brutto"],2,".",""):number_format($product["pr_cena_w_brutto"],2,".",""));

                // $B["{IMG}"] = ($T["pr_plik"]!="" and file_exists($T["pr_plik"]))?str_replace("/m/","/".$this->widok_listy."/",$T["pr_plik"]):'images/nofoto_m.jpg';//'images/nofoto_'.$this->widok_listy.'.png';
                
                // $B['{IMG}'] = $_GLOBAL['page_url'] . str_replace("/l/","/m/",$B['{IMG}']);

                $out .= '<div class="news-product-block">
                            <a href="'.$product_link.'" class="with-tooltip">
                                <div class="product-image-container" style="background-image: url('.$path . $product_image.');">
                                    '.stripcslashes($product_label).'
                                </div>
                                <div class="product-manufacture">'.stripslashes($product_producer).'</div>
                                <div class="product-name">'.stripslashes($product_name).'</div>
                                <div class="product-price">'.$product_price.' '.$waluta.'</div>
                            </a>
                         </div>';
            }
        }

        return $out;
    }

    function getBaners($id) {
        global $db,$_GLOBAL;

        $out = '';
        $query = "select * from " . dn('news') . " where id=" . (int)$id . ";";
        $res = $db->query($query);
        $item = $db->fetch($res);
        
        $pa = $_GLOBAL['page_url'] . 'files/news_baners/';
        for($i=1;$i<=20;$i++) {
            if(!empty($item['bfile'.$i])) {
                if(!empty($item['blink'.$i])) {
                    $out .= '<a href="' . $item['blink'.$i] . '"><img src="' . $pa . $item['bfile'.$i] . '" alt=""></a>';
                } else {
                    $out .= '<img src="' . $pa . $item['bfile'.$i] . '" alt="">';
                }
            }
        }
        
        return $out;
    }
    
    function get_strona() {
        global $Ccms,$_GLOBAL,$db, $sURL, $L;

        $out = '';
              
        // $url = str_replace('/gomez/','',$_SERVER['REQUEST_URI']);
        // $url = str_replace('.htm','',$url);
        // $url = explode(',',$url);
        // print_r($sURL);
        if(count($sURL)==2) {
            $news_id = $sURL[1];
          
            $query = "select * from " . dn('news') . "
                    where page=1 and (active='y' and ((active_start<'" . date("Y-m-d") . "' or active_start is NULL) and (active_stop>='" . date('Y-m-d') . "' or active_stop is NULL))) and
                    alias='" . $news_id . "' and langid=" . $_GLOBAL['langid'] . "
                    order by timestamp DESC;";
            
            $res = $db->query($query);
            $item = $db->fetch($res);

            $img2 = '';
            if(file_exists('./files/news/' . $item['file2']) && is_file('./files/news/' . $item['file2'])) {
                
                $roz = explode('.', $item['file2']);
                if($roz[count($roz)-1]=='jpg') {
                    $img2 = '<img src="' . $_GLOBAL['page_url'] . 'files/news/' . $item['file2'] . '" alt="">';
                } else if($roz[count($roz)-1]=='swf') {
                    $img2 = '<object classid="clsid:d27cdb6e-ae6d-11cf-96b8-444553540000" codebase="http://fpdownload.macromedia.com/pub/shockwave/cabs/flash/swflash.cab#version=8,0,0,0" width="194" height="374" id="flash_id" align="middle">
                                <param name="allowScriptAccess" value="sameDomain" />
                                <param name="movie" value="' . $_GLOBAL['page_url'] . 'files/news/' . $item['file2'] . '" />
                                <param name="quality" value="high" />
                                <param name="wmode" value="transparent">
                                <param name="scale" value="scale" />
                                <param name="menu" value="false" />
                                <embed src="' . $_GLOBAL['page_url'] . 'files/news/' . $item['file2'] . '" menu="false" scale="scale" wmode="transparent" quality="high" width="194" height="374" name="flash_id" align="middle" allowscriptaccess="sameDomain" type="application/x-shockwave-flash" pluginspage="http://www.macromedia.com/go/getflashplayer" />
                                </object>';
                } else if($roz[count($roz)-1]=='mp4') {
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
            $bnr = $this->getProductBaners($item['id']);
            
            if($bnr!='') $out .= '<div class="ne-baners">'.$bnr.'</div>';
            
            $out .= '</div>';
        } else {
            $query = "select * from " . dn('news') . "
                  where page=1 and (active='y' and ((active_start<'" . date("Y-m-d") . "' or active_start is NULL) and (active_stop>='" . date('Y-m-d') . "' or active_stop is NULL))) and
                        langid=" . $_GLOBAL['langid'] . "
                  order by  " . dn('news') . ".order DESC LIMIT 1";
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
                  order by  " . dn('news') . ".order DESC LIMIT 5";

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
                    <a href="' . $_GLOBAL['page_url'] . $_GLOBAL['lang'] . '/fasion_store/' . $item['alias'] . '/">' . $L['{T_CZYTAJ_WIECEJ}'] . '</a>
                                    </div>
                </div>
                            </div><!-- box -->  ';
        }
                
        $out .= '</div><!-- small-banners -->';
    
        return $out;
    }
}



$val = '<link rel="stylesheet" type="text/css" media="all" href="' . $_GLOBAL['page_url'] . 'template/' . TEMPLATE . '/css/events/styles.css" />    
    <script src="' . $_GLOBAL['page_url'] . 'template/' . TEMPLATE . '/js/events/custom.js" type="text/javascript" language="javascript" charset="utf-8"></script>';

get_head($val);

?>