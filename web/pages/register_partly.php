<?php
    session_start();
    $email = $_SESSION["email"];

    preg_match('/[a-zA-Z0-9-]+\.[a-zA-Z0-9-.]+$/', $email, $snippet);
?>

<!-- Um die Registrierung abzuschließen, muss ein Link in einer Bestätigungsmail angeklickt werden. -->

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

    <title>Registered</title>

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
                <h1>Thank you for registering.<br /> You should receive a confirmation email for activating your account.</h1>
            </div>
            

            <form action="https://<?php echo $snippet[0]?>" method="get" target="_blank"> 
            <input type="submit" class="mt-5 btn btn-lg btn-block _btn" value="Goto Email Account" />
            </form>
            <!--<p class="mt-1 text-center"><a href="https://www.web.de">email not arrived? Resend email!</a></p>-->
            <a href="../res/php/send_mail.php">Resend Email!</a> or <a href="login.php">login here!</a>

            <p class="mt-5 mb-3 text-center">&copy; 5 Gewinnt</p>
        
    </main>

    <script src="../res/js/index.js"></script>
    <script src="../res/js/themes.js"></script>


    </body>
</html>
