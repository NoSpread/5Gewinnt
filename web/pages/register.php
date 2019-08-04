<?php
require_once '../res/php/MysqliDb.php';
require_once '../res/php/config.php';
require_once '../res/php/already_reg.php';
require_once '../res/php/helpers.php';

if($_SERVER['REQUEST_METHOD'] === 'POST') {
    session_start();
    $username = filter_input(INPUT_POST, 'username');
    $email = filter_input(INPUT_POST, 'email');
    $passwd = filter_input(INPUT_POST, 'passwd');
    $confirm_code = randomString(20);
    $error = filter_input(INPUT_POST, 'error');
    
    
    if (!empty($email) && !empty($passwd)) {
        
        $check = checkifreg(null, $email);
        if ($check== 0) {            
            $db = MysqliDb::getInstance();
            $data = Array ("username" => $username, "passwort" => password_hash($passwd, PASSWORD_DEFAULT), "email" => $email, "confirm_code" => $confirm_code);
            $db->insert('user', $data);

            //E-Mail an client verschicken
            $_SESSION["email"] = $email;
            $_SESSION["username"] = $username;
            header('Location:../res/php/send_mail.php');
            
        } else {
            //email vergeben

            //String muss jedoch noch ausgegeben werden
            $error = "E-Mail bereits registriert";
            
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

    <title>Register</title>

    <!-- jquery | popper.js | bootstrap -->
    <script src="../res/js/jquery/jquery-3.4.1.min.js"></script>
    <script src="../res/js/popper.js/popper-1.15.0.min.js"></script>
    <script src="../res/js/bootstrap/bootstrap.js"></script>
</head>
<body id="body">
    <div class="loader">
        <div class="spinner"><i class="mdi mdi-48px mdi-spin mdi-loading"></i></div>
    </div>
    <div class="skewed-top"></div>
    <div class="skewed-bottom"></div>

    <div class="themes">
        <button id="theme" class="btn-theme mdi mdi-24px mdi-weather-sunny"></button>
    </div>

    <main role="main" class="container">
        <form class="form-signin" action="register.php" method="post">
            <div class="text-center">
                <h1>Register</h1>
            </div>
            <div class="form-label-group">
                <input type="text" id="inputUsername" class="form-control" name="username" placeholder="Username" required autofocus>
                <label for="inputUsername">Username</label>
            </div>
            <div class="form-label-group">
                <input type="email" id="inputEmail" class="form-control" name="email" placeholder="Email Address" required autofocus>
                <label for="inputEmail">Email Address</label>
            </div>

            <div class="form-label-group">
                <input type="password" id="inputPassword" class="form-control" name="passwd" placeholder="Password" required>
                <label for="inputPassword">Password</label>
            </div>

            <div class="form-label-group">
                <input type="password" id="inputPassword2" class="form-control" placeholder="Repeat Password" required>
                <label for="inputPassword2">Repeat Password</label>
            </div>

            <button class="mt-5 btn btn-lg btn-block btn-signin" type="submit">Register</button>
            <p class="mt-1 text-center"><a href="login.php">Already have an account? Login here!</a></p>
            <p class="mt-5 mb-3 text-center">&copy; 5 Gewinnt</p>
        </form>
    </main>

    <script src="../res/js/index.js"></script>
    <script src="../res/js/themes.js"></script>
</body>
</html>