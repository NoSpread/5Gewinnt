<?php
require_once 'MysqliDb.php';
require_once 'config.php';
require_once 'helpers.php';
session_start();


$recipient  = $_SESSION['email'];
$username = $_SESSION['username'];
$confirm_code = $_SESSION['confirm_code'];

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
      http://localhost/5Gewinnt/web/pages/register_confirmed.php?code='.$confirm_code.'&usn='.$username.'
      <br>
      <br>
      The link does not work, or you need help with the registration? <br>Do not hesitate to send us an e-mail: Support@5Gewinnt.de
      <br>
      <br>
      <br>
      your faithfully<br>
      5Gewinnt staff team
      </p>
    </body>
</html>
';

// für HTML-E-Mails muss der 'Content-type'-Header gesetzt werden
$header[] = 'MIME-Version: 1.0';
$header[] = 'Content-type: text/html; charset=iso-8859-1';


// Verschicke der E-Mail
mail($recipient, $subject, $message, implode("\r\n", $header));

header('Location:../../pages/register_partly.php');
?>