<?php
require_once '../php/config.php';
require_once 'remember_me.php';
require_once '../php/spam.php';
session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
	$emailorusername = filter_input(INPUT_POST, 'emailorusername', FILTER_SANITIZE_SPECIAL_CHARS);
	$passwd = filter_input(INPUT_POST, 'passwd', FILTER_SANITIZE_SPECIAL_CHARS);
	$remember = filter_input(INPUT_POST, 'remember', FILTER_SANITIZE_SPECIAL_CHARS);

	// Erhalten der Datenbankinstanz
	$db = getDbInstance();
	spamprotect(getUserIpAddr());

	$db->where("email", $emailorusername);
	$row = $db->getOne('user');

	if ($db->count >= 1) {
		// Es wurde ein passender E-Mail-Eintrag in der Datenbank gefunden.
		if($row['confirmed'] == 1) {
			// Der Account ist aktiviert.
			$db_password = $row['password'];
			$user_id = $row['id'];

			// Überprüfung des Passworts
			if (password_verify($passwd, $db_password)) {

				$_SESSION['user_logged_in'] = TRUE;
				$_SESSION['username'] = $row['username'];
				$_SESSION['id'] = $row['id'];
				// Die IP-Adresse des User wird in der Datenbank hinterlegt, falls dies noch nicht der Fall ist (ggf. aktualisiert).
				$ip = getUserIpAddr();
				$ip_old = $row['ip_v4'];
				if ($ip_old != $ip) {
					$db->where('id', $user_id);
					$data = Array('ip_v4' => $ip);
					$db->update('user', $data);
				}

				if ($remember) {
					// Erinnert sich an den User (Option ist ausgewählt)
					rememberMe($user_id);
				}
				// Authentifizierung erfolgreich -> Weiterleitung

				header('Location:../../pages/index.php');

			} else {
				// Authentifizierung nicht erfolgreich -> Passwort falsch
				$_SESSION['login_failure'] = "Invalid user name or password";
				header('Location:../../pages/login.php');
				die;
			}

			die;
		}
		else {
			// Der Account wurde noch nicht aktiviert.
			$_SESSION['login_failure'] = "Activate your Account first";
			header('Location:../../pages/login.php');
		}
		die;
	} else {
		$db = getDbInstance();
		$db->where("username", $emailorusername);
		$row = $db->getOne('user');

		if ($db->count >= 1) {
			// Es wurde ein passender User-Name-Eintrag in der Datenbank gefunden.
			if($row['confirmed'] == 1) {
				// Der Account ist aktiviert.
				$db_password = $row['password'];
				$user_id = $row['id'];

			if (password_verify($passwd, $db_password)) {

				$_SESSION['user_logged_in'] = TRUE;
				$_SESSION['username'] = $row['username'];
				$_SESSION['id'] = $row['id'];

				// Die IP-Adresse des User wird in der Datenbank hinterlegt, falls dies noch nicht der Fall ist (ggf. aktualisiert).
				$ip = getUserIpAddr();
				$ip_old = $row['ip_v4'];
				if ($ip_old != $ip) {
					$db->where('id', $user_id);
					$data = Array('ip_v4' => $ip);
					$db->update('user', $data);
				}
				if ($remember) {
					//Erinnert sich an den User
					rememberMe($user_id);
				}
				// Authentifizierung erfolgreich -> Weiterleitung
				header('Location:../../pages/index.php');

			} else {
				// Authentifizierung nicht erfolgreich -> Passwort falsch
				$_SESSION['login_failure'] = "Invalid username/email or password";
				header('Location:../../pages/login.php');
				die;
			}
		}
		else {
			// Der Account wurde noch nicht aktiviert.
			$_SESSION['login_failure'] = "Activate your Account first";
			header('Location:../../pages/login.php');
		}
		die;

	}
	else {
		// Es gibt weder eine passende E-Mail oder einen passenden User-Name in der Datenbank.
		$_SESSION['login_failure'] = "Invalid username/email or password";
		header('Location:../../pages/login.php');
		die;
	}

	}

}else {
	// Der User versucht etwas anderes als die POST-Methode anzuwenden.
	die('Method Not allowed');
}