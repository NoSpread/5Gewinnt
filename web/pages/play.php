<?php
	session_start();
	require_once '../res/includes/auth_validate.php';
?>
<html>
	<head>
		<title>Fight!</title>
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
						game.playerId = <?php echo $_SESSION['id'] ?>;

						// keine aktive Teilname am Spiel -> Zuschauer
						game.isSpectator = game.playerId != player1Obj.id && game.playerId != player2Obj.id;

						game.winnerObj = {
							1: null,
							2: player1Obj,
							3: player2Obj
						}[gameObj.winner];

						game.currentPlayerObj = {
							2: player1Obj,
							3: player2Obj
						}[gameObj.player];

						var title = document.getElementById('title');

						if (firstLoadingCycle) {
							title.textContent = {
								true: 'Spectator Mode \uD83D\uDC40',
								false: 'Fight! \uD83E\uDD4A'
							}[game.isSpectator];
						}

						if (firstLoadingCycle && !game.isSpectator) {
							var resignContainer = document.getElementById('resignContainer');

							var resignButton = document.createElement('input');
							resignButton.id = 'resignButton';
							resignButton.type = 'button';
							resignButton.value = 'Resign';
							resignButton.onclick = function() { resign(); };

							resignContainer.appendChild(resignButton);
						}

						// Update der Spiel-Status-Nachricht

						updateStateMessage(game);
						updateClock(game);

						var table = document.getElementById('table');

						if (firstLoadingCycle) {
							// Es gibt (noch) keinen Gewinner und das Spielbrett ist noch nicht erstellt:
							var tableHead = document.createElement('thead');
							tableHead.id = 'tableHead';
							table.appendChild(tableHead);

							if (!game.isSpectator){
								// Erzeugen des Tabellenkopfes, welcher die Knöpfe zum Spielstein-Setzen enhält
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
							// Das Spielbrett muss nur einmal erzeugt werden.
							firstLoadingCycle = false;
						}

						if (game.state == 'finished' && !game.isSpectator) {
							// Es gibt einen Gewinner (und das Spielbrett ist geladen) -> Spielbrett wird entfernt
							var insertButtons = document.getElementById('tableHead')
								.getElementsByTagName('input');

							for (var i = 0; i < insertButtons.length; i++) {
								insertButtons[i].disabled = true;
							}

							document.getElementById('resignButton').disabled = true;
						}

						// Entfernen des alten Spielbrettes
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
								var color = {
									1: '-', 2:'X', 3:'O'
								}[disc.color];

								var discCell = document.createElement('td');
	                            discCell.appendChild(document.createTextNode(color));

								if (disc.marked) {
									discCell.style.backgroundColor = '#ff0000';
								}

								boardRow.appendChild(discCell);
							}
						}
					}
				};

				<?php
					echo 'var id = ' . $_GET['id'] . ";\n";
				?>
				xhttp.open('GET', '../res/php/game_state.php?id=' + id, true);
				xhttp.send();
			}

			function updateClock(game) {
				// Updaten der Spielzeit
				var clock1 = document.getElementById('clock1');
				var clock2 = document.getElementById('clock2');

				if (game.isSpectator) {
					// Zeiten der Spieler werden dem Zuschauer angezeigt
					clock1.firstChild.nodeValue = game.name1 + "'s time: " + game.clock1.toFixed(1) + 's';
					clock2.firstChild.nodeValue = game.name2 + "'s time: " + game.clock2.toFixed(1) + 's';
				} else if (game.playerId == game.player1) {
					clock1.firstChild.nodeValue = 'Your time: ' + game.clock1.toFixed(1) + 's';
					clock2.firstChild.nodeValue = game.name2 + "'s time: " + game.clock2.toFixed(1) + 's';
				} else {
					// Die eigene Zeit bzw. die Zeit des Gegenspielers werden angezeigt
					clock1.firstChild.nodeValue = 'Your time: ' + game.clock2.toFixed(1) + 's';
					clock2.firstChild.nodeValue = game.name1 + "'s time: " + game.clock1.toFixed(1) + 's';
				}

			}

			function updateStateMessage(game) {
				var stateMessage = document.getElementById('stateMessage');

				if (game.isSpectator) {
					// Der Zuschauer kann das Spiel verfolgen.
					if (game.state == 'finished') {
						// Das Spiel ist beendet.
						if (game.winnerObj == null) {
							// Das Spiel ist unentschieden ausgegangen.
							msg = "It's a tie";
						} else {
							msg = game.winnerObj.name + ' won!';
							// Einer der Spieler hat gewonnen.
						}
					} else {
						msg = game.currentPlayerObj.name + ' is thinking . . .';
						// Das Spiel läuft.
					}
				// aktiver Teilnehmer des Spiels
			} else if (game.state == 'finished') {
					// Das Spiel ist beendet
					if (game.winnerObj == null) {
						msg = "It's a tie"; // Untentschieden
					} else if (game.winnerObj.id == game.playerId) {
						msg = 'You won.'; // Gewonnen
					} else {
						msg = 'You lost.'; // Verloren
					}
				} else if (game.currentPlayerObj.id == game.playerId) {
					msg = "It's your turn."; // Spieler ist am Zug
				} else {
					msg = game.currentPlayerObj.name + ' is thinking . . .'; // Gegenspieler ist am Zug
				}

				stateMessage.firstChild.nodeValue = msg;
			}

			/**
			  * @param column Die Spalte, in welche ein Spielstein eingefügt werden soll.
			  */
			function insertDisc(column) {
				var xhttp = new XMLHttpRequest();

				<?php
					echo 'var id = ' . $_GET['id'] . ";\n";
				?>
				xhttp.open('GET', '../res/php/make_move.php?id=' + id + '&column=' + column, true);
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

				<?php
					echo 'var id = ' . $_GET['id'] . ";\n";
				?>
				xhttp.open('GET', '../res/php/resign.php?id=' + id, true);
				xhttp.send();
			}

			// Der Spiel-Status wird in einem Intervall von einer Sekunde aktualisiert.
			function startUpdateLoop() {
				setInterval(updateGameState, 1000);
			}

			var firstLoadingCycle = true;
		</script>
	</head>
	<body onload='startUpdateLoop();'>
		<h1 id='title'></h1>
		<div id='clock1'>Loading . . .</div>
		<div id='clock2'>Loading . . .</div>
		<div id='stateMessage'>Loading . . .</div>
		<table border='1' id='table'></table>
		<div id='resignContainer'></div>
		<a href='index.php'>Back to the lobby</a>
	</body>
</html>
