<?php

session_start();
require_once '../includes/auth_validate.php';
require_once 'config.php';

$db = getDbInstance();

$db->query('DELETE FROM game WHERE (player1 IS NULL OR player2 IS NULL) AND COALESCE(player1, player2)=' . $_SESSION['id']);

?>
