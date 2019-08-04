<?php

session_start();
require_once '../includes/auth_validate.php';
require_once 'config.php';

$db = getDbInstance();

$query = $db->query('SELECT id FROM game WHERE COALESCE(player1, player2)=' . $_SESSION['id'] . ' AND (player1 IS NULL OR player2 IS NULL)');

if (count($query) == 0) {
	echo 0;
} else {
	echo 1;
}

?>
