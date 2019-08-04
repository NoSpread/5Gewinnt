<?php

session_start();
require_once '../includes/auth_validate.php';
require_once 'config.php';

$db = getDbInstance();

$query = $db->query('SELECT id FROM game WHERE state="ongoing" AND (player1=' . $_SESSION['id'] . ' OR player2=' . $_SESSION['id'] . ')');

if (count($query) == 0) {
	echo json_encode(array(
		'ongoing' => false,
		'id' => NULL
	));
} else {
	echo json_encode(array(
		'ongoing' => true,
		'id' => $query[0]['id']
	));
}

?>
