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

    <title>Game Lobby</title>

    <!-- jquery | popper.js | bootstrap -->
    <script src="../res/js/jquery/jquery-3.4.1.min.js"></script>
    <script src="../res/js/popper.js/popper-1.15.0.min.js"></script>
    <script src="../res/js/bootstrap/bootstrap.js"></script>
    </head>
        <script>
            /**
              * @param game Game object to be added to the game table
              */
            function addGame(game) {
                var table = document.getElementById('gameTable');

                var tableRow = document.createElement('tr');
                table.appendChild(tableRow);

                var idCell = document.createElement('td');
                tableRow.appendChild(idCell);

                var linkCell = document.createElement('a');
                linkCell.href = 'play.php?id=' + game.id;
                idCell.appendChild(linkCell);
                linkCell.appendChild(document.createTextNode(game.id))

                var playerCell = document.createElement('td');
                tableRow.appendChild(playerCell);
                playerCell.appendChild(document.createTextNode(game.player1));

                loadedGameIds.push(game.id);
            }

            /**
              * @param id Game id to be removed from the game table
              */
            function removeGame(id) {
                var tableRows = document.getElementById('gameTable').children;

                // Iterate over game table, search for the given game id and remove the entry
                for (let i = 0; i < games.length; i++) {
                    var curId = tableRows[i] // <tr> tag
                        .children[0] // first <td> tag ([0] -> game id, [1] -> player1)
                        .firstChild // <a> tag
                        .firstChild // text node
                        .nodeValue; // game id

                    if (curId == id) {
                        table.removeChild(games[i]);

                        loadedGameIds.splice(loadedGameIds.indexOf(id), 1);
                        return;
                    }
                }
            }

            /**
              * Request a json object with all open games from the server and list them in the game table
              */
            function updateGames() {
                var xhttp = new XMLHttpRequest();

                xhttp.onreadystatechange = function() {
                    if (this.readyState == 4 && this.status == 200) {
                        var games = JSON.parse(this.responseText);

                        // Create a copy of all loaded game ids and remove those that are still open
                        // The games that are left after that need to be removed from the game table
                        var oldGameIds = loadedGameIds.slice();

                        for (let i = 0; i < games.length; i++) {
                            var game = games[i];
                            if (!loadedGameIds.includes(game.id)) {
                                addGame(game);
                            } else {
                                oldGameIds.splice(oldGameIds.indexOf(game.id), 1);
                            }
                        }

                        for (let i = 0; i < oldGameIds.length; i++) {
                            remove(oldGameIds[i]);
                        }
                    }
                };

                xhttp.open('GET', '../res/php/list_open_games.php', true);
                xhttp.send();
            }

            /**
              * Set an interval that updates the game list every second
              */
            function startUpdateLoop() {
                setInterval(updateGames, 1000);
            }

            /**
              * Add a fresh new game to the database
              */
            function createGame() {
                var xhttp = new XMLHttpRequest();
                xhttp.open('GET', '../res/php/create_game.php', true);
                xhttp.send();
            }

            var loadedGameIds = [];
        </script>
    </head>
    <body onload='startUpdateLoop();'>
        <h1>Game Lobby &#127976;</h1>
        <table border='1'>
            <thead>
                <tr>
                    <th>Game ID</th>
                    <th>Challenging Player</th>
                <tr>
            </thead>
            <tbody id='gameTable'></tbody>
        </table>
        <button onclick='createGame();'>Create new game</button>
        <!--
        <div class="loader">
            <div class="spinner"><i class="mdi mdi-48px mdi-spin mdi-loading"></i></div>
        </div>
        <div class="skewed-top"></div>
        <div class="skewed-bottom"></div>
        <div class="themes">
            <button class="btn-theme mdi mdi-24px mdi-weather-sunny"></button>
        </div>
        <main class="container">
            <div class="lobby">
            </div>
        </main>
        <script src="../res/js/index.js"></script>
        <script src="../res/js/themes.js"></script>
        <script>
            for (var i = 0; i < 20; i++) {
                $('.lobby').append('<div class="lobby-entry"><div class="row d-flex align-items-center"><div class="col mdi mdi-account">1/2</div><div class="col col-8">GAME #' + i + '</div><div class="col"><button class="btn btn-sm btn-block _btn">Join</button></div></div></div>');
                console.log('hey');
            }
        </script>
        -->
    </body>
</html>
