<?php

include_once 'MysqliDb.php';
include_once 'config.php';

$db = getDbInstance();

function spamprotect($ip) {
    $db = MysqliDb::getInstance();

    $db->where('ip', $ip);
    $res = $db->getOne('spamcheck');

    if ($db->count > 0) {
        if ($res['ip'] == $ip) 
        {
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
    } 

    else
    {
        $data = array('counter' => 1, 'ip' => $ip);
        $id = $db->insert('spamcheck', $data);
        return 0;
    } 
}

function getUserIpAddr(){
    if(!empty($_SERVER['HTTP_CLIENT_IP'])){
        //ip from share internet
        $ip = $_SERVER['HTTP_CLIENT_IP'];
    }elseif(!empty($_SERVER['HTTP_X_FORWARDED_FOR'])){
        //ip pass from proxy
        $ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
    }else{
        $ip = $_SERVER['REMOTE_ADDR'];
    }  
    return $ip;
}

spamprotect(getUserIpAddr());

echo "TEST";