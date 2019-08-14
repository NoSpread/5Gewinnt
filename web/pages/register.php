<?php
require_once '../res/php/MysqliDb.php';
require_once '../res/php/config.php';
require_once '../res/php/already_reg.php';
require_once '../res/php/helpers.php';
require_once '../res/php/spam.php';

//Username Konventions-Regex
$username_regex = '^[a-z-A-Z-0-9_]{3,16}$';

if($_SERVER['REQUEST_METHOD'] === 'POST') {
    session_start();

    // Zur Registrierung werden ein User-Name, eine E-Mail und ein 2x eingegebenes Passwort benötigt.
    $username = filter_input(INPUT_POST, 'username', FILTER_SANITIZE_SPECIAL_CHARS);
    $email = filter_input(INPUT_POST, 'email', FILTER_SANITIZE_SPECIAL_CHARS);
    $passwd = filter_input(INPUT_POST, 'passwd', FILTER_SANITIZE_SPECIAL_CHARS);
    $passwdrep = filter_input(INPUT_POST, 'passwdrep', FILTER_SANITIZE_SPECIAL_CHARS);

    $name = filter_input(INPUT_POST, 'name', FILTER_SANITIZE_SPECIAL_CHARS);
    $age = filter_input(INPUT_POST, 'age', FILTER_SANITIZE_SPECIAL_CHARS);
    $gender = filter_input(INPUT_POST, 'gender', FILTER_SANITIZE_SPECIAL_CHARS);

    $confirm_code = randomString(20);
    
    if ($passwd === $passwdrep) {
        // Die beiden eingegebenen Passwörter stimmen überein.
        spamprotect(getUserIpAddr());
        if (!empty($email) && !empty($passwd)) {
            // Check, ob der User-Name oder die E-Mail bereits vergeben ist.
            if (preg_match ('/'.$username_regex.'/', $username)) {
                $check = checkifreg($username, $email);
                if ($check== 0) {    
                    // Weder E-Mail noch User-Name bereits vergeben -> Eintrag in die Datenbank        
                    $db = MysqliDb::getInstance();
                    $data = Array ("username" => $username, "password" => password_hash($passwd, PASSWORD_DEFAULT), "email" => $email, "confirm_code" => password_hash($confirm_code, PASSWORD_DEFAULT), "name" => $name, "age" => $age, "gender" => $gender);
                    $db->insert('user', $data);

                    //Avatar in Datenbank speichern
                    $bild_daten_tmpname = $_FILES['bild_daten']['tmp_name'];
                    $bild_daten_name = $_FILES['bild_daten']['name'];
                    $bild_daten_type = $_FILES['bild_daten']['type'];
                    $bild_daten_size = $_FILES['bild_daten']['size'];


                    if (!empty($bild_daten_tmpname)) {
                        if (( $bild_daten_type == "image/gif" ) || ($bild_daten_type == "image/pjpeg") || ($bild_daten_type == "image/jpeg") || ($bild_daten_type == "image/png") ) {

                        $sql  = "INSERT INTO `avatar`";
                        $sql .= "(`img_data` ,`img_name` ,`img_type` ,`img_size`)";
                        $sql .= " VALUES('";
                        $dateihandle = fopen($bild_daten_tmpname, "r");
                        $bild_daten = mysqli_real_escape_string($sqli, fread($dateihandle, filesize($bild_daten_tmpname)));
                        $sql .= $bild_daten;
                        $sql .= "','";
                        $sql .= mysqli_real_escape_string($sqli, htmlspecialchars($bild_daten_name));
                        $sql .= "','";
                        $sql .= mysqli_real_escape_string($sqli, htmlspecialchars($bild_daten_type));
                        $sql .= "','";
                        $sql .= $bild_daten_size * 1;
                        $sql .= "');";
                        mysqli_query($sqli, $sql) or die(mysqli_error($sqli));
                        } 
                    } 


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
        
        <style type="text/css">
            span {
                color:red;
            }
        </style>

    </head>
    <body>
        <?php 
            require_once 'components/loader.php';
            require_once 'components/theme.php';
        ?>
        <main role="main" class="container">
            <form class="form-signin" action="register.php" method="post" enctype="multipart/form-data">
                <div class="text-center">
                    <h1>Register</h1>
                </div>
                <div class="form-label-group">
                    <input type="text" id="inputUsername" minlength="3" maxlength="16" required pattern="<?php echo $username_regex; ?>" title="Lower/upper case letters & numbers & underscore" class="form-control" name="username" placeholder="Username" required autofocus>
                    <label for="inputUsername">Username <span>*</span></label>
                </div>
                <div class="form-label-group">
                    <input type="email" id="inputEmail" class="form-control" name="email" placeholder="Email Address" required autofocus>
                    <label for="inputEmail">Email Address <span>*</span></label>
                </div>
                <div class="form-label-group">
                    <input type="password" id="inputPassword" class="form-control" name="passwd" placeholder="Password" required>
                    <label for="inputPassword">Password <span>*</span></label>
                </div>
                 <div class="form-label-group">
                <input type="password" id="inputPassword2" class="form-control" name="passwdrep" placeholder="Repeat Password" required>
                <label for="inputPassword2">Repeat Password <span>*</span></label>
                <p style="color:red;font-size:15px;"><i><?php 
                    if (!empty($error))
                    echo $error;
                ?></i></p>
                </div>
                <div class="form-label-group">
                    <input type="text" id="inputName" maxlength="50" class="form-control" name="name" placeholder="Name">
                    <label for="inputName">Name</label>
                </div>
                <div class="form-label-group">
                    <input type="text" id="inputAge" maxlength="3" class="form-control" name="age" placeholder="Age">
                    <label for="inputAge">Age</label>
                </div>
                <div class="form-label-group">
                    <select name="gender">
                        <option value="">undefined</option>
                        <option value="female">Female</option>
                        <option value="male">Male</option>
                        <option value="diverse">Diverse</option>
                    </select>
                    <label for="inputGender">Gender</label>
                </div>
                <div class="form-label-group">
                <input type="file" name="bild_daten" Size="20">
                <label for="inputAvatar">Avatar</label>
                </div>
            

                <button class="mt-5 btn btn-lg btn-block _btn" type="submit">Register</button>
                <p class="mt-1 text-center"><a href="login.php">Already have an account? Login here!</a></p>
                <p><i><span>* </span>Denotes required fields.</i></p>                
                <p class="mt-5 mb-3 text-center">&copy; 5 Gewinnt</p>
            </form>
        </main>

        <script src="../res/js/index.js"></script>
        <script src="../res/js/themes.js"></script>
    </body>
</html>