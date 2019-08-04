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
						var game = JSON.parse(this.responseText);

						var gameObj = game.gameObj;
						var player1 = game.player1;
						var player2 = game.player2;
						var ownId = <?php echo $_SESSION['id'] ?>;

						var winner = {
							1: null,
							2: player1,
							3: player2
						}[gameObj.winner];

						var currentPlayer = {
							2: player1,
							3: player2
						}[gameObj.player];

						// Update the game state message
						var gameState = document.getElementById('gameState');

						// Remove current message
						if (gameState.firstChild) {
							gameState.removeChild(gameState.firstChild);
						}

						var msg;
						if (gameObj.finished) {
							if (winner == null) {
								msg = "It's a tie";
							} else if (winner == ownId) {
								msg = "You won.";
							} else {
								msg = "You lost.";
							}
						} else {
							if (currentPlayer == ownId) {
								msg = "It's your turn.";
							} else {
								msg = "Opponent is thinking . . .";
							}
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

						for (let x = 0; x < gameObj.grid.width; x++) {
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
		</script>
	</head>
	<body onload='startUpdateLoop();'>
		<h1>Fight! &#129354;</h1>
		<div id='gameState'></div>
		<table border='1' id='board'></table>
		<div>
			<button onclick='resign();'>Resign</button>
		</div><div>
			<a href='../index.php'>Back to the lobby</a>
		</div>
	</body>
</html>
