<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">

    <link rel="stylesheet" href="res/css/bootstrap/bootstrap.min.css">
    <link rel="stylesheet" href="res/css/materialdesignicons/materialdesignicons.min.css">
    <link rel="stylesheet" href="res/css/materialdesignicons/materialdesignicons.helper.css">
    <link rel="stylesheet" href="res/css/style.css">
    <link rel="stylesheet" id="theme" href="res/css/light.css">

    <title>Register</title>

    <!-- jquery | popper.js | bootstrap -->
    <script src="res/js/jquery/jquery-3.4.1.min.js"></script>
    <script src="res/js/popper.js/popper-1.15.0.min.js"></script>
    <script src="res/js/bootstrap/bootstrap.js"></script>
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
        <form class="form-signin" action="res/includes/auth.php" method="post">
            <div class="text-center">
                <h1>register</h1>
            </div>

            <div class="form-label-group">
                <input type="email" id="inputEmail" class="form-control" placeholder="Email Address" required autofocus>
                <label for="inputEmail">Email Address</label>
            </div>

            <div class="form-label-group">
                <input type="password" id="inputPassword" class="form-control" placeholder="Password" required>
                <label for="inputPassword">Password</label>
            </div>

            <div class="form-label-group">
                <input type="password" id="inputPassword2" class="form-control" placeholder="Password (again)" required>
                <label for="inputPassword2">Password (again)</label>
            </div>

            <button class="mt-5 btn btn-lg btn-block btn-signin" type="submit">Register</button>
            <p class="mt-1 text-center"><a href="login.php">Already have an account? Login here!</a></p>
            <p class="mt-5 mb-3 text-center">&copy; löres ipsumme</p>
        </form>
    </main>

    <script src="res/js/index.js"></script>
    <script src="res/js/themes.js"></script>
</body>
</html>