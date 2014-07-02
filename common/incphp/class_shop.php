<?php 
/**
 * Klasa obsługująca prezentowanie oferty sklepu
 * 
 * @author Michał Bzowy
 * @copyright Copyright (c) 2008, Michał Bzowy
 * @since 2008-08-12 12:36:48
 * @link www.imt-host.pl
 */

/**
 * Autoryzacja
 */
if ( !defined('SMDESIGN') ) {die("Hacking attempt");}

class shop extends layout {
  function shop ($ARG) {
    $this->layout($ARG);
  }
} 

?>