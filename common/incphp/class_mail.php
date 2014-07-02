<?php

/**
 * @author Michał Bzowy
 * @since 2006-03-25
 */


function my_mail_smtp($T) {
    global $_GLOBAL, $conf;
    // config SMTP
    $params = array();
  
    // adres widoczny w naglowku wiadomosci jako adres z ktorego przyszla wiadomosc
    if($T["m_sender"]!="") $conf['from_header'] = $T["m_sender"]." ";
    elseif($_GLOBAL["mail_sender"]!="") $conf['from_header'] = $_GLOBAL["mail_sender"]." ";
    else $conf['from_header'] = "";

    // from - adres z ktorego otrzymaja wiadomosc odbiorcy
    if(!isset($T["m_from"]) or strpos($T["m_from"],"@")===false) $conf['from'] = $_GLOBAL["mail_email"];
    else {
        $conf['from'] = $T["m_from"];
        $conf['from_header'] = $T["m_from"];
    }
    

    $params = array(
        'host' => $_GLOBAL["mail_smtp"],    // Mail server address
        'port' => 25,                       // Mail server port
        'helo' => $_GLOBAL["mail_smtp"],    // Use your domain here.
        'auth' => TRUE,                     // Whether to use authentication or not.
        'user' => $_GLOBAL["mail_user"],    // Authentication username
        'pass' => $_GLOBAL["mail_password"] // Authentication password
    );

debug($params);    

    $mail = new html_mime_mail(array($conf['mailer']));

    $html = $T["m_message"];
    if(!isset($T["m_img_dir"]))$T["m_img_dir"]="";
    if(!isset($T["m_message_txt"]))$T["m_message_txt"]="";
    $mail->add_html($html, $T["m_message_txt"],$T["m_img_dir"]);
    if(isset($T["m_att"]))
        foreach($T["m_att"] as $A) {
            $fd = fopen ($A['tmp_name'], "r");
            $contents = fread ($fd, filesize ($A['tmp_name']));
            fclose ($fd);       
            $mail->add_attachment($contents,$A['name'],$A['type']);
        }
//  debug($T);
    if(!$mail->build_message()) {
		$file = 'debug_log.txt';
		// Open the file to get existing content
		$current = file_get_contents($file);
		// Append a new person to the file
		$current .=  "my_mail_smto: output: ".$this->output."\n";
		$current .=  "my_mail_smto: headers: ".$this->headers."\n";
		// Write the contents back to the file
		file_put_contents($file, $current);
	
		die('Failed to build email');
	}
    if(isset($smtp)) unset( $smtp );
//  $smtp =&smtp::connect($params);
    $smtp = new smtp($params);
    if($smtp->connect()){
        $smtp->status = SMTP_STATUS_CONNECTED;
    }

  
    if($conf['iso_subject']) $subject = "=?utf-8?B?".base64_encode($T["m_subject"])."?=";
    else $subject = "=?utf-8?B?".base64_encode($T["m_subject"])."?=";
    
    $send_params = array('from'		=> $conf['from'],
			 'recipients'	=> $T["m_to"],
			 'headers'	=> array('From: '.$conf['from_header'].'<'.$conf['from'].'>',
                                                 'To: <'.$T["m_to"].'>',
                                                 'Subject: '.$subject,
                                                 'Date: '.date('r') )
                        );
    $ret = true;
    if ( !$mail->smtp_send($smtp, $send_params) ) {
        $ret = false;
        debug($smtp->errors);
    }
    flush();
    return $ret;
}


/***************************************
** Title.........: HTML Mime Mail class
** Version.......: 2.0.3
** Author........: Richard Heyes <richard@phpguru.org>
** Filename......: class.html.mime.mail.class
** Last changed..: 21 December 2001
** License.......: Free to use. If you find it useful
**                 though, feel free to buy me something
**                 from my wishlist :)
**                 http://www.amazon.co.uk/exec/obidos/wishlist/S8H2UOGMPZK6
***************************************/
// nazwa linii w miejsce ktorej ma byc wstawiona stopka o wypisaniu
// sie z listy mailingowej
$conf['unsubscribe'] = '<!-- unsubscribe -->';

// nazwa programu pocztowego wysylana w naglowku
$conf['mailer'] = 'X-Mailer: Html Mime Mail Class';

// pamatry: true, false
// jesli true to $subject zamieniany jest na postac iso: 
// "=?iso-8859-2?B?".base64_encode($subject)."?=";
$conf['iso_subject'] = false;

// uzywa skali prawdopodobienstwa 1-10 do zapisywania
// adresow e-maili (przydatne przy zbieraniu adresow
// z niepewnych zrodel) wtedy za pomoca skali 1-10 mozna
// okreslic wieksza pewnosc nalezenia danego adresu do okreslonej
// grupy
$conf['use_probability'] = false;

// jesli wylaczona jest opcja powyzsza system uzywa tego "prawdopodobienstwa"
$conf['default_probability'] = 10;

// domyslna grupa (patrz. index tablicy $kinds w groups.php)
$conf['default_group'] = 1;

// FILTRY
// istnieje mozliwosc dodania domen do kategorii $kinds_short
// spelniaja one role filtrow, tzn: nowe adresy e-mail
// importowane z plikow sa przegladane pod katem
// domen i trafiaja automatycznie do kategorii przypisanych
// tym domenom
$conf['use_domens'] = false;

// znak konca linii
define('CRLF', "\r\n", TRUE);

// liczba e-maili pokazywana na jednej stronie
$max_results = 30;

$conf['tpl_header'] = '';
$conf['tpl_footer'] = '';

class Mail_mimePart{
    
   /**
    * The encoding type of this part
    * @var string
    */
    var $_encoding;

   /**
    * An array of subparts
    * @var array
    */
    var $_subparts;

   /**
    * The output of this part after being built
    * @var string
    */
    var $_encoded;

   /**
    * Headers for this part
    * @var array
    */
    var $_headers;

   /**
    * The body of this part (not encoded)
    * @var string
    */
    var $_body;

    /**
     * Constructor.
     * 
     * Sets up the object.
     *
     * @param $body   - The body of the mime part if any.
     * @param $params - An associative array of parameters:
     *                  content_type - The content type for this part eg multipart/mixed
     *                  encoding     - The encoding to use, 7bit, base64, or quoted-printable
     *                  cid          - Content ID to apply
     *                  disposition  - Content disposition, inline or attachment
     *                  dfilename    - Optional filename parameter for content disposition
     *                  description  - Content description
     * @access public
     */
    function Mail_mimePart($body, $params = array())
    {
        if (!defined('MAIL_MIMEPART_CRLF')) {
            define('MAIL_MIMEPART_CRLF', "\r\n", TRUE);
        }

        foreach ($params as $key => $value) {
            switch ($key) {
                case 'content_type':
                    $headers['Content-Type'] = $value . (isset($charset) ? '; charset="' . $charset . '"' : '');
                    break;

                case 'encoding':
                    $this->_encoding = $value;
                    $headers['Content-Transfer-Encoding'] = $value;
                    break;

                case 'cid':
                    $headers['Content-ID'] = '<' . $value . '>';
                    break;

                case 'disposition':
                    $headers['Content-Disposition'] = $value . (isset($dfilename) ? '; filename="' . $dfilename . '"' : '');
                    break;

                case 'dfilename':
                    if (isset($headers['Content-Disposition'])) {
                        $headers['Content-Disposition'] .= '; filename="' . $value . '"';
                    } else {
                        $dfilename = $value;
                    }
                    break;

                case 'description':
                    $headers['Content-Description'] = $value;
                    break;

                case 'charset':
                    if (isset($headers['Content-Type'])) {
                        $headers['Content-Type'] .= '; charset="' . $value . '"';
                    } else {
                        $charset = $value;
                    }
                    break;
            }
        }

        // Default content-type
        if (!isset($_headers['Content-Type'])) {
            $_headers['Content-Type'] = 'text/plain';
        }

        // Assign stuff to member variables
        $this->_encoded  =  array();
        $this->_headers  =& $headers;
        $this->_body     =  $body;
    }

    /**
     * encode()
     * 
     * Encodes and returns the email. Also stores
     * it in the encoded member variable
     *
     * @return An associative array containing two elements,
     *         body and headers. The headers element is itself
     *         an indexed array.
     * @access public
     */
    function encode()
    {
        $encoded =& $this->_encoded;

        if (!empty($this->_subparts)) {
            srand((double)microtime()*1000000);
            $boundary = '=_' . md5(uniqid(rand()) . microtime());
            $this->_headers['Content-Type'] .= ';' . MAIL_MIMEPART_CRLF . chr(9) . 'boundary="' . $boundary . '"';

            // Add body parts to $subparts
            for ($i = 0; $i < count($this->_subparts); $i++) {
                $headers = array();
                $tmp = $this->_subparts[$i]->encode();
                foreach ($tmp['headers'] as $key => $value) {
                    $headers[] = $key . ': ' . $value;
                }
                $subparts[] = implode(MAIL_MIMEPART_CRLF, $headers) . MAIL_MIMEPART_CRLF . MAIL_MIMEPART_CRLF . $tmp['body'];
            }

            $encoded['body'] = '--' . $boundary . MAIL_MIMEPART_CRLF .
                               implode('--' . $boundary . MAIL_MIMEPART_CRLF, $subparts) .
                               '--' . $boundary.'--' . MAIL_MIMEPART_CRLF;

        } else {
            $encoded['body'] = $this->_getEncodedData($this->_body, $this->_encoding) . MAIL_MIMEPART_CRLF;
        }

        // Add headers to $encoded
        $encoded['headers'] =& $this->_headers;

        return $encoded;
    }

    /**
     * &addSubPart()
     * 
     * Adds a subpart to current mime part and returns
     * a reference to it
     *
     * @param $body   The body of the subpart, if any.
     * @param $params The parameters for the subpart, same
     *                as the $params argument for constructor.
     * @return A reference to the part you just added. It is
     *         crucial if using multipart/* in your subparts that
     *         you use =& in your script when calling this function,
     *         otherwise you will not be able to add further subparts.
     * @access public
     */
    function &addSubPart($body, $params)
    {
        $this->_subparts[] = new Mail_mimePart($body, $params);
        return $this->_subparts[count($this->_subparts) - 1];
    }

    /**
     * _getEncodedData()
     * 
     * Returns encoded data based upon encoding passed to it
     *
     * @param $data     The data to encode.
     * @param $encoding The encoding type to use, 7bit, base64,
     *                  or quoted-printable.
     * @access private
     */
    function _getEncodedData($data, $encoding)
    {
        switch ($encoding) {
            case '7bit':
                return $data;
                break;

            case 'quoted-printable':
                return $this->_quotedPrintableEncode($data);
                break;

            case 'base64':
                return rtrim(chunk_split(base64_encode($data), 76, MAIL_MIMEPART_CRLF));
                break;
        }
    }

    /**
     * quoteadPrintableEncode()
     * 
     * Encodes data to quoted-printable standard.
     *
     * @param $input    The data to encode
     * @param $line_max Optional max line length. Should 
     *                  not be more than 76 chars
     *
     * @access private
     */
    function _quotedPrintableEncode($input , $line_max = 76)
    {
        $lines    = preg_split("/\r\n|\r|\n/", $input);
        $eol    = MAIL_MIMEPART_CRLF;
        $escape    = '=';
        $output    = '';
        
        while(list(, $line) = each($lines)){

            $linlen     = strlen($line);
            $newline = '';

            for ($i = 0; $i < $linlen; $i++) {
                $char = substr($line, $i, 1);
                $dec  = ord($char);

                if (($dec == 32) AND ($i == ($linlen - 1))){    // convert space at eol only
                    $char = '=20';

                } elseif($dec == 9) {
                    ; // Do nothing if a tab.
                } elseif(($dec == 61) OR ($dec < 32 ) OR ($dec > 126)) {
                    $char = $escape . strtoupper(sprintf('%02s', dechex($dec)));
                }
    
                if ((strlen($newline) + strlen($char)) >= $line_max) {        // MAIL_MIMEPART_CRLF is not counted
                    $output  .= $newline . $escape . $eol;                    // soft line break; " =\r\n" is okay
                    $newline  = '';
                }
                $newline .= $char;
            } // end of for
            $output .= $newline . $eol;
        }
        $output = substr($output, 0, -1 * strlen($eol)); // Don't want last crlf
        return $output;
    }
} // End of class

class html_mime_mail{
    var $html;
    var $text;
    var $output;
    var $html_text;
    var $html_images;
    var $image_types;
    var $build_params;
    var $attachments;
    var $headers;

/***************************************
** Constructor function. Sets the headers
** if supplied.
***************************************/

    function html_mime_mail($headers = array()){

	/***************************************
            ** Make sure this is defined. This should
            ** be \r\n, but due to many people having
            ** trouble with that, it is by default \n
            ** If you leave it as is, you will be breaking
            ** quite a few standards.
        ***************************************/

        if(!defined('CRLF')) define('CRLF', "\n", TRUE);

	/***************************************
            ** Initialise some variables.
        ***************************************/

	$this->html_images	= array();
	$this->headers		= array();

	/***************************************
            ** If you want the auto load functionality
            ** to find other image/file types, add the
            ** extension and content type here.
        ***************************************/

	$this->image_types = array(
				'gif'	=> 'image/gif',
				'jpg'	=> 'image/jpeg',
				'jpeg'	=> 'image/jpeg',
				'jpe'	=> 'image/jpeg',
                		'bmp'	=> 'image/bmp',
				'png'	=> 'image/png',
				'tif'	=> 'image/tiff',
                        	'tiff'	=> 'image/tiff',
				'swf'	=> 'application/x-shockwave-flash'
			  );

	/***************************************
            ** Set these up
        ***************************************/

	$this->build_params['html_encoding']	= 'quoted-printable';
	$this->build_params['text_encoding']	= '7bit';
	$this->build_params['html_charset']		= 'utf-8';
	$this->build_params['text_charset']		= 'utf-8';
	$this->build_params['text_wrap']		= 998;

	/***************************************
            ** Make sure the MIME version header is first.
        ***************************************/

	$this->headers[] = 'MIME-Version: 1.0';

	foreach($headers as $value){
            if(!empty($value)) $this->headers[] = $value;
	}
    }

/***************************************
** This function will read a file in
** from a supplied filename and return
** it. This can then be given as the first
** argument of the the functions
** add_html_image() or add_attachment().
***************************************/

    function get_file($filename){
        $return = '';
	if($fp = fopen($filename, 'rb')){
            while(!feof($fp)){
                $return .= fread($fp, 1024);
            }
            fclose($fp);
            return $return;
        }else{
            return FALSE;
	}
    }

/***************************************
** Function for extracting images from
** html source. This function will look
** through the html code supplied by add_html()
** and find any file that ends in one of the
** extensions defined in $obj->image_types.
** If the file exists it will read it in and
** embed it, (not an attachment).
**
** Function contributed by Dan Allen
***************************************/

    function find_html_images($images_dir) {
        // Build the list of image extensions
	while(list($key,) = each($this->image_types)) $extensions[] = $key;
        
        preg_match_all('/"([^"]+\.('.implode('|', $extensions).'))"/Ui', $this->html, $images);
#print_r($images);
	for($i=0; $i<count($images[1]); $i++){
            if(file_exists($images_dir.$images[1][$i])){
                $html_images[] = $images[1][$i];
		$this->html = str_replace($images[1][$i], basename($images[1][$i]), $this->html);
            }
	}

	if(!empty($html_images)){
            // If duplicate images are embedded, they may show up as attachments, so remove them.
            $html_images = array_unique($html_images);
            sort($html_images);
	
            for($i=0; $i<count($html_images); $i++){
                if($image = $this->get_file($images_dir.$html_images[$i])){
                    $content_type = $this->image_types[substr($html_images[$i], strrpos($html_images[$i], '.') + 1)];
                    $this->add_html_image($image, basename($html_images[$i]), $content_type);
		}
            }
	}
    }

/***************************************
** Adds plain text. Use this function
** when NOT sending html email
***************************************/

    function add_text($text = ''){
        $this->text = $text;
    }

/***************************************
** Adds a html part to the mail.
** Also replaces image names with
** content-id's.
***************************************/

    function add_html($html, $text = NULL, $images_dir = NULL){
        $this->html         = $html;
	$this->html_text    = $text;

	if(isset($images_dir)) $this->find_html_images($images_dir);
    }

/***************************************
** Adds an image to the list of embedded
** images.
***************************************/

    function add_html_image($file, $name = '', $c_type='application/octet-stream'){
        $this->html_images[] = array(
				'body'   => $file,
                    		'name'   => $name,
				'c_type' => $c_type,
				'cid'    => md5(uniqid(time()))
				);
    }


/***************************************
** Adds a file to the list of attachments.
***************************************/

    function add_attachment($file, $name = '', $c_type='application/octet-stream', $encoding = 'base64'){
        $this->attachments[] = array(
            			'body'		=> $file,
				'name'		=> $name,
				'c_type'	=> $c_type,
				'encoding'	=> $encoding
			  );
    }

/***************************************
** Adds a text subpart to a mime_part object
***************************************/

    function &add_text_part(&$obj, $text){
        $params['content_type'] = 'text/plain';
	$params['encoding']     = $this->build_params['text_encoding'];
	$params['charset']      = $this->build_params['text_charset'];
	if(is_object($obj)){
            return $obj->addSubpart($text, $params);
	}else{
            return new Mail_mimePart($text, $params);
	}
    }

/***************************************
** Adds a html subpart to a mime_part object
***************************************/

    function &add_html_part(&$obj){
        $params['content_type'] = 'text/html';
	$params['encoding']     = $this->build_params['html_encoding'];
	$params['charset']      = $this->build_params['html_charset'];
	if(is_object($obj)){
            return $obj->addSubpart($this->html, $params);
	}else{
            return new Mail_mimePart($this->html, $params);
	}
    }

/***************************************
** Starts a message with a mixed part
***************************************/

    function &add_mixed_part(){
        $params['content_type'] = 'multipart/mixed';
	return new Mail_mimePart('', $params);
    }

/***************************************
** Adds an alternative part to a mime_part object
***************************************/

    function &add_alternative_part(&$obj){
        $params['content_type'] = 'multipart/alternative';
	if(is_object($obj)){
            return $obj->addSubpart('', $params);
	}else{
            return new Mail_mimePart('', $params);
	}
    }

/***************************************
** Adds a html subpart to a mime_part object
***************************************/

    function &add_related_part(&$obj){
        $params['content_type'] = 'multipart/related';
	if(is_object($obj)){
            return $obj->addSubpart('', $params);
	}else{
            return new Mail_mimePart('', $params);
	}
    }

/***************************************
** Adds an html image subpart to a mime_part object
***************************************/

    function &add_html_image_part(&$obj, $value){
        $params['content_type'] = $value['c_type'];
	$params['encoding']     = 'base64';
	$params['disposition']  = 'inline';
	$params['dfilename']    = $value['name'];
	$params['cid']          = $value['cid'];
	$obj->addSubpart($value['body'], $params);
    }

/***************************************
** Adds an attachment subpart to a mime_part object
***************************************/

    function &add_attachment_part(&$obj, $value){
        $params['content_type'] = $value['c_type'];
	$params['encoding']     = $value['encoding'];
	$params['disposition']  = 'attachment';
	$params['dfilename']    = $value['name'];
	$obj->addSubpart($value['body'], $params);
    }

/***************************************
** Builds the multipart message from the
** list ($this->_parts). $params is an
** array of parameters that shape the building
** of the message. Currently supported are:
**
** $params['html_encoding'] - The type of encoding to use on html. Valid options are
**                            "7bit", "quoted-printable" or "base64" (all without quotes).
**                            7bit is EXPRESSLY NOT RECOMMENDED. Default is quoted-printable
** $params['text_encoding'] - The type of encoding to use on plain text Valid options are
**                            "7bit", "quoted-printable" or "base64" (all without quotes).
**                            Default is 7bit
** $params['text_wrap']     - The character count at which to wrap 7bit encoded data.
**                            Default this is 998.
** $params['html_charset']  - The character set to use for a html section.
**                            Default is iso-8859-1
** $params['text_charset']  - The character set to use for a text section.
**                          - Default is iso-8859-1
***************************************/

    function build_message($params = array()){
        if(count($params) > 0) while(list($key, $value) = each($params)) $this->build_params[$key] = $value;

	if(!empty($this->html_images))
            foreach($this->html_images as $value) $this->html = str_replace($value['name'], 'cid:'.$value['cid'], $this->html);

	$null        = NULL;
	$attachments = !empty($this->attachments) ? TRUE : FALSE;
	$html_images = !empty($this->html_images) ? TRUE : FALSE;
	$html        = !empty($this->html)        ? TRUE : FALSE;
	$text        = isset($this->text)         ? TRUE : FALSE;

	switch(TRUE){
            case $text AND !$attachments:
                $message =& $this->add_text_part($null, $this->text);
		break;

            case !$text AND $attachments AND !$html:
                $message =& $this->add_mixed_part();

		for($i=0; $i<count($this->attachments); $i++) $this->add_attachment_part($message, $this->attachments[$i]);
		break;

            case $text AND $attachments:
                $message =& $this->add_mixed_part();
		$this->add_text_part($message, $this->text);

		for($i=0; $i<count($this->attachments); $i++) $this->add_attachment_part($message, $this->attachments[$i]);
		break;

            case $html AND !$attachments AND !$html_images:
                if(!is_null($this->html_text)){
                    $message =& $this->add_alternative_part($null);
                    $this->add_text_part($message, $this->html_text);
                    $this->add_html_part($message);
		}else{
                    $message =& $this->add_html_part($null);
		}
		break;

            case $html AND !$attachments AND $html_images:
                if(!is_null($this->html_text)){
                    $message =& $this->add_alternative_part($null);
                    $this->add_text_part($message, $this->html_text);
                    $related =& $this->add_related_part($message);
		}else{
                    $message =& $this->add_related_part($null);
                    $related =& $message;
		}
		$this->add_html_part($related);
		for($i=0; $i<count($this->html_images); $i++) $this->add_html_image_part($related, $this->html_images[$i]);
		break;

            case $html AND $attachments AND !$html_images:
                $message =& $this->add_mixed_part();
                if(!is_null($this->html_text)){
                    $alt =& $this->add_alternative_part($message);
                    $this->add_text_part($alt, $this->html_text);
                    $this->add_html_part($alt);
		}else{
                    $this->add_html_part($message);
		}
                for($i=0; $i<count($this->attachments); $i++) $this->add_attachment_part($message, $this->attachments[$i]);
		break;

            case $html AND $attachments AND $html_images:
                $message =& $this->add_mixed_part();
                if(!is_null($this->html_text)){
                    $alt =& $this->add_alternative_part($message);
                    $this->add_text_part($alt, $this->html_text);
                    $rel =& $this->add_related_part($alt);
		}else{
                    $rel =& $this->add_related_part($message);
		}
		$this->add_html_part($rel);
		for($i=0; $i<count($this->html_images); $i++) $this->add_html_image_part($rel, $this->html_images[$i]);
                for($i=0; $i<count($this->attachments); $i++) $this->add_attachment_part($message, $this->attachments[$i]);
		break;
        }

	if(isset($message)){
            $output = $message->encode();
            $this->output = $output['body'];

            foreach($output['headers'] as $key => $value){
                $headers[] = $key.': '.$value;
            }

            $this->headers = array_merge($this->headers, $headers);
            return TRUE;
	}else return FALSE;
    }

/***************************************
** Sends the mail.
***************************************/

    function send($to_name, $to_addr, $from_name, $from_addr, $subject = '', $headers = ''){
        $to		= ($to_name != '')   ? '"'.$to_name.'" <'.$to_addr.'>' : $to_addr;
	$from	= ($from_name != '') ? '"'.$from_name.'" <'.$from_addr.'>' : $from_addr;

	if(is_string($headers)) $headers = explode(CRLF, trim($headers));

        for($i=0; $i<count($headers); $i++){
            if(is_array($headers[$i]))
                for($j=0; $j<count($headers[$i]); $j++)
                    if($headers[$i][$j] != '') $xtra_headers[] = $headers[$i][$j];

            if($headers[$i] != '') $xtra_headers[] = $headers[$i];
	}
	if(!isset($xtra_headers)) $xtra_headers = array();
        
        $subject = "=?utf-8?B?".base64_encode($subject)."?=";

	return mail($to, $subject, $this->output, 'From: '.$from.CRLF.implode(CRLF, $this->headers).CRLF.implode(CRLF, $xtra_headers));
    }

/***************************************
** Use this method to deliver using direct
** smtp connection. Relies upon the smtp
** class available from http://www.heyes-computing.net
** You probably downloaded it with this class though.
**
** bool smtp_send(
**                object The smtp object,
**                array  Parameters to pass to the smtp object
**                       See example.1.php for details.
**               )
***************************************/

    function smtp_send(&$smtp, $params = array()){
        foreach($params as $key => $value){
            switch($key){
                case 'headers':
                    $headers = $value;
                    break;
                case 'from':
                    $send_params['from'] = $value;
                    break;
                case 'recipients':
                    $send_params['recipients'] = $value;
                    break;
            }
	}

        $send_params['body']	= $this->output;
	$send_params['headers']	= array_merge($this->headers, $headers);

	return $smtp->send($send_params);
    }
/***************************************
** Use this method to return the email
** in message/rfc822 format. Useful for
** adding an email to another email as
** an attachment. there's a commented
** out example in example.php.
**
** string get_rfc822(string To name,
**		   string To email,
**		   string From name,
**		   string From email,
**		   [string Subject,
**		    string Extra headers])
***************************************/

    function get_rfc822($to_name, $to_addr, $from_name, $from_addr, $subject = '', $headers = ''){
        // Make up the date header as according to RFC822
        $date = 'Date: '.date('D, d M y H:i:s');

	$to   = ($to_name   != '') ? 'To: "'.$to_name.'" <'.$to_addr.'>' : 'To: '.$to_addr;
	$from = ($from_name != '') ? 'From: "'.$from_name.'" <'.$from_addr.'>' : 'From: '.$from_addr;


	if(is_string($subject)) $subject = 'Subject: '.$subject;

	if(is_string($headers)) $headers = explode(CRLF, trim($headers));

	for($i=0; $i<count($headers); $i++){
            if(is_array($headers[$i]))
                for($j=0; $j<count($headers[$i]); $j++)
                    if($headers[$i][$j] != '') $xtra_headers[] = $headers[$i][$j];

            if($headers[$i] != '') $xtra_headers[] = $headers[$i];
	}

	if(!isset($xtra_headers)) $xtra_headers = array();

	$headers = array_merge($this->headers, $xtra_headers);

	return $date.CRLF.$from.CRLF.$to.CRLF.$subject.CRLF.implode(CRLF, $headers).CRLF.CRLF.$this->output;
    }

} // End of class.

define('SMTP_STATUS_NOT_CONNECTED', 1, TRUE);
define('SMTP_STATUS_CONNECTED', 2, TRUE);

class smtp{
    var $authenticated;
    var $connection;
    var $recipients;
    var $headers;
    var $timeout;
    var $errors;
    var $status;
    var $body;
    var $from;
    var $host;
    var $port;
    var $helo;
    var $auth;
    var $user;
    var $pass;

    /***************************************
        ** Constructor function. Arguments:
		** $params - An assoc array of parameters:
		**
		**   host    - The hostname of the smtp server		Default: localhost
		**   port    - The port the smtp server runs on		Default: 25
		**   helo    - What to send as the HELO command		Default: localhost
		**             (typically the hostname of the
		**             machine this script runs on)
		**   auth    - Whether to use basic authentication	Default: FALSE
		**   user    - Username for authentication			Default: <blank>
		**   pass    - Password for authentication			Default: <blank>
		**   timeout - The timeout in seconds for the call	Default: 5
		**             to fsockopen()
    ***************************************/

    function smtp($params = array()){
        if(!defined('CRLF')) define('CRLF', "\r\n", TRUE);

	$this->authenticated    = FALSE;			
	$this->timeout  	= 5;
	$this->status		= SMTP_STATUS_NOT_CONNECTED;
	$this->host		= '';
	$this->port		= 25;
	$this->helo		= '';
	$this->auth		= FALSE;
	$this->user		= '';
	$this->pass		= '';
	$this->errors  		= array();

	foreach($params as $key => $value) $this->$key = $value;
    }

    /***************************************
        ** Connect function. This will, when called
		** statically, create a new smtp object, 
		** call the connect function (ie this function)
		** and return it. When not called statically,
		** it will connect to the server and send
		** the HELO command.
    ***************************************/

    function &connect($params = array()){
        if(!isset($this->status)){
            $obj = new smtp($params);
            if($obj->connect()){
                $obj->status = SMTP_STATUS_CONNECTED;
            }

            return $obj;
        }else{
            @$this->connection = fsockopen($this->host, $this->port, $errno, $errstr, $this->timeout);
            if(function_exists('socket_set_timeout')){
                @socket_set_timeout($this->connection, 5, 0);
            }
            
            $greeting = $this->get_data();
            if(is_resource($this->connection)){
                return $this->auth ? $this->ehlo() : $this->helo();
            }else{
                $this->errors[] = 'Failed to connect to server: '.$errstr;
            	#echo 'Failed to connect to server: '.$errstr."<br>"; // check
		return FALSE;
            }
	}
    }

    /**
     * Function which handles sending the mail.
     * Arguments:
     * $params	- Optional assoc array of parameters.
     *            Can contain:
     *              recipients - Indexed array of recipients
     *              from       - The from address. (used in MAIL FROM:),
     *                           this will be the return path
     *              headers    - Indexed array of headers, one header per array entry
     *              body       - The body of the email
     *            It can also contain any of the parameters from the connect()
     *            function
     * 
     * @param type $params
     * @return boolean
     */
    function send($params = array()){
        foreach($params as $key => $value){
            $this->set($key, $value);
	}

	if($this->is_connected()){
            // Do we auth or not? Note the distinction between the auth variable and auth() function
            if($this->auth AND !$this->authenticated){
                if(!$this->auth()) return FALSE;
            }

            $this->mail($this->from);
            if(is_array($this->recipients))
                foreach($this->recipients as $value) $this->rcpt($value);
            else $this->rcpt($this->recipients);

            if(!$this->data()) return FALSE;

            // Transparency
            $headers = str_replace(CRLF.'.', CRLF.'..', trim(implode(CRLF, $this->headers)));
            $body    = str_replace(CRLF.'.', CRLF.'..', $this->body);
            $body    = $body[0] == '.' ? '.'.$body : $body;

            $this->send_data($headers);
            $this->send_data('');
            $this->send_data($body);
            $this->send_data('.');

            $result = (substr(trim($this->get_data()), 0, 3) === '250');
				//$this->rset();
            return $result;
	}else{
            $this->errors[] = 'Not connected!';
            #echo 'Not connected!'; // check
            return FALSE;
	}
    }
		
    /**
     * Function to implement HELO cmd
     * 
     * @return boolean
     */
    function helo(){
        if(is_resource($this->connection)
            AND $this->send_data('HELO '.$this->helo)
            AND substr(trim($error = $this->get_data()), 0, 3) === '250' ){
            return TRUE;
        }else{
            $this->errors[] = 'HELO command failed, output: ' . trim(substr(trim($error),3));
            #echo 'HELO command failed, output: ' . trim(substr(trim($error),3))."<br>"; //check
            return FALSE;
	}
    }
		
    /**
     * Function to implement EHLO cmd
     * 
     * @return boolean
     */
    function ehlo(){
        if(is_resource($this->connection)
            AND $this->send_data('EHLO '.$this->helo)
            AND substr(trim($error = $this->get_data()), 0, 3) === '250' ){
            return TRUE;
        }else{
            $this->errors[] = 'EHLO command failed, output: ' . trim(substr(trim($error),3));
            #echo 'EHLO command failed, output: ' . trim(substr(trim($error),3))."<br>";
            return FALSE;
	}
    }
		
    /***************************************
        ** Function to implement RSET cmd
    ***************************************/

    function rset(){
        if(is_resource($this->connection)
            AND $this->send_data('RSET')
            AND substr(trim($error = $this->get_data()), 0, 3) === '250' ){
            return TRUE;
        }else{
            $this->errors[] = 'RSET command failed, output: ' . trim(substr(trim($error),3));
            #echo 'RSET command failed, output: ' . trim(substr(trim($error),3))."<br>";
            return FALSE;
	}
    }
		
    /***************************************
        ** Function to implement QUIT cmd
    ***************************************/

    function quit(){
        if(is_resource($this->connection)
            AND $this->send_data('QUIT')
            AND substr(trim($error = $this->get_data()), 0, 3) === '221' ){
            fclose($this->connection);
            $this->status = SMTP_STATUS_NOT_CONNECTED;
            return TRUE;
        }else{
            $this->errors[] = 'QUIT command failed, output: ' . trim(substr(trim($error),3));
            #echo 'QUIT command failed, output: ' . trim(substr(trim($error),3))."<br>";
            return FALSE;
	}
    }
		
    /***************************************
        ** Function to implement AUTH cmd
    ***************************************/

    function auth(){
        if(is_resource($this->connection)
            AND $this->send_data('AUTH LOGIN')
            AND substr(trim($error = $this->get_data()), 0, 3) === '334'
            AND $this->send_data(base64_encode($this->user))			// Send username
            AND substr(trim($error = $this->get_data()),0,3) === '334'
            AND $this->send_data(base64_encode($this->pass))			// Send password
            AND substr(trim($error = $this->get_data()),0,3) === '235' ){
            $this->authenticated = TRUE;
            return TRUE;
        }else{
            $this->errors[] = 'AUTH command failed: ' . trim(substr(trim($error),3));
            #echo 'AUTH command failed: ' . trim(substr(trim($error),3))."<br>";
            return FALSE;
	}
    }

    /***************************************
        ** Function that handles the MAIL FROM: cmd
    ***************************************/
		
    function mail($from){
        if($this->is_connected()
            AND $this->send_data('MAIL FROM:<'.$from.'>')
            AND substr(trim($this->get_data()), 0, 2) === '250' ){
            return TRUE;
        }else return FALSE;
    }

    /***************************************
        ** Function that handles the RCPT TO: cmd
    ***************************************/
		
    function rcpt($to){
        if($this->is_connected()
            AND $this->send_data('RCPT TO:<'.$to.'>')
            AND substr(trim($error = $this->get_data()), 0, 2) === '25' ){
            return TRUE;
        }else{
            $this->errors[] = trim(substr(trim($error), 3));
            #echo trim(substr(trim($error), 3))."<br>";
            return FALSE;
	}
    }

    /***************************************
        ** Function that sends the DATA cmd
    ***************************************/

    function data(){
        if($this->is_connected()
            AND $this->send_data('DATA')
            AND substr(trim($error = $this->get_data()), 0, 3) === '354' ){
            return TRUE;
        }else{
            $this->errors[] = trim(substr(trim($error), 3));
            #echo trim(substr(trim($error), 3))."<br>";
            return FALSE;
	}
    }

    /***************************************
        ** Function to determine if this object
		** is connected to the server or not.
    ***************************************/

    function is_connected(){
        return (is_resource($this->connection) AND ($this->status === SMTP_STATUS_CONNECTED));
    }

    /***************************************
        ** Function to send a bit of data
    ***************************************/

    function send_data($data){
        if(is_resource($this->connection)){
            return fwrite($this->connection, $data.CRLF, strlen($data)+2);
	}else
            return FALSE;
    }

    /***************************************
        ** Function to get data.
    ***************************************/

    function &get_data(){
        $return = '';
        $line   = '';
	$loops  = 0;

	if(is_resource($this->connection)){
            while((strpos($return, CRLF) === FALSE OR substr($line,3,1) !== ' ') AND $loops < 100){
                $line    = fgets($this->connection, 512);
                $return .= $line;
                $loops++;
            }
            return $return;
        }else
            return FALSE;
    }

    /***************************************
        ** Sets a variable
    ***************************************/
		
    function set($var, $value){
        $this->$var = $value;
	return TRUE;
    }
} // End of class
?>