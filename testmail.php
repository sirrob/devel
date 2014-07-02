<?php
if(mail('maciej@freshstudio.pl','temat test mail','tresc test mail','','-fburo@karmac.pl'))
{
	echo 'mail poprawnie wyslany';
} else 
{
	echo 'mail NIE wys³any';
};
?>