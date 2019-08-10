<?php

// Generierung eines zufälligen Strings
function randomString($n) {

	$generated_string = "";

	$domain = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ1234567890";

	$len = strlen($domain);

	for ($i = 0; $i < $n; $i++) {
		// Generierung eines zufälligen Indexes um anhand dessen Characters auszuwählen.
		$index = rand(0, $len - 1);

		// Aneinanderfügen der Characters zu einem String
		$generated_string = $generated_string . $domain[$index];
	}

	return $generated_string;
}

// Ein zufälliger Token wird erzeugt
function getSecureRandomToken() {
	$token = bin2hex(openssl_random_pseudo_bytes(16));
	return $token;
}

// Löschen der Authentifizierungs-Cookies
function clearAuthCookie() {

	unset($_COOKIE['series_id']);
	unset($_COOKIE['remember_token']);
	setcookie('series_id', null, -1, '/');
	setcookie('remember_token', null, -1, '/');
}

// Der Input wird angepasst: Whitespaces und andere Zeichen werden am Ende/Anfang des Strings entfernt.
// Maskierungszeichen werden aus dem String entfernt.
// Sonderzeichen werden in HTML-Code umgewandelt.
function clean_input($data) {
	$data = trim($data);
	$data = stripslashes($data);
	$data = htmlspecialchars($data);
	return $data;
}

// Weiterleitung auf eine andere Seite mit einem optionalen Status-Code
function redirect($url, $statusCode = 303){
	header('Location:' . $url);
	die();
}

function redirect_index($statusCode = 401) {
	define('BASE_PATH', dirname(dirname(__FILE__)));
	header('Location:'.BASE_PATH.'/index.php', TRUE, $statusCode);
}
