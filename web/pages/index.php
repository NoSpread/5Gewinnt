<?php
    session_start();
    require_once '../res/includes/auth_validate.php';
    require_once '../res/php/MysqliDb.php';
    require_once '../res/php/config.php';

    $username = $_SESSION['username'];

    $db = getDbInstance();
    $db->where('username', $username);
    $row = $db->getOne('user');

    if ($db->count > 0) {
        $name = $row['name'];
        $age = $row['age'];
        $gender = $row['gender'];
    }

    $db = MySqliDB::getInstance();
    $db->where('username', $username);
    $row = $db->getOne('avatar');

    $avatarpath = $row['img_name'];

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

                var tableRow = document.createElement('div');
				tableRow.id = 'game' + game.id;
                tableRow.classList.add('lobby-entry');
                table.appendChild(tableRow);
                var tablex = document.createElement('div');
                tablex.classList.add('row', 'd-flex', 'align-items-center');
                tableRow.appendChild(tablex);

                var idCell = document.createElement('div');
                idCell.classList.add('col');
                idCell.innerHTML = "GAME #" + game.id;
                tablex.appendChild(idCell);

                var playersCell = document.createElement('div');
                playersCell.classList.add('col', 'col-6');
                tablex.appendChild(playersCell);
				var buttonCell = document.createElement('div');
                buttonCell.classList.add('col')
				tablex.appendChild(buttonCell);

                var button = document.createElement('button');
                button.classList.add('btn', 'btn-sm', 'btn-block', '_btn')
                button.innerHTML = mode.buttonLabel;
                button.onclick = mode.callback;
				buttonCell.appendChild(button);

				if (challenging) {
					button.disabled = true;
				}

                // Ist einer der Spielernamen nicht angegeben (weil noch ein Mitspieler fehlt), wird das Namensfeld freigelassen.
                if (game.name1 !== null && game.name2 !== null) {
                    playersCell.innerHTML = game.name1 + '#' + game.player1 + ' vs. ' + game.name2 + '#' + game.player2;
                } else if (game.name1 !== null && game.name2 == null) {
                    playersCell.innerHTML = game.name1 + '#' + game.player1 + ' vs. ???';
                } else if (game.name1 == null && game.name2 !== null) {
                    playersCell.innerHTML = '??? vs. ' + game.name2 + '#' + game.player2;
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
                var xhttp = new XMLHttpRequest();

                // var player;
                // if (document.getElementById('player').classList.contains('white')) {
                    // player = '1';
                // } else if (document.getElementById('player').classList.contains('black')) {
                    // player = '2';
                // }

				var themeList = document.getElementById('gameTheme').children;
				var theme;
				for (var i = 0; i < themeList.length; i++) {
					if (themeList[i].selected) {
						theme = {
							'theme1': '1',
							'theme2': '2',
							'theme3': '3'
						}[themeList[i].id];
					}
				}

				var playerList = document.getElementById('startPlayer').children;
				var player;
				for (var i = 0; i < playerList.length; i++) {
					if (playerList[i].selected) {
						player = {
							'player1': '1',
							'player2': '2'
						}[playerList[i].id];
					}
				}

                xhttp.open('GET', '../res/php/create_game.php?player=' + player + '&theme=' + theme, true);
                xhttp.send();
            }

            // Die Benutzeroberfläche wird so eingestellt, dass eine Herausforderung erstellt, oder an einem offenen Spiel teilgenommen werden kann.
            function challengeMode() {
                var buttons = document.getElementById('openTable')
                    .getElementsByTagName('button');

                for (var i = 0; i < buttons.length; i++) {
                    buttons[i].disabled = false;
                }

                document.getElementById('blacknwhite').classList.remove('disabled');
                document.getElementById('gameTheme').disabled = false;
                document.getElementById('startPlayer').disabled = false;

                var button = document.getElementById('manage');
                button.onclick = function() { createGame(); };
                button.innerHTML = 'Create Challenge';

                challenging = false;
            }

            // Die Benutzeroberfläche wird so eingestellt, dass eine Herausforderung widerrufen werden kann.
            function revokeMode() {
				var buttons = document.getElementById('openTable')
					.getElementsByTagName('button');

                for (var i = 0; i < buttons.length; i++) {
                    buttons[i].disabled = true;
                }

                document.getElementById('blacknwhite').classList.add('disabled');
                document.getElementById('gameTheme').disabled = true;
                document.getElementById('startPlayer').disabled = true;

                var button = document.getElementById('manage');
                button.onclick = function() { revokeChallenge(); };
                button.innerHTML = 'Revoke Challenge';

                challenging = true;
            }

            var loadedGameIds = { 'openTable': [], 'ongoingTable': [] };
			var challenging = false;
        </script>
    </head>
    <body onload='startUpdateLoop();' id='body'>
        <?php
            require_once 'components/loader.php';
            require_once 'components/theme.php';
            require_once 'components/sidebar.php';
            require_once 'components/profile.php';
            require_once 'components/settings.php';
        ?>
        <main class='container'>
            <div id="openTable" class='lobby'>
                <div class="lobby-entry text-center">
                    OPEN CHALLENGES
                </div>
            </div>
            <div class='cc'>
                <div class="dropdown">
                    <select id='gameTheme' class="form-control">
                        <option id='theme1' selected>Black vs. White</option>
                        <option id='theme2'>Red vs. Green</option>
                        <option id='theme3'>Yellow vs. Blue</option>
                    </select>
                </div>
                <div class="dropdown">
                    <select id='startPlayer' class="form-control">
                        <option id='player1' selected>Player 1</option>
                        <option id='player2'>Player 2</option>
                    </select>
                </div>
                <button id="manage" class='btn _btn' onclick='createGame();'>Create Challenge</button>
            </div>
            <div id="ongoingTable" class='lobby'>
                <div class="lobby-entry text-center">
                    ONGOING CHALLENGES
                </div>
            </div>
        </main>
        <script src='../res/js/index.js'></script>
        <script src='../res/js/themes.js'></script>
        <script src='../res/js/browser.js'></script>
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
