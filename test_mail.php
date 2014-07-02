<?php

function mail_utf8($to, $from_user, $from_email, $subject = '(No subject)', $message = '') { 
  $from_user = "=?UTF-8?B?".base64_encode($from_user)."?=";
  $subject = "=?UTF-8?B?".base64_encode($subject)."?=";

  $headers =	"From: $from_user <$from_email>\r\n". 
           		"MIME-Version: 1.0" . "\r\n" . 
           		"Content-type: text/plain; charset=UTF-8" . "\r\n"; 

  return mail($to, $subject, $message, $headers); 
}


mail_utf8("support@gomez.pl, it-support@gomez.pl", "Marcin Borowski", "mborowski@mindgroup.pl", "Test", "wiadomosc");

?>