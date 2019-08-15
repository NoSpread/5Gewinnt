<?php
    session_start();
    require_once '../res/includes/auth_validate.php';
    require_once '../res/php/MysqliDb.php';
    require_once '../res/php/config.php';

    $db = getDbInstance();
    $db->where("username", $_SESSION['username']);
    $row = $db->getOne('user');

    if ($db->count > 0) {
        $name = $row['name'];
        $age = $row['age'];
        $gender = $row['gender'];
    }
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
        <script>
            /**
              * @param game Game-Objekt, welches zu einer der Spiele-Listen hinzugefügt werden soll. Dieses enthält die Id des Spiels, sowie die aufgelösten Namen der Mitspieler.
              * @param mode Objekt, das den Namen der Tabelle, in die die Eintragung hinzufügt werden soll, die Beschriftung des Buttons ("Join"/"Watch"), sowie dessen Callback-Fuktion enthält
              */
            function addGame(game, mode) {
                // Erstellen und Konfigurieren der benötigten DOM-Objekte
				var table = document.getElementById(mode.tableName);

                var tableRow = document.createElement('tr');
				tableRow.id = 'game' + game.id;
                table.appendChild(tableRow);

                var idCell = document.createElement('td');
                tableRow.appendChild(idCell);
                idCell.appendChild(document.createTextNode(game.id));

                var player1Cell = document.createElement('td');
                tableRow.appendChild(player1Cell);
                var player2Cell = document.createElement('td');
                tableRow.appendChild(player2Cell);
				var buttonCell = document.createElement('td');
				tableRow.appendChild(buttonCell);

                var button = document.createElement('input');
                button.type = 'button';
                button.value = mode.buttonLabel;
                button.onclick = mode.callback;
				buttonCell.appendChild(button);

				if (challenging) {
					button.disabled = true;
				}

                // Ist einer der Spielernamen nicht angegeben (weil noch ein Mitspieler fehlt), wird das Namensfeld freigelassen.
				if (game.name1 !== null) {
					player1Cell.appendChild(document.createTextNode(game.name1 + '#' + game.player1));
				} else {
					player1Cell.appendChild(document.createTextNode(''));
				}
				if (game.name2 !== null) {
					player2Cell.appendChild(document.createTextNode(game.name2 + '#' + game.player2));
				} else {
					player2Cell.appendChild(document.createTextNode(''));
				}

                // Wir müssen das hinzugefügt Spiel global registrieren.
                loadedGameIds[mode.tableName].push(game.id);
            }

            /**
              * @param id ID des Spiel, auf das der Benutzer (im Spectator-Modus) weitergeleitet wird
              */
			function watchGame(id) {
				var url = 'play.php';
				var form = document.createElement('form');
				form.action = url;
				form.method = 'get';
				form.style.visibility = 'hidden';
				document.getElementById('body').appendChild(form);

				var idField = document.createElement('input');
				idField.type = 'text';
				idField.name = 'id';
				idField.value = id;
				form.appendChild(idField);

				form.submit();
			}

            /**
              * @param id ID des Spiels, bei dem der Benutzer als zweiter Spieler eintragen wird
              */
			function joinGame(id) {
				var xhttp = new XMLHttpRequest();
                xhttp.open('GET', '../res/php/join_game.php?id=' + id, true);
                xhttp.send();
			}

            /**
              * @param id Game id des Spiels, das entfernt werden soll
              * @param tableName Name der Tabelle, die die zu entfernende Eintragung enthält
              */
            function removeGame(id, tableName) {
				var idTable = loadedGameIds[tableName];

				var table = document.getElementById(tableName);
                var rowToRemove = document.getElementById('game' + id);

                table.removeChild(rowToRemove);

                idTable.splice(idTable.indexOf(id), 1);
            }

            // Senden einer Anfrage an den Server nach einem json-Objekt, welches alle offenen und laufenden Spiele enthält.
            // Bei diesem Objekt wird analysiert, welche Spiele neu hinzugekommen sind und welche entfernt wurden.
            // Dies bedeutet, dass die Spiele-Tabelle nicht jedes Mal komplett neu aufgebaut werden muss, sondern nur die Änderungen übernommen werden.
            function updateGames() {
                var xhttp = new XMLHttpRequest();

                xhttp.onreadystatechange = function() {
                    if (this.readyState == 4 && this.status == 200) {
                        var games = JSON.parse(this.responseText);

						var modes = [
							{ tableName: 'openTable', buttonLabel: 'Join Game', callback: function() { joinGame(game.id); } },
							{ tableName: 'ongoingTable', buttonLabel: 'Watch Game', callback: function() { watchGame(game.id); } }
						];


                        // Zuerst erstellen wir eine Kopie aller aktuell angezeigten Game-IDs in beiden Tabellen
						var oldGameIds = {
							'openTable': loadedGameIds['openTable'].slice(),
							'ongoingTable': loadedGameIds['ongoingTable'].slice()
						};

                        // Dann iterieren wir über die Listen der Spiele, die uns der Server geliefert hat.
						for (let j = 0; j < modes.length; j++) {
							var mode = modes[j];

							for (let i = 0; i < games[mode.tableName].length; i++) {
								var game = games[mode.tableName][i];

                                // Stoßen wir auf eine Game-ID, die wir noch nicht kennen, fügen wir das Spiel in eine Tabelle hinzu
								if (!loadedGameIds[mode.tableName].includes(game.id)) {
									addGame(game, mode);
								} else { // Kennen wir das Spiel schon, streichen wir es aus unserer eben erwähnten Liste.
									oldGameIds[mode.tableName].splice(oldGameIds[mode.tableName].indexOf(game.id), 1);
								}
							}
                            // Die übrig gebliebenen IDs gehören jetzt zu denjenigen Spielen, die bei uns noch gelistet sind, vom Server jedoch nicht mitgeteilt wurden
                            // Diese Spiele müssen wir entfernen.
							for (let i = 0; i < oldGameIds[mode.tableName].length; i++) {
								removeGame(oldGameIds[mode.tableName][i], mode.tableName);
							}
						}
                    }
                };

                xhttp.open('GET', '../res/php/list_games.php', true);
                xhttp.send();
            }

            // Es wird überprüft, ob der Spieler eine offene Herausforderung besitzt.
            // Tut er das, wird die Seite in den "Revoke-Modus" gesetzt, in dem der Benutzer seine Herausforderung zurückziehen kann.
            // Andernfalls wird die Seite in den "Challenge-Modus" gesetzt, in dem der Spieler einer Herausforderung erstellen kann.
            function checkOpenGame() {
                var xhttp = new XMLHttpRequest();

                xhttp.onreadystatechange = function() {
                    if (this.readyState == 4 && this.status == 200) {
                        if (this.responseText == '1' && !challenging) {
                            revokeMode();
                        } else if (this.responseText == '0' && challenging) {
                            challengeMode();
                        }
                    }
                };

                xhttp.open('GET', '../res/php/has_open_game.php', true);
                xhttp.send();
            }

            // Es wird überprüft, ob sich der Benutzer gerade an einem laufenden Spiel befindet.
            // Tut er das, wird er auf die Seite mit diesem Spiel weitergeleitet.
            function checkOngoingGame() {
                var xhttp = new XMLHttpRequest();

                xhttp.onreadystatechange = function() {
                    if (this.readyState == 4 && this.status == 200) {
                        var info = JSON.parse(this.responseText);
                        if(info.ongoing) {
							var url = 'play.php';
							var form = document.createElement('form');
							form.action = url;
							form.method = 'get';
							form.style.visibility = 'hidden';
							document.getElementById('body').appendChild(form);

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

			// Die aktuelle Herausforderung des Benutzers werden zurückgezogen, sofern eine existiert.
			function revokeChallenge() {
				var xhttp = new XMLHttpRequest();
				xhttp.open('GET', '../res/php/revoke_own_game.php', true);
				xhttp.send();
			}

            // Ein wiederholter Funktionsaufruf wird erstellt, der prüft, ob...
            // 1) sich etwas an der Spiele-Liste geändert hat
            // 2) sich der Benutzer im "Challenge-" oder "Revoke-Modus" befindet
            // 3) sich der Benutzer an einem laufenden Spiel teilnimmt
            function startUpdateLoop() {
                setInterval( function() {
					updateGames();
					checkOpenGame();
					checkOngoingGame();
				}, 1000);
            }

            // Ein neues Spiel wird der Datenbank hinzugefügt.
            function createGame() {
				// @Marvin: Pls make Checkbox to Togglebutton

                var xhttp = new XMLHttpRequest();

				var player = {
					true: '1',
					false: '2'
				}[document.getElementById('player').checked];

                xhttp.open('GET', '../res/php/create_game.php?player=' + player , true);
                xhttp.send();
            }

            // Die Benutzeroberfläche wird so eingestellt, dass eine Herausforderung erstellt, oder an einem offenen Spiel teilgenommen werden kann.
            function challengeMode() {
                var buttons = document.getElementById('openTable')
                    .getElementsByTagName('input');

                for (var i = 0; i < buttons.length; i++) {
                    buttons[i].disabled = false;
                }

                document.getElementById('player').disabled = false;

                var button = document.getElementById('manage');
                button.onclick = function() { createGame(); };
                button.value = 'Create Challenge';

                document.getElementById('player').disabled = false;

                challenging = false;
            }

            // Die Benutzeroberfläche wird so eingestellt, dass eine Herausforderung widerrufen werden kann.
            function revokeMode() {
				var buttons = document.getElementById('openTable')
					.getElementsByTagName('input');

                for (var i = 0; i < buttons.length; i++) {
                    buttons[i].disabled = true;
                }

                document.getElementById('player').disabled = true;

                var button = document.getElementById('manage');
                button.onclick = function() { revokeChallenge(); };
                button.value = 'Revoke Challenge';

                challenging = true;
            }

            var loadedGameIds = { 'openTable': [], 'ongoingTable': [] };
			var challenging = false;
        </script>
    </head>
    <body onload='startUpdateLoop();' id='body'>
        <h1>Game Lobby &#127976;</h1>
        <table border=1>
            <thead>
				<tr>
                    <th>Game ID</th>
                    <th>First Player</th>
					<th>Second Player</th>
					<th>Join</th>
                <tr>
            </thead>
            <tbody id='openTable'></tbody>
        </table>
		<br />
		<table border=1>
            <thead>
                <tr>
                    <th>Game ID</th>
                    <th>First Player</th>
					<th>Second Player</th>
					<th>Watch</th>
                <tr>
            </thead>
            <tbody id='ongoingTable'></tbody>
        </table>
		<form>
			<input id='player' type='checkbox' checked='checked'>White</input>
			<input id='manage' type='button' onclick='createGame();' value='Create Challenge' />
		</form>

        <?php
            require_once 'components/loader.php';
            require_once 'components/theme.php';
            require_once 'components/sidebar.php';
            require_once 'components/profile.php';
        ?>
        <main class='container'>
            <div class='lobby'>
                <div class='cc'>
                        <!-- if this is not necessary for the project I can still remove it out whatever @TaTaNa @nospread -->
                    <div class='form-check _form btn'>
                        <input class='form-check-input' type='checkbox' value='' id='privateChallenge'>
                        <label class='form-check-label' for='privateChallenge'>Private Challenge</label>
                    </div>
                    <button class='btn _btn'>Create Challenge</button>
                    <button class='btn _btn mdi mdi-24px mdi-reload'></button>
                </div>
            </div>
        </main>
        <script src='../res/js/index.js'></script>
        <script src='../res/js/themes.js'></script>
        <script>
        // localhost
            check34795z93475();
            function check34795z93475() {
                if ($(location).attr('host') != 'localhost')
                    return

                for (var i = 0; i < 20; i++) {
                    /*if ( is private challenge true ) continue / don't list private challenges. they're only accessable via link. */
                    // Private Spiele werden nicht aufgelistet, sondern sind über einen Link zu erreichen.

                    $('.lobby').append("<div class='lobby-entry'><div class='row d-flex align-items-center'><div class='col mdi mdi-account'>1/2</div><div class='col col-8'>GAME #" + i + "</div><div class='col'><button class='btn btn-block _btn'>Join</button></div></div></div>");
                }
            }
        </script>
	</body>
</html>

<!-- <div class='lobby-entry'>
    <div class='row d-flex align-items-center'>
        <div class='col mdi mdi-account'>1/2</div>
        <div class='col col-8'>GAME #' + i + '</div>
        <div class='col'>
            <button class='btn btn-sm btn-block _btn'>Join</button>
        </div>
    </div>
</div> -->
