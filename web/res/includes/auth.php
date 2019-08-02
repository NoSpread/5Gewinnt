<?php
require_once '../php/config.php';
session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
	$email = filter_input(INPUT_POST, 'email');
	$passwd = filter_input(INPUT_POST, 'passwd');
	$remember = filter_input(INPUT_POST, 'remember');

	//Get DB instance.
	$db = getDbInstance();

	$db->where("email", $email);

	$row = $db->get('user');

	
	if ($db->count >= 1) {

		$db_password = $row[0]['passwort'];
		$user_id = $row[0]['id'];
		
		
		if (password_verify($passwd, $db_password)) {
			
			$_SESSION['user_logged_in'] = TRUE;
			$_SESSION['username'] = $row[0]['username'];

			if ($remember) {

				$series_id = randomString(16);
				$remember_token = getSecureRandomToken(20);
				$encryted_remember_token = password_hash($remember_token, PASSWORD_DEFAULT);


				$expiry_time = date('Y-m-d H:i:s', strtotime(' + 30 days'));

				$expires = strtotime($expiry_time);

				setcookie('series_id', $series_id, $expires, "/");
				setcookie('remember_token', $remember_token, $expires, "/");

				$db = getDbInstance();
				$db->where ('id', $user_id);

				$update_remember = array(
					'series_id'=> $series_id,
					'remember_token' => $encryted_remember_token,
					'expires' =>$expiry_time
				);
				$db->update('user', $update_remember);
			}
			//Authentication successfull redirect user
			
			header('Location:../../pages/index.php');

		} else {
			$_SESSION['login_failure'] = "Invalid user name or password";
			header('Location:../../pages/login.php');
		}

		exit;
	} else {
		$_SESSION['login_failure'] = "Invalid user name or password";
		header('Location:../../pages/login.php');
		exit;
	}

}
else {
	die('Method Not allowed');
}
