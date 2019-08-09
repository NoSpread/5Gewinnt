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

        <title>Profile</title>

        <!-- jquery | popper.js | bootstrap -->
        <script src="../res/js/jquery/jquery-3.4.1.min.js"></script>
        <script src="../res/js/popper.js/popper-1.15.0.min.js"></script>
        <script src="../res/js/bootstrap/bootstrap.js"></script>
    </head>
    <body>
            <div class="sidebar">
                <div class="sidebar-btn">
                    <button class="sidebar-_btn mdi mdi-24px mdi-chevron-right"></button>
                </div>
                <div class="sidebar-header">5 GEWINNT</div>
                <div class="sidebar-content">
                    <button class="sidebar-entry mdi mdi-account" href="profile.php"> Profil</button>
                    <button class="sidebar-entry mdi mdi-settings"> Settings</button>
                    <button class="sidebar-entry mdi mdi-logout" onclick="location.href = 'logout.php'"> Logout</button>
                </div>
            </div>
            <main class="container">
                <div class="profile-header">

                </div>
            </main>
            <script src="../res/js/index.js"></script>
            <script src="../res/js/themes.js"></script>
            <script>
            /* localhost */
                check34795z93475();
                function check34795z93475() {
                    if ($(location).attr('host') != 'localhost')
                        return


                }
            </script>
    </body>
</html>