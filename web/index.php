<?php
session_start();
require_once 'res/php/config.php';
require_once 'res/includes/auth_validate.php';
$db->getInstance();

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">

    <link rel="stylesheet" href="res/css/bootstrap/bootstrap.min.css">
    <link rel="stylesheet" href="res/css/style.css">
    <link rel="stylesheet" href="res/css/login.css">

    <title>Document</title>

    <!-- jquery | popper.js | bootstrap -->
    <script src="res/js/jquery/jquery-3.4.1.min.js"></script>
    <script src="res/js/popper.js/popper-1.15.0.min.js"></script>
    <script src="res/js/bootstrap/bootstrap.js"></script>
</head>
<body>
    <main role="main" class="container">
        <form class="form-signin">
            <div class="text-center">
                <h1>login</h1>
            </div>

            <div class="form-label-group">
                <input type="email" id="inputEmail" class="form-control" placeholder="Email Address" required autofocus>
                <label for="inputEmail">Email Address</label>
            </div>

            <div class="form-label-group">
                <input type="password" id="inputPassword" class="form-control" placeholder="Password" required>
                <label for="inputPassword">Password</label>
            </div>

            <div class="checkbox mb-3">
                <label>
                    <input type="checkbox" value="remember-me"> Remember me
                </label>
            </div>
            <button class="btn btn-lg btn-block btn-signin" type="submit">Sign in</button>
            <p id="test" class="mt-5 mb-3 text-center">&copy; 2017-2019</p>
        </form>
    </main>
</body>
</html>