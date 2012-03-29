<?php

//$result = file_get_contents("http://www.undercover-network.nl/forum/forum.php");

$ch = curl_init("http://www.undercover-network.nl/forum/forum.php");

$header = array();
$header[0]  = "Accept: text/xml,application/xml,application/xhtml+xml,";
$header[0] .= "text/html;q=0.9,text/plain;q=0.8,image/png,*/*;q=0.5";
$header[]   = "Cache-Control: max-age=0";
$header[]   = "Connection: keep-alive";
$header[]   = "Keep-Alive: 300";
$header[]   = "Accept-Charset: ISO-8859-1,utf-8;q=0.7,*;q=0.7";
$header[]   = "Accept-Language: en-us,en;q=0.5";
$header[]   = "Pragma: "; // browsers keep this blank.

curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (Windows; U; Windows NT 5.2; en-US; rv:1.8.1.7) Gecko/20070914 Firefox/2.0.0.7');
curl_setopt($ch, CURLOPT_HTTPHEADER, $header);
curl_setopt($ch, CURLOPT_AUTOREFERER, true);
curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);


/*curl_setopt($ch, CURLOPT_USERAGENT, $_SERVER['HTTP_USER_AGENT']);
curl_setopt($ch, CURLOPT_AUTOREFERER, true);
curl_setopt($ch, CURLOPT_COOKIESESSION, true);
curl_setopt($ch, CURLOPT_FAILONERROR, false);
curl_setopt($ch, CURLOPT_FOLLOWLOCATION, false);
curl_setopt($ch, CURLOPT_FRESH_CONNECT, true);
//curl_setopt($ch, CURLOPT_HEADER, true);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 30);*/

$result = curl_exec($ch);
curl_close($ch);

echo $result;

?>