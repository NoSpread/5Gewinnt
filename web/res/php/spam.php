<?php

include_once 'MysqliDb.php';
include_once 'config.php';

function spamprotect($ip) {
    $db = MysqliDb::getInstance();
	
	$db->query('DELETE FROM spamcheck WHERE timestamp < (NOW() - INTERVAL 30 MINUTE)');

    $db->where('ip', $ip);
    $res = $db->getOne('spamcheck');

    if ($db->count > 0) {
        // Wenn die IP-Adresse bereits in der Datenbank hinterlegt ist, wird der Counter für jedes Neuladen hochgezählt.
        $db->where('ip', $ip);
        $res = $db->getOne('spamcheck');
        $counter = $res['counter'] + 1;

        $data = array('counter' => $counter , 'ip' => $ip);
        $id = $db->update('spamcheck', $data);

        if ($counter > 10) {
            http_response_code(403);
            die;
        }
    } 
    else
    {
        // Ist die IP-Adresse noch nicht in der Datenbank hinterlegt, so wird ein Eintrag erstellt.
        $data = array('counter' => 1, 'ip' => $ip);
        $id = $db->insert('spamcheck', $data);
    } 
}

function getUserIpAddr(){
    if(!empty($_SERVER['HTTP_CLIENT_IP'])){
        // IP von einem share internet
        $ip = $_SERVER['HTTP_CLIENT_IP'];
    }elseif(!empty($_SERVER['HTTP_X_FORWARDED_FOR'])){
        // IP weitergegeben über einen Proxy-Server
        $ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
    }else{
        $ip = $_SERVER['REMOTE_ADDR'];
    }  
    return $ip;
}
