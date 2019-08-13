<?php
require_once '../res/php/MysqliDb.php';
require_once '../res/php/config.php';
require_once '../res/php/already_reg.php';
require_once '../res/php/helpers.php';

//Username Konventions-Regex
$username_regex = '^[a-z-A-Z-0-9_]{3,16}$';

if($_SERVER['REQUEST_METHOD'] === 'POST') {
    session_start();

    // Zur Registrierung werden ein User-Name, eine E-Mail und ein 2x eingegebenes Passwort benötigt.
    $username = filter_input(INPUT_POST, 'username', FILTER_SANITIZE_SPECIAL_CHARS);
    $email = filter_input(INPUT_POST, 'email', FILTER_SANITIZE_SPECIAL_CHARS);
    $passwd = filter_input(INPUT_POST, 'passwd', FILTER_SANITIZE_SPECIAL_CHARS);
    $passwdrep = filter_input(INPUT_POST, 'passwdrep', FILTER_SANITIZE_SPECIAL_CHARS);
    $confirm_code = randomString(20);
    
    if ($passwd === $passwdrep) {
        // Die beiden eingegebenen Passwörter stimmen überein.
        if (!empty($email) && !empty($passwd)) {
            // Check, ob der User-Name oder die E-Mail bereits vergeben ist.
            if (preg_match ('/'.$username_regex.'/', $username)) {
                $check = checkifreg($username, $email);
                if ($check== 0) {    
                    // Weder E-Mail noch User-Name bereits vergeben -> Eintrag in die Datenbank        
                    $db = MysqliDb::getInstance();
                    $data = Array ("username" => $username, "password" => password_hash($passwd, PASSWORD_DEFAULT), "email" => $email, "confirm_code" => password_hash($confirm_code, PASSWORD_DEFAULT));
                    $db->insert('user', $data);

                    //E-Mail an client verschicken
                    $_SESSION['email'] = $email;
                    $_SESSION['username'] = $username;
                    $_SESSION['confirm_code'] = $confirm_code;
                    header('Location:../res/php/send_mail.php');
                    
                } 
                else if($check == 1) {
                //User-Name bereits vergeben
                    $error = "Username is already in use";
                    
                }
                else if($check == 2) {
                    //E-Mail bereits vergeben
                    $error = "Email address is already in use";
                    
                }
            } else {
                //User-Name entspricht nicht der Konvention
                $error = "Username does not comply with the rule";
            }
        }
    }
    else {
        $error = "Your password and confirmation password do not match.";
        
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
    <body>
        <?php 
            require_once 'components/loader.php';
            require_once 'components/theme.php';
        ?>
        <main role="main" class="container">
            <form class="form-signin" action="register.php" method="post">
                <div class="text-center">
                    <h1>Register</h1>
                </div>
                <div class="form-label-group">
                    <input type="text" id="inputUsername" minlength="3" maxlength="16" required pattern="<?php echo $username_regex; ?>" title="Lower/upper case letters & numbers & underscore" class="form-control" name="username" placeholder="Username" required autofocus>
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
                <input type="password" id="inputPassword2" class="form-control" name="passwdrep" placeholder="Repeat Password" required>
                <label for="inputPassword2">Repeat Password</label>
                <p style="color:red;font-size:15px;"><i><?php 
                    if (!empty($error))
                    echo $error;
                ?></i></p>
            </div>
            

                <button class="mt-5 btn btn-lg btn-block _btn" type="submit">Register</button>
                <p class="mt-1 text-center"><a href="login.php">Already have an account? Login here!</a></p>
                <p class="mt-5 mb-3 text-center">&copy; 5 Gewinnt</p>
            </form>
        </main>

        <script src="../res/js/index.js"></script>
        <script src="../res/js/themes.js"></script>
    </body>
</html>