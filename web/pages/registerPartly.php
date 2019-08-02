<?php
    session_start();
    $email = $_SESSION["email"];

    //change explode to regex term
    $snippet = explode("@", $email);
    $snippet = explode(".", $snippet[1]);
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

    <title>Registered</title>

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
            <div>
                <h1>Thank you for registering.<br /> You should receive a confirmation email for activating your account.</h1>
            </div>
            

            <form class="" action="https://google.com/search" method="get" target="_blank"> 
            <input type="hidden" name="q" value="<?php echo "$snippet[0]"?> mail login" />
            <input type="submit" class="mt-5 btn btn-lg btn-block btn-signin" value="Goto email Account" />
            </form>
           <!--<p class="mt-1 text-center"><a href="https://www.web.de">email not arrived? Resend email!</a></p>-->
           <a href="../res/php/sendmail.php">email not arrived? Resend email!</a>

            <p class="mt-5 mb-3 text-center">&copy; 5 Gewinnt</p>
        
    </main>

    <script src="../res/js/index.js"></script>
    <script src="../res/js/themes.js"></script>


    </body>
</html>
