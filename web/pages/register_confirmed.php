<?php
require_once '../res/php/MysqliDb.php';
require_once '../res/php/helpers.php';
require_once '../res/php/config.php';

//User könnte auch manuell den Link modifizieren, deshalb Schadcode entfernen
$confirm_code = filter_input(INPUT_GET, 'code');

//User identifizieren
$db = getDbInstance();
$db->where('confirm_code', password_hash($confirm_code, PASSWORD_DEFAULT));
$data = Array ('confirm_code' => null, 'confirmed' => '1');
$db->update('user', $data);

if($db->count > 0){
    $message = 'Your account has been successfully activated.<br> We look forward to welcoming you in the lobby.';
    $redirection = 'login';
    
    
} else {
    $message = 'Account activation failed. To activate an account, you must first create one.';
    $redirection = 'register'; 
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

    <body>
        <?php 
            require_once 'components/loader.php';
            require_once 'components/theme.php';
        ?>

    <main role="main" class="container">
            <div>
                <h1><?php echo $message; ?><br>
                <i>We will redirect you to the <?php echo $redirection;?> page</i></h1>
            </div>
            

            <form action="<?php echo $redirection.'.php';?>" method="get"> 
            <input type="submit" class="mt-5 btn btn-lg btn-block _btn" value="Goto <?php echo $redirection;?> page" />
            </form>
        

            <p class="mt-5 mb-3 text-center">&copy; 5 Gewinnt</p>
        
    </main>

    <script src="../res/js/index.js"></script>
    <script src="../res/js/themes.js"></script>
    </body>
</html>

