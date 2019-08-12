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
						console.log(this.responseText);
						var game = JSON.parse(this.responseText);

						console.log(game);

						var gameObj = game.gameObj;
						var player1 = [game.player1, game.name1];
						var player2 = [game.player2, game.name2];
						game['playerId'] = <?php echo $_SESSION['id'] ?>;
						
						
						if (game['playerId'] != player1[0] && game['playerId'] != player2[0]) {
							// keine aktive Teilname am Spiel -> Zuschauer
							game['spectator'] = true;
						} else {
							game['spectator'] = false;
						}						
						game['winner'] = {
							1: null,
							2: player1[0],
							3: player2[0]
						}[gameObj.winner];
						
						
						
						game['currentPlayer'] = {
							2: player1[0],
							3: player2[0]
						}[gameObj.player];
						
						// Update der Spiel-Status-Nachricht

						gamestate(game);
						
						updateClock(game);
						
						var table = document.getElementById('table');

						
						if (!tableHeadLoaded && game['winner'] == null) {
							// Es gibt (noch) keinen Gewinner und das Spielbrett ist noch nicht erstellt:
							var tableHead = document.createElement('thead');
							table.appendChild(tableHead);
							if (!game['spectator']){

								// Erzeugen des Tabellenkopfes, welcher die Knöpfe zum Spielstein-Setzen enhält
								var tableHeadRow = document.createElement('tr');
								tableHead.appendChild(tableHeadRow);

								for (let x = 0; x < gameObj.grid.width; x++) {
									var buttonCell = document.createElement('th');
									tableHeadRow.appendChild(buttonCell);

									var button = document.createElement('button');
									button.onclick = function() { insert(x); };
									buttonCell.appendChild(button);
									button.appendChild(document.createTextNode('v'));
								}
							}	
							// Das Spielbrett muss nur einmal erzeugt werden.
							tableHeadLoaded = true;
						}
						if (game['winner'] != null && tableHeadLoaded) {
							// Es gibt einen Gewinner (und das Spielbrett ist geladen) -> Spielbrett wird entfernt
							table.removeChild(document.getElementsByTagName("thead")[0]);
							tableHeadLoaded = false;
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
									1: ' - ', 2:'    X', 3:'O'
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
				var gameObj = game.gameObj;
				
				
				var playerClock = document.getElementById('playerClock');
				var opponentClock = document.getElementById('opponentClock');
				
				if (game.spectator) {
					// Zeiten der Spieler werden dem Zuschauer angezeigt
					playerClock.firstChild.nodeValue = game.name1 + "'s time: " + game.clock1.toFixed(1) + "s";
					opponentClock.firstChild.nodeValue = game.name2 + "'s time: " + game.clock2.toFixed(1) + "s";
				
				} else {
					// Die eigene Zeit bzw. die Zeit des Gegenspielers werden angezeigt
					if (game.playerId == game.player1) {
						playerClock.firstChild.nodeValue = "Your time: " + game.clock1.toFixed(1) + "s";
						opponentClock.firstChild.nodeValue = game.name2 + "'s time: " + game.clock2.toFixed(1) + "s";
					} else {
						playerClock.firstChild.nodeValue = "Your time: " + game.clock2.toFixed(1) + "s";
						opponentClock.firstChild.nodeValue = game.name1 + "'s time: " + game.clock1.toFixed(1) + "s";
					}
				}

			}
			
			
			function gamestate(game) {
				var gameState = document.getElementById('gameState');
				
				
				var gameObj = game.gameObj;
				if (game.spectator) {

					// Der Zuschauer kann das Spiel verfolgen.
					var title = document.getElementsByTagName("h1");
					console.log(title.textContent);
					title[0].textContent = 'Spectator Mode 👀';
					
					if (gameObj.finished) {
						// Das Spiel ist beendet.
						if (game.winner == null) {
							// Das Spiel ist unentschieden ausgegangen.
							msg = "It's a tie";
						} else {
							// Einer der Spieler hat gewonnen.
							if(game.player1 == game.winner) {	
								msg = game.name1 + " won!";
							} else {
								msg = game.name2 + " won!"
							}
						}
					} else {
						// Das Spiel läuft.
						if(game.player1 == game.currentPlayer) {	
								msg = game.name1 + " is thinking . . .";
							} else {
								msg = game.name2 + " is thinking . . ."
							}
					}
				} else {

					// aktiver Teilnehmer des Spiels
					if (gameObj.finished) {
						// Das Spiel ist beendet.
						if (game.winner == null) {
							// Unentschieden
							msg = "It's a tie";
						} else if (game.winner == game.playerId) {
							// Gewonnen
							msg = "You won.";
						} else {
							// Verloren
							msg = "You lost.";
						}
					} else {
						// Das Spiel läuft
						if (game.currentPlayer == game.playerId) {
							// Spieler ist am Zug
							msg = "It's your turn.";
						} else {
							// Gegenspieler ist am Zug
							if(game.player1 == game.currentPlayer) {	
								msg = game.name1 + " is thinking . . .";
							} else {
								msg = game.name2 + " is thinking . . ."
							}
						}
					}
				}
				gameState.firstChild.nodeValue = msg;
			}
			
			
			/**
			  * @param column Die Spalte, in welche ein Spielstein eingefügt werden soll.
			  */
			function insert(column) {
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

			var tableHeadLoaded = false;
		</script>
	</head>
	<body onload='startUpdateLoop();'>
		<h1>Fight! &#129354;</h1>
		<div id='playerClock'>Loading . . .</div>
		<div id='opponentClock'>Loading . . .</div>
		<div id='gameState'>Loading . . .</div>
		<table border='1' id='table'></table>
		<div>
			<button onclick='resign();'>Resign</button>
		</div><div>
			<a href='index.php'>Back to the lobby</a>
		</div>
	</body>
</html>
