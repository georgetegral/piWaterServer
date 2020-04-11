<?php 

function getIp() {
    $json = file_get_contents('https://api.ipify.org?format=json');
    $obj = json_decode($json);
    return $obj->ip;
}

function getLat($ip){
    $json = file_get_contents('http://ip-api.com/json/'.$ip);
    $obj = json_decode($json);
    return $obj->lat;
}

function getLon($ip){
    $json = file_get_contents('http://ip-api.com/json/'.$ip);
    $obj = json_decode($json);
    return $obj->lon;
}

function getWeatherId($lat,$lon){
    $apiKey= '7fcc45092b8560eb0f356add9bd91dad';
    $url = 'https://api.openweathermap.org/data/2.5/weather?lat='.$lat.'&lon='.$lon.'&APPID='.$apiKey;
    $json = file_get_contents($url);
    $res = json_decode($json, true);
    $res = $res["weather"];
    $res = $res[0];
    $res = $res["id"];
    return $res;
}

define("pin",2);

$ip = getIp();
$lat = getLat($ip);
$lon = getLon($ip);
$weatherId = getWeatherId($lat,$lon);
echo $weatherId;



?>