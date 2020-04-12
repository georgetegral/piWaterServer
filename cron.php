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

function changeLocale($ip){
    $ipInfo = file_get_contents('http://ip-api.com/json/' . $ip);
    $ipInfo = json_decode($ipInfo);
    $timezone = $ipInfo->timezone;
    date_default_timezone_set($timezone);
}

function getWeatherId($lat,$lon){
    $apiKey= '7fcc45092b8560eb0f356add9bd91dad';
    $url = 'https://api.openweathermap.org/data/2.5/weather?lat='.$lat.'&lon='.$lon.'&APPID='.$apiKey;
    $json = file_get_contents($url);
    $res = json_decode($json, true);
    $res = $res["weather"];
    $res = $res[0];
    $res = $res["id"];
    $res = (string)$res;
    return $res;
}

function water($pin){
    exec("sudo gpio mode ".$pin." in");
    sleep(7);
    exec("sudo gpio mode ".$pin." out");
}

function saveToDB($watered){
    $servername = "localhost";
    $username = "admin";
    $password = "pajarito";
    $database = "logPiWater";
    // Create connection
    $conn = new mysqli($servername, $username, $password, $database);
    // Check connection
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    } else {
        $timestamp = new DateTime();
        $timestamp = $timestamp->getTimestamp();
        $timestamp = date('Y-m-d H:i:s',$timestamp);
        $sql="INSERT INTO logs (time, watered) VALUES ('$timestamp', '$watered')";
        if ($conn->query($sql) === TRUE) {
            //echo "New record created successfully";
        } else {
            echo "Error: " . $sql . "<br>" . $conn->error;
        }
    }
}

//Cambiar el número para cambiar el pin de GPIO
define("pin",2);

$ip = getIp();
changeLocale($ip);
$lat = getLat($ip);
$lon = getLon($ip);
$id = getWeatherId($lat,$lon);
$watered = 0;
if( $id[0] == "2" || $id[0] == "3" || $id[0] == "5" || $id[0] == "6"){
    if( $id== "210" || $id == "211" || $id == "212" || $id == "221"){
        water(pin);
        $watered = 1;
    }
} else {
    water(pin);
    $watered = 1;
}
saveToDB($watered);

?>