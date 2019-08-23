<?php
require_once '../res/php/MysqliDb.php';
require_once '../res/php/config.php';
require_once '../res/php/helpers.php';
session_start();


if($_SERVER['REQUEST_METHOD'] === 'POST') {
    $emailorusername  = filter_input(INPUT_POST, 'emailorusername', FILTER_SANITIZE_SPECIAL_CHARS)

    if(strpos($emailorusername, '@')) {
        // fill email
        $email = $emailorusername;

        $db = getDbInstance();
        $db->where('email', $email);
        $row = $db->getOne ('user');
        if($db->count > 0) {
            // fill username
            $username = $row['username'];


            //temporary put in new variable
            $password_request = randomString(20);

            $db = MysqliDb::getInstance();
            $db->where('username', $username);
            $data = Array ("password_request" => password_hash($password_request, PASSWORD_DEFAULT));
            $db->update('user', $data);

            // Betreff
            $subject = 'Reset your password at 5Gewinnt';

            // Nachricht
            $message = '
            <html>
                <head>
                <title>Reset password</title>
                <style>
                    body {

                    }
                </style>
                </head>
                <body>
                <p>Hello '.$username .',<br><br>
                Click on the link below to reset your password: <br>
                http://localhost/5Gewinnt/web/pages/password_change_confirmed.php?code='.$password_request.'&usn='.$username.'
                <br>
                <br>
                The link does not work, or you need help with reseting your password? <br>Do not hesitate to send us an e-mail: Support@5Gewinnt.de
                <br>
                You didn\'t want to reset your password? Then you can just ignore this email!
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



            // verschicke die E-Mail
            mail($email, $subject, $message, implode("\r\n", $header));
            $message = 'We have send you an email.';
            $mess_color = 'green';












        } else {
            $message = 'Invalid username or password';
            $mess_color = 'red';
        }

    }else {
        // fill username
        $username = $emailorusername;

        $db = getDbInstance();
        $db->where('username', $username);
        $row = $db->getOne ('user');
        if($db->count > 0) {
            // fill email
            $email = $row['email'];





            //temporary put in new function
            $password_request = randomString(20);

            $db = MysqliDb::getInstance();
            $db->where('username', $username);
            $data = Array ("password_request" => password_hash($password_request, PASSWORD_DEFAULT));
            $db->update('user', $data);

                    // Betreff
            $subject = 'Reset your password at 5Gewinnt';

            // Nachricht
            $message = '
            <html>
                <head>
                <title>Reset password</title>
                <style>
                    body {

                    }
                </style>
                </head>
                <body>
                <p>Hello '.$username .',<br><br>
                Click on the link below to reset your password: <br>
                http://localhost/5Gewinnt/web/pages/password_change_confirmed.php?code='.$password_request.'&usn='.$username.'
                <br>
                <br>
                The link does not work, or you need help with reseting your password? <br>Do not hesitate to send us an e-mail: Support@5Gewinnt.de
                <br>
                You didn\'t want to reset your password? Then you can just ignore this email!
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



            // verschicke die E-Mail
            mail($email, $subject, $message, implode("\r\n", $header));
            $message = 'We have send you an email.';
            $mess_color = 'green';























        } else {
            $message = 'Invalid username or password';
            $mess_color = 'red';
        }

    }

}


?>















<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta http-equiv="X-UA-Compatible" content="ie=edge">

        <link rel="stylesheet" href="../res/css/bootstrap/bootstrap.min.css">
        <link rel="stylesheet" href="../res/css/materialdesignicons/materialdesignicons.min.css">
        <link rel="stylesheet" href="../res/css/materialdesignicons/materialdesignicons.helper.css">
        <link rel="stylesheet" href="../res/css/style.css">
        <link rel="stylesheet" id="theme" href="../res/css/light.css">

        <title>forgot password</title>

        <!-- jquery | popper.js | bootstrap -->
        <script src="../res/js/jquery/jquery-3.4.1.min.js"></script>
        <script src="../res/js/popper.js/popper-1.15.0.min.js"></script>
        <script src="../res/js/bootstrap/bootstrap.js"></script>
    </head>
    <body>
        <div class="loader">
            <div class="spinner"><i class="mdi mdi-48px mdi-spin mdi-loading"></i></div>
        </div>
        <div class="skewed-top"></div>
        <div class="skewed-bottom"></div>

        <div class="themes">
            <button class="btn-theme mdi mdi-24px mdi-weather-sunny"></button>
        </div>

        <main role="main" class="container">
            <form class="form-signin" action="password_forgot.php" method="post">
                <div class="text-center">
                    <h1>Reset password</h1>
                </div>

            <div class="form-label-group">
                <input type="text" id="inputEmailorUsername" class="form-control" name="emailorusername" placeholder="Email or Username" required autofocus>
                <label for="inputEmail">Email or Username</label>
            </div>
            <p style="color:<?php echo $mess_color;?>;font-size:15px;"><i><?php
                    if (!empty($message))
                    echo $message;
                ?><i></p>
                <button class="mt-5 btn btn-lg btn-block _btn" type="submit">Reset password</button>
                <p class="mt-1 text-center"><a href="login.php">You remember your password? Login here!</a></p>
                <p class="mt-5 mb-3 text-center">&copy; 5 Gewinnt</p>
            </form>
        </main>

        <script src="../res/js/index.js"></script>
        <script src="../res/js/themes.js"></script>
    </body>
</html>
