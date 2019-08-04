<?php

session_start();
require_once '../includes/auth_validate.php';
require_once 'config.php';

$db = getDbInstance();

$db->query('DELETE FROM game WHERE state="open" AND COALESCE(player1, player2)=' . $_SESSION['id']);

?>
