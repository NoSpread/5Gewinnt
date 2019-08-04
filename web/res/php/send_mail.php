<?php
require_once 'MysqliDb.php';
require_once 'config.php';
require_once 'helpers.php';
session_start();


$recipient  = $_SESSION['email'];
$username = $_SESSION['username'];

//Confirm Code des Users aus der Datenbank holen
$db = getDbInstance();
$db->where ('username', $username);
$row = $db->getOne ('user');

// Betreff
$subject = 'Complete your registration at 5Gewinnt';

// Nachricht
$message = '
<html>
    <head>
    <title>Registeration at 5Gewinnt</title>
    <style>
        body {
            
        }
    </style>
    </head>
    <body>
    <p>Hello '.$username .',<br><br>
      thank you for your registration. <br>
      Click on the link below to activate your account: <br>
      http://localhost/5Gewinnt/web/pages/register_confirmed.php?code='.$row['confirm_code'].'
      <br>
      <br>
      The link does not work, or you need help with the registration? <br>Do not hesitate to send us an e-mail: Support@5Gewinnt.de
      <br>
      <br>
      <br>
      your faithfully<br>
      5Gewinnt staff team
      </p>
	<img src="https://bilder.t-online.de/b/84/93/82/46/id_84938246/920/tid_da/eichhoernchen-geert-weggen-zeigt-die-welt-der-kleinen-nager-.jpg" alt="5Gewinnt-Logo" width="200" height="100">
    </body>
</html>
';

// für HTML-E-Mails muss der 'Content-type'-Header gesetzt werden
$header[] = 'MIME-Version: 1.0';
$header[] = 'Content-type: text/html; charset=iso-8859-1';

// zusätzliche Header
//$header[] = 'To: Simone <simone@example.com>, Andreas <andreas@example.com>';
//$header[] = 'From: Geburtstags-Erinnerungen <geburtstag@example.com>';
//$header[] = 'Cc: geburtstagsarchiv@example.com';
//$header[] = 'Bcc: geburtstagscheck@example.com';

// verschicke die E-Mail
mail($recipient, $subject, $message, implode("\r\n", $header));

header('Location:../../pages/register_partly.php');
?>