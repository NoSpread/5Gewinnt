<?php
require_once '../res/php/config.php';

if (isset($_SESSION['user_logged_in']) && $_SESSION['user_logged_in'] === TRUE) {
    header('Location:index.php');
}

$token = bin2hex(openssl_random_pseudo_bytes(16));

//remember me function
if (isset($_COOKIE['series_id']) && isset($_COOKIE['remember_token'])) {

    //Get user credentials from cookies.
    $series_id = filter_var($_COOKIE['series_id']);
    $remember_token = filter_var($_COOKIE['remember_token']);
    $db = getDbInstance();
    //Get user By serirs ID : 
    $db->where("series_id", $series_id);
    $row = $db->get('admin_accounts');


    if ($db->count >= 1) {

        //User found. verify remember token
        if (password_verify($remember_token, $row[0]['remember_token'])) {
            //Verify if expiry time is modified. 

            $expires = strtotime($row[0]['expires']);

            if (strtotime(date('Y-m-d H:i:s')) > $expires) {

                //Remember Cookie has expired. 
                clearAuthCookie();
                header('Location:login.php');
                exit;
            }

            $_SESSION['user_logged_in'] = TRUE;
            $_SESSION['admin_type'] = $row[0]['admin_type'];
            header('Location:index.php');
            exit;
        } else {
            clearAuthCookie();
            header('Location:login.php');
            exit;
        }
    } else {
        clearAuthCookie();
        header('Location:login.php');
        exit;
    }
}