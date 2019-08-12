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
						var player1 = { id: game.player1, name: game.name1 };
						var player2 = { id: game.player2, name: game.name2 };
						game.playerId = <?php echo $_SESSION['id'] ?>;
						
						
						if (game.playerId != player1.id && game.playerId != player2.id) {
							game.spectator = true;
						} else {
							game.spectator = false;
						}		
						
						game.winner = {
							1: null,
							2: player1,
							3: player2
						}[gameObj.winner];
						
						game.currentPlayer = {
							2: player1,
							3: player2
						}[gameObj.player];
						
						// Update the game state message
						
						gamestate(game);
						
						updateClock(game);
						
						var table = document.getElementById('table');

						if (!tableHeadLoaded && game['winner'] == null) {
							var tableHead = document.createElement('thead');
							table.appendChild(tableHead);
							if (!game['spectator']){
								
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
						if (game['winner'] != null && tableHeadLoaded) {
							table.removeChild(document.getElementsByTagName("thead")[0]);
							tableHeadLoaded = false;
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
				
				
				var clock1 = document.getElementById('clock1');
				var clock2 = document.getElementById('clock2');
				
				if (game.spectator) {
					clock1.firstChild.nodeValue = game.name1 + "'s time: " + game.clock1.toFixed(1) + "s";
					clock2.firstChild.nodeValue = game.name2 + "'s time: " + game.clock2.toFixed(1) + "s";
				} else if (game.playerId == game.player1) {
					clock1.firstChild.nodeValue = "Your time: " + game.clock1.toFixed(1) + "s";
					clock2.firstChild.nodeValue = game.name2 + "'s time: " + game.clock2.toFixed(1) + "s";
				} else {
					clock1.firstChild.nodeValue = "Your time: " + game.clock2.toFixed(1) + "s";
					clock2.firstChild.nodeValue = game.name1 + "'s time: " + game.clock1.toFixed(1) + "s";
				}

			}
			
			
			function gamestate(game) {
				var gameState = document.getElementById('gameState');
				
				var gameObj = game.gameObj;
				if (game.spectator) {
					var title = document.getElementById('title');
					title.textContent = 'Spectator Mode 👀';
					
					if (gameObj.finished) {
						if (game.winner == null) {
							msg = "It's a tie";
						} else {
							msg = game.winner.name + ' won!';
						}
					} else {
						msg = game.currentPlayer.name + ' is thinking . . .';
					}
				} else if (gameObj.finished) {
					if (game.winner == null) {
						msg = "It's a tie";
					} else if (game.winner == game.playerId) {
						msg = "You won.";
					} else {
						msg = "You lost.";
					}
				} else if (game.currentPlayer == game.playerId) {
					msg = "It's your turn.";
				} else {
					msg = game.currentPlayer.name + ' is thinking . . .';
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
		<h1 id='title'>Fight! &#129354;</h1>
		<div id='clock1'>Loading . . .</div>
		<div id='clock2'>Loading . . .</div>
		<div id='gameState'>Loading . . .</div>
		<table border='1' id='table'></table>
		<div>
			<button onclick='resign();'>Resign</button>
		</div><div>
			<a href='index.php'>Back to the lobby</a>
		</div>
	</body>
</html>
