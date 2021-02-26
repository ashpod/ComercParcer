<?php
set_time_limit (0);

function curl_content($url)
{
    $ch = curl_init($url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
// curl_setopt($ch, CURLOPT_HEADER, false);
    curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (Windows; U; WinNT; en; rv:1.0.2) Gecko/20030311 Beonex/0.8.2-stable');
//curl_setopt($ch, CURLOPT_COOKIEJAR, $cookiefiles);
//curl_setopt($ch, CURLOPT_NOBODY,true);
//curl_setopt($ch, CURLOPT_COOKIEFILE, $cookiefiles);

    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
    curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
//////курл
    $html = curl_exec($ch);
    return $html;
    unset($url);
}
$url="http://erau.unba.org.ua/";
$params = array(
    'limit' => '60000',
    'offset'=> '0',
    'order[surname]' => 'ASC',
    'addation[probono]' => '0',
    'foreigner'=> '0',
    );
$urlparams = $url.'search?'.http_build_query($params);
//print_r($urlparams);
//вытягиваем html с id/
$html=curl_content($urlparams);
preg_match_all("/\"id\"\:\"(\d{0,6})/", $html,$resultid);
//var_dump($resultid[1]);
//$resultid[1]  массив с id пользователями
foreach ($resultid[1] as $res) {
    $zap=array();
    $url = "http://erau.unba.org.ua/profile/$res";
    $html = curl_content($url);
    preg_match("/<title>(\S{0,}\s\S{0,}\s\S{0,})/", $html,$name);
    $zap[]=$name[1];
    var_dump($name[1]);
    preg_match("/\<a href\=\"tel:(.{0,})\"\>/", $html,$tel);
    //$tel[1] - телефон
    $zap[]=$tel[1];
    var_dump($tel[1]);
    preg_match("/\<a href\=\"mailto\:(.{0,})\"\>/", $html,$email);
    //$email[1] - телефон
    $zap[]=$email[1];
    var_dump($email[1]);

    $fp = fopen('file.csv', 'a+');
    fputcsv($fp, $zap, ';', '"');
    fclose($fp);
}
