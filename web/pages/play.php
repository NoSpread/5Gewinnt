<?php
	session_start();
	require_once '../res/includes/auth_validate.php';
?>
<html>
	<head>
		<title id='header'>Loading . . .</title>
		<script>
			// Der aktuelle Spiel-Status wird abgefragt und dem Spieler angezeigt.
			function updateGameState() {
				var xhttp = new XMLHttpRequest();

				xhttp.onreadystatechange = function() {
					if (this.readyState == 4 && this.status == 200) {
						var game = JSON.parse(this.responseText);

						var gameObj = game.gameObj;
						var player1Obj = { id: game.player1, name: game.name1 };
						var player2Obj = { id: game.player2, name: game.name2 };

						// keine aktive Teilname am Spiel -> Zuschauer
						game.isSpectator = playerId != player1Obj.id && playerId != player2Obj.id;
						game.winnerObj = { 1: null, 2: player1Obj, 3: player2Obj }[gameObj.winner];
						game.currentPlayerObj = { 2: player1Obj, 3: player2Obj }[gameObj.player];

						updateStateMessage(game); // Statusmeldung des Spiels updaten (z.B. "orthoplex is thinking . . .")
						updateClock(game); // Verbleibende Bedenkzeit der Spieler updaten

						var table = document.getElementById('table');

						// Einmalige Aktion beim Laden der Webseite
						if (firstLoadingCycle) {
							// Titel der Seite setzen (Wettkampf- oder Spectator-Modus)

							var title = {
								true: 'Spectator Mode \uD83D\uDC40',
								false: 'Fight! \uD83E\uDD4A'
							}[game.isSpectator];
							document.getElementById('title').textContent = title;
							document.getElementById('header').textContent = title;

							// Im Wettkampf-Modus den Resign-Button laden
							if (!game.isSpectator) {
								document.getElementById('resignButton').hidden = false;
							}

							// Im Spectator-Modus den Lobby-Button laden
							if (game.isSpectator) {
								document.getElementById('lobbyButton').hidden = false;
							}

							// Einmalig die Tabellen-Titelzeile erstellen
							var tableHead = document.createElement('thead');
							tableHead.id = 'tableHead';
							table.appendChild(tableHead);

							// Befindet sich das Spiel im Wettkampf-Modus, werden Buttons zur Spaltenauswahl hinzugefügt
							if (!game.isSpectator) {
								var tableHeadRow = document.createElement('tr');
								tableHead.appendChild(tableHeadRow);

								for (let x = 0; x < gameObj.grid.width; x++) {
									var buttonCell = document.createElement('th');
									tableHeadRow.appendChild(buttonCell);

									var button = document.createElement('input');
									button.type = 'button';
									button.value = '\u25BC';
									button.onclick = function() { insertDisc(x); };
									buttonCell.appendChild(button);
								}
							}

							firstLoadingCycle = false;
						}

						// Sobald das Spiel beendet ist, werden im Wettkampf-Modus die Buttons zur Spaltenauswahl und der Resign-Button deaktiviert. Der Lobby-Button wird angezeigt.
						if (game.state == 'finished' && !game.isSpectator) {
							var insertButtons = document.getElementById('tableHead').getElementsByTagName('input');

							for (var i = 0; i < insertButtons.length; i++) {
								insertButtons[i].disabled = true;
							}

							document.getElementById('resignButton').disabled = true;
							document.getElementById('lobbyButton').hidden = false;
						}

						// Entfernen des alten Spielbrettes, sofern dieses existiert
						if (document.getElementById('tableBody')) {
							table.removeChild(document.getElementById('tableBody'));
						}

						// Erzeugen des Spielbrettes
						var tableBody = document.createElement('tbody');
						tableBody.id = 'tableBody';
						table.appendChild(tableBody);

						for (let y = 0; y < gameObj.grid.height; y++) {
							var boardRow = document.createElement('tr');
							tableBody.appendChild(boardRow);

							for (let x = 0; x < gameObj.grid.width; x++) {
								var disc = gameObj.grid.lines[y][x];
								var symbol = { 1: '-', 2:'X', 3:'O'}[disc.color];
								var color = { 1: '#000000', 2: game.color1, 3: game.color2 }[disc.color];
								var background = { 1: '#FFFFFF', 2: game.color2, 3: game.color1}[disc.color];

								var discCell = document.createElement('td');
								if (disc.marked) {
									discCell.style.fontWeight = 'bold';
								}
								discCell.style.backgroundColor = background;
								discCell.style.color = color;
	                            discCell.appendChild(document.createTextNode(symbol));
								boardRow.appendChild(discCell);
							}
						}
					}
				};

				xhttp.open('GET', '../res/php/game_state.php?id=' + gameId, true);
				xhttp.send();
			}

			// Updaten der verbleibenden Bedenkzeiten
			function updateClock(game) {
				var clock1 = document.getElementById('clock1');
				var clock2 = document.getElementById('clock2');

				if (game.isSpectator) { // Einem Zuschauer werden beide Namen aufgelöst
					clock1.firstChild.nodeValue = game.name1 + "'s time: " + game.clock1.toFixed(1) + 's';
					clock2.firstChild.nodeValue = game.name2 + "'s time: " + game.clock2.toFixed(1) + 's';
				} else if (playerId == game.player1) { // Als aktiver Spieler wird nur der Name des Gegners aufgelöst
					clock1.firstChild.nodeValue = 'Your time: ' + game.clock1.toFixed(1) + 's';
					clock2.firstChild.nodeValue = game.name2 + "'s time: " + game.clock2.toFixed(1) + 's';
				} else {
					clock1.firstChild.nodeValue = 'Your time: ' + game.clock2.toFixed(1) + 's';
					clock2.firstChild.nodeValue = game.name1 + "'s time: " + game.clock1.toFixed(1) + 's';
				}

			}

			// Updaten der Spielstatus-Meldung
			function updateStateMessage(game) {
				var stateMessage = document.getElementById('stateMessage');

				if (game.isSpectator) {
					if (game.state == 'finished') {
						if (game.winnerObj == null) {
							msg = "It's a tie";
						} else {
							msg = game.winnerObj.name + ' won!';
						}
					} else {
						msg = game.currentPlayerObj.name + ' is thinking . . .';
					}
				} else if (game.state == 'finished') {
					if (game.winnerObj == null) {
						msg = "It's a tie";
					} else if (game.winnerObj.id == playerId) {
						msg = 'You won.';
					} else {
						msg = 'You lost.';
					}
				} else if (game.currentPlayerObj.id == playerId) {
					msg = "It's your turn.";
				} else {
					msg = game.currentPlayerObj.name + ' is thinking . . .';
				}

				stateMessage.firstChild.nodeValue = msg;
			}

			/**
			  * @param column Die Spalte, in welche ein Spielstein eingefügt werden soll.
			  */
			function insertDisc(column) {
				var xhttp = new XMLHttpRequest();
				xhttp.open('GET', '../res/php/make_move.php?id=' + gameId + '&column=' + column, true);
				xhttp.send();
			}

			// Das Spiel aufgeben
			function resign() {
				var xhttp = new XMLHttpRequest();

				xhttp.onreadystatechange = function() {
					if (this.readyState == 4 && this.status == 200) {
						updateGameState();
					}
				};

				xhttp.open('GET', '../res/php/resign.php?id=' + gameId, true);
				xhttp.send();
			}

			// Der Spiel-Status wird in einem Intervall von einer Sekunde aktualisiert.
			function startUpdateLoop() {
				setInterval(updateGameState, 1000);
			}

			var gameId = <?php echo $_GET['id']; ?>;
			var playerId = <?php echo $_SESSION['id'] ?>;
			var firstLoadingCycle = true;
		</script>
	</head>
	<body onload='startUpdateLoop();'>
		<h1 id='title'>Loading . . .</h1>
		<div id='clock1'>Loading . . .</div>
		<div id='clock2'>Loading . . .</div>
		<div id='stateMessage'>Loading . . .</div>
		<table border='1' id='table'></table>
		<div id='resignContainer'>
			<input type='button' value='Resign' onclick='resign();' id='resignButton' hidden />
		</div>
		<a href='index.php' hidden id='lobbyButton'>Back to the lobby</a>
	</body>
</html>
