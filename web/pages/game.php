<?php
	session_start();
    require_once '../res/includes/auth_validate.php';
?>
<!DOCTYPE html>
<html lang='en'>
    <head>
        <meta charset='UTF-8'>
        <meta name='viewport' content='width=device-width, initial-scale=1.0'>
        <meta http-equiv='X-UA-Compatible' content='ie=edge'>

        <link rel='stylesheet' href='../res/css/bootstrap/bootstrap.min.css'>
        <link rel='stylesheet' href='../res/css/materialdesignicons/materialdesignicons.min.css'>
        <link rel='stylesheet' href='../res/css/materialdesignicons/materialdesignicons.helper.css'>
        <link rel='stylesheet' href='../res/css/style.css'>
        <link rel='stylesheet' id='theme' href='../res/css/light.css'>

        <title>Game Lobby</title>

        <!-- jquery | popper.js | bootstrap -->
        <script src='../res/js/jquery/jquery-3.4.1.min.js'></script>
        <script src='../res/js/popper.js/popper-1.15.0.min.js'></script>
        <script src='../res/js/bootstrap/bootstrap.js'></script>
    </head>
    <body>
        <?php
            require_once 'components/theme.php';
        ?>
        <main class="game-main">
            <div class="game-bg">
                <div class="game-hover-overlay">
                    <div class="game-hover-overlay-wrapper">
                        <div class="row">
                            <div class="col"></div>
                            <div class="col"></div>
                            <div class="col"></div>
                            <div class="col"></div>
                            <div class="col"></div>
                            <div class="col"></div>
                            <div class="col"></div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col"></div>
                    <div class="col"></div>
                    <div class="col"></div>
                    <div class="col"></div>
                    <div class="col"></div>
                    <div class="col"></div>
                    <div class="col"></div>
                </div>
                <div class="row">
                    <div class="col"></div>
                    <div class="col"></div>
                    <div class="col"></div>
                    <div class="col"></div>
                    <div class="col"></div>
                    <div class="col"></div>
                    <div class="col"></div>
                </div>
                <div class="row">
                    <div class="col"></div>
                    <div class="col"></div>
                    <div class="col"></div>
                    <div class="col"></div>
                    <div class="col"></div>
                    <div class="col"></div>
                    <div class="col"></div>
                </div>
                <div class="row">
                    <div class="col"></div>
                    <div class="col"></div>
                    <div class="col"></div>
                    <div class="col"></div>
                    <div class="col"></div>
                    <div class="col"></div>
                    <div class="col"></div>
                </div>
                <div class="row">
                    <div class="col"></div>
                    <div class="col"></div>
                    <div class="col"></div>
                    <div class="col"></div>
                    <div class="col"></div>
                    <div class="col"></div>
                    <div class="col"></div>
                </div>
                <div class="row">
                    <div class="col"></div>
                    <div class="col"></div>
                    <div class="col"></div>
                    <div class="col"></div>
                    <div class="col"></div>
                    <div class="col"></div>
                    <div class="col"></div>
                </div>
            </div>
        </main>
        <script src='../res/js/index.js'></script>
        <script src='../res/js/themes.js'></script>
        <script src='../res/js/browser.js'></script>
    </body>