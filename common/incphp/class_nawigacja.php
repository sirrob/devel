<?php
class nawigacja {
    var $page = 0;
    var $lista = 5;
    var $ppp;
    var $maxp;
    var $url;
  
    function nawigacja($max, $ppp=30, $url="") {
        global $_GET;
        $this->url = ($url=="")?$_SERVER["PHP_SELF"]:$url;
    
        $this->maxp = $max;
        $this->ppp = $ppp;
    
        if(isset($_GET["p"])) $this->page=$_GET["p"];
        elseif(isset($_POST["p"])) $this->page=$_POST["p"];

        if(!$this->page or $max==0) $this->page=0;
        elseif($this->page>floor(($max-1)/$ppp))$this->page=floor(($max-1)/$ppp);
        $_SESSION["page"] = $this->page;
        
    }

  /**
   * Funkcja podstawia banery do szablonu głównego
   *
   * @return integer Ilość stron
   */
    
    function get_toppage() {return ceil($this->maxp/$this->ppp);}
    function set_page($i) {$this->page=$i;}
    function set_max($max) { $this->maxp = $max; }
    function get_maxp() { return $this->maxp; }
    function get_page() { return $this->page; }
    function get_min() { return $this->page*(($this->ppp==0)?1:$this->ppp); }
    function get_max() { return $this->get_min() + $this->ppp; }
    function get_ppp() { return $this->ppp; }

    function get_pozycje() {
        $min = $this->get_min();
        if($this->maxp < $this->get_max()) $max = $this->maxp;
        else $max = $this->get_max();
        
        if($this->ppp==0) $max = $this->maxp;
        //$min = $max==0?-1:$min;
        return (($min+1)."-".$max." {T_Z} ".$this->maxp);    
    }

    /**
   * Funkcja generuje oraz zwraca listę stron
   *
   * @param string $extra_par
   * @return string Lista stron
   */
    function prn_strony($extra_par="") {
        global $b,$_GLOBAL,$sURL;
        $min = $this->get_min();
        $out = '';

        if($this->maxp < $this->get_max()) $max = $this->maxp;
        else $max = $this->get_max();
    
        $start = floor($this->page/$this->lista)*$this->lista;

        if($start+$this->lista>ceil($this->maxp/$this->ppp)) $maxp = ceil($this->maxp/$this->ppp);
        else $maxp = $start+$this->lista;  


       if($maxp<2) return;
        $out .= '<div class="page-list-container">
                                <ul>';
        if($this->page>0) {
            if($sURL[0]=='wyszukiwarka') {
                if (($this->page-1) == 0) {
                    $out .= '<li><a href="' . $this->url . '' . ($this->page-1) . '" class="prev-page"><img src="' . $_GLOBAL['page_url'] . 'images/spacer.gif" alt="spacer" width="21" height="20" /></a></li>';
                } else {
                    $out .= '<li><a href="' . $this->url . '" class="prev-page"><img src="' . $_GLOBAL['page_url'] . 'images/spacer.gif" alt="spacer" width="21" height="20" /></a></li>';
                }
            }
            else {
                if (($this->page-1) == 0) {
                    $out .= '<li><a href="' . $this->url . $extra_par.'" class="prev-page"></a></li>';
                } else {
                    $out .= '<li><a href="' . $this->url . '' . ($this->page-1) . '/' . $extra_par.'" class="prev-page"></a></li>';
                }
            }
        } else {
        }
        
        //paginacja        
        for($i=$start; $i<$maxp;$i++){
            if($this->page != $i ) 
            { 
                if($sURL[0]=='wyszukiwarka') {
                    if ($i == 0) {
                        $out .= '<li><a href="'.$this->url.'"'.(($i==$maxp-1)?' class="end"':'').'>' . ($i+1) . "</a></li>\n";
                    } else {
                        $out .= '<li><a href="'.$this->url.''.$i.'"'.(($i==$maxp-1)?' class="end"':'').'>' . ($i+1) . "</a></li>\n";
                    }
                }
                else {
                    if ($i == 0) {
                        $out .= '<li><a href="'.$this->url.$extra_par.'"'.(($i==$maxp-1)?' class="end"':'').'>' . ($i+1) . "</a></li>\n";
                    } else {
                        $out .= '<li><a href="'.$this->url.''.$i.'/'.$extra_par.'"'.(($i==$maxp-1)?' class="end"':'').'>' . ($i+1) . "</a></li>\n";
                    }
                }
            } else 
            {
                $out .= '<li><a name="a" class="sel'.(($i==$maxp-1)?'end':'').'">'.($i+1).'</a></li>';
            }
        }

        if($this->page<ceil($this->maxp/$this->ppp)-1) 
        {
            if($sURL[0]=='wyszukiwarka') {
                $out .= '<li style="margin-right:0px;padding-right:1px;"><a href="'.$this->url.''.($this->page+1).'" class="next-page"><img src="' . $_GLOBAL['page_url'] . 'images/spacer.gif" alt="spacer" width="21" height="20" /></a></li>';
            } else {
                $out .= '<li style="margin-right:0px;padding-right:1px;"><a href="'.$this->url.''.($this->page+1).'/'.$extra_par.'" class="next-page"></a></li>';
            }
        } else {
        }
        $out .= '</ul>
                </div>';
    
        return $out;
    }
  
    /**
   * Funkcja idź do strony
   *
   * @param string $extra_par
   * @return string Select z listą stron
   */
    
    function get_idzdo($extra_par="") {
        $out = '<select name="idzdo" onchange="window.location=\''.$this->url.'p=\'+this.options[this.selectedIndex].value+\''.$extra_par.'\';">';
        for($i=0;$i<$this->get_toppage();$i++) {
            $out .= '<option value="'.$i.'"'.($i==$this->page?' selected':'').'>'.($i+1).'</option>';
        }
        $out .= '</select>';  
        return $out;
    }  
}
?>