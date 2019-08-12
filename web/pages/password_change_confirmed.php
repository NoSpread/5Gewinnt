<?php
require_once '../res/php/MysqliDb.php';
require_once '../res/php/helpers.php';
require_once '../res/php/config.php';

//User könnte auch manuell den Link modifizieren, deshalb Schadcode entfernen
$password_request = filter_input(INPUT_GET, 'code');
$username = filter_input(INPUT_GET, 'usn');

$db = getDbInstance();
$db->where('username', $username);
$row = $db->get('user');

if($db->count > 0) {
    if(password_verify($password_request, $row[0]['password_request'])) {
        if($_SERVER['REQUEST_METHOD'] === 'POST') {
            $new_password = $_POST['new_password'];

            $db = MysqliDb::getInstance();
            $db->where('username', $username);
            $data = Array ('password_request' => null, 'password' => password_hash($new_password, PASSWORD_DEFAULT));
            $db->update('user', $data);

            $message1 = '<p style=color:green;>Your password has been successfully changed. <br>We will redirect you to the login page</p>';
            $redirection = 'login';
        } 
    } else {
        $message2 = 'Failed to change password.';
        $redirection = 'password_forgot';
    } 
} else {
    $message2 = 'Failed to change password.';
    $redirection = 'password_forgot';
}

?>


<!DOCTYPE html>
    <html lang="en">
    <head>
    <meta http-equiv="refresh" content="10;url=<?php echo $redirection?>.php">
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">

    <link rel="stylesheet" href="../res/css/bootstrap/bootstrap.min.css">
    <link rel="stylesheet" href="../res/css/materialdesignicons/materialdesignicons.min.css">
    <link rel="stylesheet" href="../res/css/materialdesignicons/materialdesignicons.helper.css">
    <link rel="stylesheet" href="../res/css/style.css">
    <link rel="stylesheet" id="theme" href="../res/css/light.css">

    <title>Confirm Registration</title>
    <!-- jquery | popper.js | bootstrap -->
    <script src="../res/js/jquery/jquery-3.4.1.min.js"></script>
    <script src="../res/js/popper.js/popper-1.15.0.min.js"></script>
    <script src="../res/js/bootstrap/bootstrap.js"></script>
    </head>

    <body id="body">
    <?php 
            require_once 'components/loader.php';
            require_once 'components/theme.php';
        ?>
    

    <main role="main" class="container">
            <?php
                if(isset($message2)) {
                    echo '<div><h1> '.$message2.' <br>
                    <i>We will redirect you to the password forgot page.</i></h1></div>';
               

                } else {
                    echo "<form class='form-signin' action=\"password_change_confirmed.php?code=$password_request&usn=$username\" method='post'>
                        <div class='text-center'>
                            <h1>Change password</h1>
                        </div>
                        <div class='form-label-group'>
                            <input type='password' id='inputPassword' class='form-control' name='new_password' placeholder='New Password' required autofocus>
                            <label for='inputPassword'>New Password</label>";
                        if(isset($message1)) {
                            echo $message1;
                        }
                        
                    echo "<button class='mt-5 btn btn-lg btn-block _btn' type='submit'>Set password</button>
                    </div>
                    </form>
                    <p class='mt-5 mb-3 text-center'>&copy; 5 Gewinnt</p>";
                }
            ?>
    </main>

    <script src="../res/js/index.js"></script>
    <script src="../res/js/themes.js"></script>
    </body>
</html>


