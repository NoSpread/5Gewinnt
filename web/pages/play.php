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
						var game = JSON.parse(this.responseText);

						// Update the game state message
						var gameState = document.getElementById('gameState');

						// Remove current message
						if (gameState.firstChild) {
							gameState.removeChild(gameState.firstChild);
						}

						var msg;
						if (game.finished) {
							msg = {
								1: "It's a tie",
								2: "Player 1 won",
								3: "Player 2 won"
							}[game.winner];
						} else {
							var player_name = {
								2: "Player 1",
								3: "Player 2"
							}[game.player];

							msg = "Now playing: " + player_name;
						}

						gameState.appendChild(document.createTextNode(msg));

						var board = document.getElementById('board');

						// Remove old board
						while (board.firstChild) {
							board.removeChild(board.firstChild);
						}

						var tableHead = document.createElement('thead');
						board.appendChild(tableHead);

						// Create the table head containing the buttons for inserting discs
						var tableHeadRow = document.createElement('tr');
						tableHead.appendChild(tableHeadRow);

						for (let x = 0; x < game.grid.width; x++) {
							var buttonCell = document.createElement('th');
							tableHeadRow.appendChild(buttonCell);

							var button = document.createElement('button');
							button.onclick = function() { insert(x); };
							buttonCell.appendChild(button);
							button.appendChild(document.createTextNode('v'));
						}

						// Create the game board
						var tableBody = document.createElement('tbody');
						board.appendChild(tableBody);

						for (let y = 0; y < game.grid.height; y++) {
							var boardRow = document.createElement('tr');
							tableBody.appendChild(boardRow);

							for (let x = 0; x < game.grid.width; x++) {
								var disc = game.grid.lines[y][x];
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

			/**
			  * @param column The column number to be inserted into
			  */
			function insert(column) {
				var xhttp = new XMLHttpRequest();

				xhttp.onreadystatechange = function() {
					if (this.readyState == 4 && this.status == 200) {
						updateGameState();
					}
				};

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
		</script>
	</head>
	<body onload='updateGameState();'>
		<h1>Fight! &#129354;</h1>
		<div id='gameState'></div>
		<table border='1' id='board'></table>
		<div>
			<button onclick='resign();'>Resign</button>
		</div><div>
			<a href='index.php'>Back to the lobby</a>
		</div>
	</body>
</html>
