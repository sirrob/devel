<?php
/******
** CleanURL
** Make your URLs User & Google friendly
** Version 0.9
******
** Author: Huda M Elmatsani
** Email: 	justhuda ** netscape ** net
**
** 2/Nov/2004
******
** Copyright (c) 2004 Huda M Elmatsani All rights reserved.
** This program is free for any purpose use.
********
**
** The scope of this class is to change the way writing URL Query String like this:
** from
**   news.php?id=120&page=2
** to
**   news/120/2
**
** edit your .htaccess and use directive RewriteRule
** Example:
**
** RewriteEngine on
** RewriteRule ^news(\/.*)*$  /news.php
**
**
** To create clean URL:
** $clean->makeClean('news.php?id=120&page=2');
** results 'news/120/2';
**
** To read clean URL
** $clean->parseURL();
** $clean->setRelative('relativeslash'); //relativeslash is variable name
** $clean->setParts('id','page');
**
** What is relative slash?
** think this img tag on index.php file directory:
** <img src="images/logo.jpeg">
** if you access your image logo from deeper directory, should be like this:
** <img src="../../images/logo.jpeg">
** Clean URL class makes the URL deeper because of using slashes
** the solution is placing variable that contains 'relative slashes'.
** <img src="<?=$relativeslash?>images/logo.jpeg">
**
** Note on setParts()
** $clean->setParts('id','page');
** this method will produce pairs of query string variables and values,
** using eval() function, webpage will read as:
** $id = 120;
** $page = 2;
**
**
**  EXAMPLES:
**  To create clean URL
**  original: <a href="news.php?id=<?=$id?>&page=<?=$page?>">;
**  convert:  <a href="<? CleanURL::makeClean("news.php?id=$id&page=$page")?>">
**  result on browser: <a href="news/120/2">;
**
**  To parse clean URL as above
**  $clean = new CleanURL;
**  $clean->parseURL();
**  $clean->setRelative('relativeslash');
**  $clean->setParts('id','page');
**
************/

class CleanURL {

	var $basename;
	var $parts;
	var $slashes;

	function parseURL() {
		 /* grab URL query string and script name */
		$uri = $_SERVER['REQUEST_URI'];
		$script = $_SERVER['SCRIPT_NAME'];
		/* get extension */
		$ext = end( explode(".",$script) );

		/* if extension is found in URL, eliminate it */
		if(strstr($uri,".")) {
			$arr_uri = explode('.', $uri);
			/* get last part */
			$last = end($arr_uri);

			if($last == $ext){
				array_pop($arr_uri);
				$uri = implode('.', $arr_uri);
			}
		}

		/* pick the name without extension */
		$basename = basename ($script, '.'.$ext);
		/* slicing query string */
		$temp  = explode('/',$uri);
		$key   = array_search($basename,$temp);
		$parts = array_slice ($temp, $key+1);
		$this->basename = $basename;
		$this->parts = $parts;

	}

	function setRelative($relativevar) {
		/* count the number of slash
		   to define relative path */
		$numslash = count($this->parts);
		$slashes="";
		for($i=0;$i<$numslash;$i++){
			$slashes .= "../";
		}
		$this->slashes = $slashes;
		/* make relative path variable available for webpage */
		eval("\$GLOBALS['$relativevar'] = '$slashes';");

	}

	function getParts() {
		/* return array of sliced query string */
		return $this->parts;
	}

	function setParts() {
		/* pair off query string variable and query string value */
		$numargs = func_num_args();
		$arg_list = func_get_args();
		$urlparts = $this->getParts();
		for ($i = 0; $i < $numargs; $i++) {
			/* make them available for webpage */
			eval ("\$GLOBALS['".$arg_list[$i] ."']= '$urlparts[$i]';");
		}

   }

   function makeClean($stringurl) {
		/* convert normal URL query string to clean URL */
		$url=parse_url($stringurl);
		$strurl = basename($url['path'],".php");
		$qstring = parse_str($url['query'],$vars);
		while(list($k,$v) = each($vars)) $strurl .= "/".$v;
		return $strurl;

   }
}


?>