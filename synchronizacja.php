<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
<title>GOMEZ - synchronizacja</title>
<script type="text/javascript">
var min = 5;
var sec = 10;
function syn()
{
	sec--;
	if(sec<0) 
	{
		min--;
		sec = 59;
	}

	if((min==0) && (sec==0))
	{
		document.getElementById('min').innerHTML=min;
		document.getElementById('sec').innerHTML=sec;
		location="synchronizacja.php";
	} else
	{
		document.getElementById('min').innerHTML=min;
		document.getElementById('sec').innerHTML=sec;
		setTimeout("syn()",1000);
	}
}
</script>
<style>
.gr{
	color: grey;
}

table {
	font-weight: bold;
	font-family: Tahoma;
	font-size: 18px;
}
</style>
</head>
<body onload="syn();">
<?php 

//file_get_contents('http://gomez.pl/cron-5min.php');
//require "cron-5min.php";
require "external/synchronization.php";


$string = 'Uruchomienie synchronizacji: ' . date("Y-m-d H:i:s") . "\r\n";

$fp = fopen("cron-log.txt", "a");
fputs ($fp, $string );
fclose ($fp);


?>
<br><br><br>
<table align="center">
<tr>
	<td class="gr">Następna synchronizacja za: </td>
	<td><div id="min">xx</div></td>
	<td class="gr"> min </td>
	<td><div id="sec">yy</div></td>
	<td class="gr"> sek</td>
</table>
</body>
</html>