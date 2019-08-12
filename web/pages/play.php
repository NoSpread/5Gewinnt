<?php
	session_start();
	require_once '../res/includes/auth_validate.php';
?>
<html>
	<head>
		<title>Fight!</title>
		<script>
			/**
			  * Request the current game state and show it to the player
			  */
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
						var clock1 = game.clock1;
						var clock2 = game.clock2;
						var playerId = <?php echo $_SESSION['id'] ?>;
						
						
						game['playerId'] = playerId;
						
						if (playerId != player1[0] && playerId != player2[0]) {
							var spectator = true;
						} else {
							var spectator = false;
						}
						game['spectator'] = spectator;
						
						var winner = {
							1: null,
							2: player1[0],
							3: player2[0]
						}[gameObj.winner];
						game['winner'] = winner;
						
						
						var currentPlayer = {
							2: player1[0],
							3: player2[0]
						}[gameObj.player];
						game['currentPlayer'] = currentPlayer;
						
						
						// Update the game state message
						// Need to distinguish between Player and Spectator mode
						
						gamestate(game);
						
						updateClock(game);
						
						var table = document.getElementById('table');

						
						if (!tableHeadLoaded) {
							var tableHead = document.createElement('thead');
							table.appendChild(tableHead);
							if (!spectator){
								
								// Create the table head containing the buttons for inserting discs
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
							tableHeadLoaded = true;
						}
						
						// Remove old board
						if (document.getElementById('tableBody')) {
							table.removeChild(document.getElementById('tableBody'));
						}

						// Create the game board
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
				var gameObj = game.gameObj;
				
				
				var playerClock = document.getElementById('playerClock');
				var opponentClock = document.getElementById('opponentClock');
				
				if (game.spectator) {
					
					playerClock.firstChild.nodeValue = game.name1 + "'s time: " + game.clock1.toFixed(1) + "s";
					opponentClock.firstChild.nodeValue = game.name2 + "'s time: " + game.clock2.toFixed(1) + "s";
				
				} else {
				
					if (game.playerId == game.player1) {
						playerClock.firstChild.nodeValue = "Your time: " + game.clock1.toFixed(1) + "s";
						opponentClock.firstChild.nodeValue = "Opponent's time: " + game.clock2.toFixed(1) + "s";
					} else {
						playerClock.firstChild.nodeValue = "Your time: " + game.clock2.toFixed(1) + "s";
						opponentClock.firstChild.nodeValue = "Opponent's time: " + game.clock1.toFixed(1) + "s";
					}
				}

			}
			
			
			function gamestate(game) {
				var gameState = document.getElementById('gameState');
				
				
				var gameObj = game.gameObj;
				if (game.spectator) {

					var title = document.getElementsByTagName("h1");
					console.log(title.textContent);
					title[0].textContent = 'Spectator Mode 👀';
					
					if (gameObj.finished) {
						if (game.winner == null) {
							msg = "It's a tie";
						} else {
							if(game.player1 == game.winner) {	
								msg = game.name1 + " won!";
							} else {
								msg = game.name2 + " won!"
							}
						}
					} else {
						if(game.player1 == game.currentPlayer) {	
								msg = game.name1 + " is thinking . . .";
							} else {
								msg = game.name2 + " is thinking . . ."
							}
					}
				} else {
					if (gameObj.finished) {
						if (game.winner == null) {
							msg = "It's a tie";
						} else if (game.winner == game.playerId) {
							msg = "You won.";
						} else {
							msg = "You lost.";
						}
					} else {
						if (game.currentPlayer == game.playerId) {
							msg = "It's your turn.";
						} else {
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
			  * @param column The column number to be inserted into
			  */
			function insert(column) {
				var xhttp = new XMLHttpRequest();

				<?php
					echo 'var id = ' . $_GET['id'] . ";\n";
				?>
				xhttp.open('GET', '../res/php/make_move.php?id=' + id + '&column=' + column, true);
				xhttp.send();
			}

			/**
			  * Resign the game
			  */
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

			/**
			  * Set an interval that updates the game state every second
			  */
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
