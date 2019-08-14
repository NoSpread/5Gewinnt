<?php

// Dieses Skript entfernt das offene Spiel des Nutzers, sofern dieses existiert.

session_start();
require_once '../includes/auth_validate.php';
require_once 'config.php';

$playerId = $_SESSION['id'];

$db = getDbInstance();

$db->query("DELETE FROM game WHERE state='open' AND COALESCE(player1, player2)=$playerId");

?>
