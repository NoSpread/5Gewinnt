<?php
    session_start();
    require_once '../res/includes/auth_validate.php';
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

        <title>Game Lobby</title>

        <!-- jquery | popper.js | bootstrap -->
        <script src="../res/js/jquery/jquery-3.4.1.min.js"></script>
        <script src="../res/js/popper.js/popper-1.15.0.min.js"></script>
        <script src="../res/js/bootstrap/bootstrap.js"></script>
        <script>
            /**
              * @param game Game object, welches zu der Spiele-Liste hinzugefügt werden soll
              */
            function addGame(game,id) {
				
				if (id == 0) {
					var table = document.getElementById('gameTable');
				} else { 
					var table = document.getElementById('spectateTable');
				}
                // Spiel der Spiele-Liste hinzufügen
                var tableRow = document.createElement('tr');
                table.appendChild(tableRow);

                var idCell = document.createElement('td');
                tableRow.appendChild(idCell);
                idCell.appendChild(document.createTextNode(game.id))

                var player1Cell = document.createElement('td');
                tableRow.appendChild(player1Cell);
                var player2Cell = document.createElement('td');
                tableRow.appendChild(player2Cell);

                var button = document.createElement('input');
                button.type = 'button';
                button.value = 'Join Game';
                button.onclick = function() { joinGame(game.id); }
				if (challenging) {
					button.disabled = true;
				}

                if (game.player1 !== null) {
                    player1Cell.appendChild(document.createTextNode(game.username + ' (id:' + game.player1 + ')'));
                    player2Cell.appendChild(button);
                } else {
                    player1Cell.appendChild(button);
                    player2Cell.appendChild(document.createTextNode(game.username + ' (id:' + game.player2 + ')'));
                }

                loadedGameIds.push(game.id);
            }

			function joinGame(id) {
                // Einem Spiel beitreten
				var xhttp = new XMLHttpRequest();
                xhttp.open('GET', '../res/php/join_game.php?id=' + id, true);
                xhttp.send();
			}

            /**
              * @param id Game id, welche aus der Spiele-Tabelle entfernt werden soll
              */
            function removeGame(id) {
				var table = document.getElementById('gameTable');
                var tableRows = document.getElementById('gameTable').children;

                // Iteration über die Spiele-Tabelle: Es wird nach der angegebenen Spiel-ID gesucht und dieser Eintrag entfernt.
                for (let i = 0; i < loadedGameIds.length; i++) {
                    var curId = tableRows[i] // <tr> tag
                        .children[0] // erster <td> tag ([0] -> game id, [1] -> player1)
                        .firstChild // text node
                        .nodeValue; // game id

                    if (curId == id) {
                        table.removeChild(tableRows[i]);

                        loadedGameIds.splice(loadedGameIds.indexOf(id), 1);
                        return;
                    }
                }
            }

            // Senden einer Anfrage an den Server nach einem json-Objekt, welches alle offenen Spiele enthält.
            // Diese werden dann in der Spiele-Tabelle aufgelistet.
            function updateGames() {
                var xhttp = new XMLHttpRequest();

                xhttp.onreadystatechange = function() {
                    if (this.readyState == 4 && this.status == 200) {
                        var games = JSON.parse(this.responseText);

                        var oldGameIds = [
								loadedGameIds[0].slice(),
								loadedGameIds[1].slice()
								];
						
                        // Es wird eine Kopie aller Game IDs erstellt und alle Spiele entfernt, welche noch "offen" sind.
                        // Die Spiele, welche anschließend noch übrig sind, müssen von der Spiele-Tabelle entfernt werden.

                        for (let i = 0; i < games.length; i++) {
                            var game = games[i];
							
							if( game.state == 'open') {
								var specate = 0;
							} else {
								var spectate = 1;
							}
							
                            if (!loadedGameIds[spectate].includes(game.id)) {
                                addGame(game, spectate);
                            } else {
                                oldGameIds[spectate].splice(oldGameIds[specatate].indexOf(game.id), 1);
                            }
                        }

                        for (let i = 0; i < oldGameIds[0].length; i++) {
                            removeGame(oldGameIds[0][i], 'gameTable');
                        }
						for (let i = 0; i < oldGameIds[1].length; i++) {
                            removeGame(oldGameIds[1][i], 'spectateTable');
                        }
                    }
                };

                xhttp.open('GET', '../res/php/list_games.php', true);
                xhttp.send();
            }

            function checkOpenGame() {
                // Es wird überprüft, ob die Herausforderung noch exixtiert.
                var xhttp = new XMLHttpRequest();

                xhttp.onreadystatechange = function() {
                    if (this.readyState == 4 && this.status == 200) {
                        if (this.responseText == '1' && !challenging) {
                            // widerrufen
                            revokeMode();
                        } else if (this.responseText == '0' && challenging) {
                            // laufend
                            challengeMode();
                        }
                    }
                };

                xhttp.open('GET', '../res/php/has_open_game.php', true);
                xhttp.send();
            }

            function checkOngoingGame() {
                var xhttp = new XMLHttpRequest();

                xhttp.onreadystatechange = function() {
                    if (this.readyState == 4 && this.status == 200) {
                        var info = JSON.parse(this.responseText);
                        if(info.ongoing) {
							// TODO make form invisible
							var url = 'play.php';
							var form = document.createElement('form');
							form.action = url;
							form.method = 'get';
							document.getElementsByTagName('body')[0].appendChild(form);

							var idField = document.createElement('input');
							idField.type = 'text';
							idField.name = 'id';
							idField.value = info.id;
							form.appendChild(idField);

							form.submit();
						}
                    }
                };

                xhttp.open('GET', '../res/php/has_ongoing_game.php', true);
                xhttp.send();
            }

			// Herausforderung widerrufen
			function revokeChallenge() {
				var xhttp = new XMLHttpRequest();
				xhttp.open('GET', '../res/php/revoke_own_game.php', true);
				xhttp.send();
			}

            // Ein Intervall wird gesetzt, das die Spiele-Liste jede Sekunde aktualisiert.
            function startUpdateLoop() {
                setInterval( function() {
                        updateGames();
                        checkOpenGame();
                        checkOngoingGame();
                    }, 1000);
            }

            // Ein neues Spiel wird der Datenbank hinzugefügt.
            function createGame() {
				// Pls make Checkbox to Togglebutton

                var xhttp = new XMLHttpRequest();

				var player = {
					true: '1',
					false: '2'
				}[document.getElementById('player').checked];

                xhttp.open('GET', '../res/php/create_game.php?player=' + player , true);
                xhttp.send();
            }

            // Die Benutzeroberfläche wird so eingestellt, dass eine Herausforderung erstellt werden kann.
            function challengeMode() {
                var buttons = document.getElementById('gameTable')
                    .getElementsByTagName('input');

                for (var i = 0; i < buttons.length; i++) {
                    buttons[i].disabled = false;
                }

                document.getElementById('player').disabled = false;

                challenging = false;

                button = document.getElementById('manage')
                button.onclick = function() { createGame(); };
                button.value = "Create Challenge";

                document.getElementById('player').disabled = false;
            }

            // Die Benutzeroberfläche wird so eingestellt, dass eine Herausforderung widerrufen werden kann.
            function revokeMode() {
				var buttons = document.getElementById('gameTable')
					.getElementsByTagName('input');

                for (var i = 0; i < buttons.length; i++) {
                    buttons[i].disabled = true;
                }

                document.getElementById('player').disabled = true;

                var button = document.getElementById('manage');
                button.onclick = function() { revokeChallenge(); };
                button.value = "Revoke Challenge";

                challenging = true;
            }

            var loadedGameIds = [[],[]];
			var challenging = false;
        </script>
    </head>
    <body onload='startUpdateLoop();'>
	
        <h1>Game Lobby &#127976;</h1>
        <table border=1>
            <thead>
				<tr>
                    <th>Game ID</th>
                    <th>First Player</th>
					<th>Second Player</th>
                <tr>
            </thead>
            <tbody id='gameTable'></tbody>
        </table>
		<br />
		<table border=1>
            <thead>
                <tr>
                    <th>Game ID</th>
                    <th>First Player</th>
					<th>Second Player</th>
					<th>Spectate now!</th>
                <tr>
            </thead>
            <tbody id='spectateTable'></tbody>
        </table>
		<form>
			<input id="player" type="checkbox" checked="checked">White</input>
			<input id="manage" type='button' onclick='createGame();' value='Create Challenge' />
		</form> 
		
        <?php
            require_once 'components/loader.php';
            require_once 'components/theme.php';
            require_once 'components/sidebar.php';
            require_once 'components/profile.php';
        ?>
        <main class="container">
            <div class="lobby">
                <div class="cc">
                        <!-- if this is not necessary for the project I can still remove it out whatever @TaTaNa @nospread -->
                    <div class="form-check _form btn">
                        <input class="form-check-input" type="checkbox" value="" id="privateChallenge">
                        <label class="form-check-label" for="privateChallenge">Private Challenge</label>
                    </div>
                    <button class="btn _btn">Create Challenge</button>
                    <button class="btn _btn mdi mdi-24px mdi-reload"></button>
                </div>
            </div>
        </main>
        <script src="../res/js/index.js"></script>
        <script src="../res/js/themes.js"></script>
        <script>
        // localhost
            check34795z93475();
            function check34795z93475() {
                if ($(location).attr('host') != 'localhost')
                    return

                for (var i = 0; i < 20; i++) {
                    /*if ( is private challenge true ) continue / don't list private challenges. they're only accessable via link. */
                    // Private Spiele werden nicht aufgelistet, sondern sind über einen Link zu erreichen.

                    $('.lobby').append('<div class="lobby-entry"><div class="row d-flex align-items-center"><div class="col mdi mdi-account">1/2</div><div class="col col-8">GAME #' + i + '</div><div class="col"><button class="btn btn-block _btn">Join</button></div></div></div>');
					// ^^^ Garbage ^^^
					
					console.log('hey');
                }
            }
        </script>
	</body>
</html>

<!-- <div class="lobby-entry">
    <div class="row d-flex align-items-center">
        <div class="col mdi mdi-account">1/2</div>
        <div class="col col-8">GAME #' + i + '</div>
        <div class="col">
            <button class="btn btn-sm btn-block _btn">Join</button>
        </div>
    </div>
</div> -->