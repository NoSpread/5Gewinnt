<?php
session_start();
//require_once 'res/php/config.php';
//require_once 'res/includes/auth_validate.php';
//$db->getInstance();

	//DB request, if Game exists and is ready

	if(TRUE){ //DB request
		echo 'game ready';
		echo '<a href="#" onclick=joingame()>Join Game</a>';
	} else {
		echo 'no game found';
		echo 'maybe its allready running?';
	}
?>
