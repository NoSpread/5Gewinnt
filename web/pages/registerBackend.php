<?php
require_once '../res/php/config.php';

if (isset($_SESSION['user_logged_in']) && $_SESSION['user_logged_in'] === TRUE) {
    header('Location:index.php');
}

/*
* ADD REGISTER FUNCTION
*/