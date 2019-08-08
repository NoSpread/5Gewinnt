<?php
require_once '../php/config.php';
require_once 'remember_me.php';
session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
	$emailorusername = filter_input(INPUT_POST, 'emailorusername');
	$passwd = filter_input(INPUT_POST, 'passwd');
	$remember = filter_input(INPUT_POST, 'remember');

	//Get DB instance.
	$db = getDbInstance();
	$db->where("email", $emailorusername);
	$row = $db->get('user');

	if ($db->count >= 1) {

		$db_password = $row[0]['password'];
		$user_id = $row[0]['id'];

		if (password_verify($passwd, $db_password)) {

			$_SESSION['user_logged_in'] = TRUE;
			$_SESSION['username'] = $row[0]['username'];
			$_SESSION['id'] = $row[0]['id'];

			if ($remember) {
				// Erinnert sich an den User
				rememberMe($user_id);
			}
			//Authentication successfull redirect user
			header('Location:../../pages/index.php');

		} else {
			$_SESSION['login_failure'] = "Invalid user name or password";
			header('Location:../../pages/login.php');
			die;
		}

		die;
	} else {
		$db = getDbInstance();
		$db->where("username", $emailorusername);
		$row = $db->get('user');

		if ($db->count >= 1) {

		$db_password = $row[0]['password'];
		$user_id = $row[0]['id'];

		if (password_verify($passwd, $db_password)) {

			$_SESSION['user_logged_in'] = TRUE;
			$_SESSION['username'] = $row[0]['username'];
			$_SESSION['id'] = $row[0]['id'];

			if ($remember) {
				//Erinnert sich an den User
				rememberMe($user_id);
			}
			//Authentication successfull redirect user
			header('Location:../../pages/index.php');

		} else {
			$_SESSION['login_failure'] = "Invalid user name or password";
			header('Location:../../pages/login.php');
			die;
		}
		die;

	}
	else {
		$_SESSION['login_failure'] = "Invalid user name or password";
		header('Location:../../pages/login.php');
		die;
	}

	}

}else {
	die('Method Not allowed');
}