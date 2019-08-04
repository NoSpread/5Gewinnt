<?php

session_start();
require_once '../includes/auth_validate.php';
require_once 'config.php';

$playerId = $_SESSION['id'];

$db = getDbInstance();

$query = $db->query("SELECT id FROM game WHERE state='open' AND (player1=$playerId OR player2=$playerId)");

if (count($query) == 0) {
	echo 0;
} else {
	echo 1;
}

?>
