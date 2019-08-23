<?php
require_once '../res/php/config.php';

session_start();

// Es existiert eine Session -> User ist eingeloggt
if (isset($_SESSION['user_logged_in']) && $_SESSION['user_logged_in'] === TRUE) {
    header('Location: index.php');
}

$token = bin2hex(openssl_random_pseudo_bytes(16));

// remember-me-Function
if (isset($_COOKIE['series_id']) && isset($_COOKIE['remember_token'])) {

    // Erhalte die (Anmelde-)Daten des Users mittels Cookies
    $series_id = filter_input(INPUT_COOKIE, 'series_id', FILTER_SANITIZE_SPECIAL_CHARS);
    $remember_token = filter_input(INPUT_COOKIE, 'remember_token', FILTER_SANITIZE_SPECIAL_CHARS);
    $db = getDbInstance();
    // Erhalte den User mittels seiner series ID
    $db->where("series_id", $series_id);
    $row = $db->get('user');


    if ($db->count >= 1) {

        // User wurde gefunden - der Remember-Token wird verifiziert
        if (password_verify($remember_token, $row[0]['remember_token'])) {
            // Falls die Ablaufzeit verändert wurde, wird erneut verifiziert.

            $expires = strtotime($row[0]['expires']);

            if (strtotime(date('Y-m-d H:i:s')) > $expires) {

                // Der Remember-Cookie ist abgelaufen.
                clearAuthCookie();
                header('Location:login.php');
                exit;
            }

            $_SESSION['user_logged_in'] = TRUE;
            header('Location:index.php');
            exit;
        } else {
            clearAuthCookie();
            header('Location:login.php');
            exit;
        }
    } else {
        clearAuthCookie();
        header('Location:login.php');
        exit;
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

        <title>Login</title>

        <!-- jquery | popper.js | bootstrap -->
        <script src="../res/js/jquery/jquery-3.4.1.min.js"></script>
        <script src="../res/js/popper.js/popper-1.15.0.min.js"></script>
        <script src="../res/js/bootstrap/bootstrap.js"></script>
    </head>
    <body>
        <?php 
            require_once 'components/loader.php';
            require_once 'components/theme.php';
        ?>

        <main role="main" class="container">
            <form class="form-signin" action="../res/includes/auth.php" method="post">
                <div class="text-center">
                    <h1>Login</h1>
                </div>

                <div class="form-label-group">
                    <input type="text" id="inputEmailorUsername" class="form-control" name="emailorusername" placeholder="Email or Username" required autofocus>
                    <label for="inputEmailorUsername">Email or Username</label>
                </div>

                <div class="form-label-group">
                    <input type="password" id="inputPassword" class="form-control" name="passwd" placeholder="Password" required>
                    <label for="inputPassword">Password</label>
                </div>

                <div class="mb-1">
                    <div class="row">
                        <div class="col">
                            <input id="rememberMe" type="checkbox" class="checkbox" name="remember" value="remember-me">
                            <label for="rememberMe">Remember me</label>
                        </div>
                        <div class="col d-flex justify-content-end">
                            <i><a style="color: #2269c3;" href="password_forgot.php">Forgot password?</a></i>
                        </div>
                    </div>
                </div>
                <div class="pt-1 pb-3 err"><?php if(isset($_SESSION['login_failure'])) { echo $_SESSION['login_failure']; } ?></div>
                <button class="mt-1 btn btn-lg btn-block _btn" type="submit">Sign in</button>
                <p class="mt-1 text-center"><a href="register.php">Don't have an account? Register here!</a></p>
                <p class="mt-5 mb-3 text-center">&copy; 5 Gewinnt</p>
            </form>
        </main>

        <script src="../res/js/index.js"></script>
        <script src="../res/js/themes.js"></script>
        <script src='../res/js/browser.js'></script>
    </body>
</html>

<?php 
if(isset($_SESSION['login_failure'])) {
    $_SESSION['login_failure'] = null;
}
?>