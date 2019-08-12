<?php
function rememberMe($user_id) {
    // Die Cookies zur Wiedererkennung des Users werden gesetzt.
    $series_id = randomString(16);
    $remember_token = getSecureRandomToken(20);
    $encryted_remember_token = password_hash($remember_token, PASSWORD_DEFAULT);

    $expiry_time = date('Y-m-d H:i:s', strtotime(' + 30 days'));

    $expires = strtotime($expiry_time);
    
    setcookie('series_id', $series_id, $expires, "/");
    setcookie('remember_token', $remember_token, $expires, "/");

    $db = getDbInstance();
    $db->where ('id', $user_id);

    $update_remember = array(
        'series_id'=> $series_id,
        'remember_token' => $encryted_remember_token,
        'expires' =>$expiry_time
    );
    $db->update('user', $update_remember);
}
?>