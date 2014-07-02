<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<title>{TITLE}</title>
  <meta http-equiv="content-type" content="text/html; charset=utf-8" />
  <meta http-equiv="pragma" content="no-cache" /> 
  <meta name="title" content="{TITLE}" />
  <meta http-equiv="content-language" content="pl"/>
  <meta name="description" content="{DESCRIPTION}"/>
  <meta name="keywords" content="{KEYWORDS}"/>
  <meta name="robots" content="index, follow"/>
  <meta name="author" content="Positive / Michał Bzowy"/>
  <link href="template/favicon.ico" rel="shortcut icon" />
  <link href="template/favicon.ico" rel="Bookmark" />
  <link rel="stylesheet" href="template/{TEMPLATE}/style.css" type="text/css"/>
  <link rel="stylesheet" href="template/{TEMPLATE}/center.css" type="text/css"/>
  <link rel="stylesheet" href="template/{TEMPLATE}/boxstyle.css" type="text/css"/>
  <script type="text/javascript" src="inchtml/script.js"></script> 
  <script type="text/javascript">
{JAVASCRIPT}
  </script>
<!--{HEAD}-->
</head>

<body{ONLOAD}>
	<div class="glowny">
	  <div class="top"> 
			<a href="index.php" class="logo"></a>
            <div class="top2"><a href="javascript:;" onclick="m_ulubione();" id="dodaj">Dodaj do ulubionych</a><a href="javascript:;" onclick="m_polec('pl',window.location.href);">Poleć znajomemu</a></div>
            <div class="top3"><!--{TOPMENU}--><a href="{URL}" onclick="{ONCLICK}" class="{POZ}" id="mt{POZ}"{TARGET}>{TYTUL}</a><!--/{TOPMENU}--></div>       
     </div>
     <div class="menugl">
     <div class="mainmenu"><!--{MAINMENU}--><a href="{URL}" onclick="{ONCLICK}" class="{POZ}"{TARGET} id="mm{ID}">{TYTUL}</a><!--/{MAINMENU}--></div>
     <div class="logowanie"><div class="topszuk"><form action="{SZ_ACTION}" method="get">
<input type="hidden" name="a" value="szukaj"/><input type="hidden" name="i" value="41" /><input type="text" class="szuk" name="sz_key" id="ll" value="{T_KEYWORD}" onfocus="if(this.value=='{T_KEYWORD}')this.value=''" onblur="if(this.value=='')this.value='{T_KEYWORD}'" /><input type="submit" value="szukaj" class="szukajb"/></form></div>
     </div></div>
     <div class="banerpod">{BANER_TOP}</div>
      <div class="mapa">{LOCAL}</div>
     {EXTRA}
     {EXTERNAL_SKYSCRAPER}
     {STRONAPRE}
	 {STRONA}
	 {STRONASUF}
<div class="stopka"><img src="/kallisto/template/pl/images/atenastop.gif" alt="logo stopka" />ATENA Usługi Informatyczne i Finansowe Sp. z o.o. ul. Rzemieślnicza 33, 81-855 Sopot</div>
</div>
{DEBUG}
</body></html>
