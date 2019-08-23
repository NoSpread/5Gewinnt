<?php

include_once 'MysqliDb.php';
include_once 'config.php';

$username = filter_input(INPUT_POST, 'username', FILTER_SANITIZE_SPECIAL_CHARS);
$email = filter_input(INPUT_POST, 'email', FILTER_SANITIZE_SPECIAL_CHARS);


$db = getDbInstance();


// Diese Funktion überprüft, ob die eingegebene Email oder der Benutzername schon vergeben sind.
function checkIfReg($username, $email) {
    $db = MysqliDb::getInstance();

    $db->where('email', $email);
    $db->orwhere('username', $username);
    $res = $db->get('user');

    // 2 -> Die Email ist bereits vergeben.
    // 1 -> Der Benutzername ist bereits vergeben.
    // 0 -> Weder E-Mail noch Benutzername sind bereits vergeben. 
    if ($db->count > 0) {
        if ($res[0]['email'] == $email) return 2;
        return 1;
    } else return 0;
    
}

checkIfReg($username, $email);