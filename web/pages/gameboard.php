<html>
	<head>
		<title>Game Board</title>
		<script>
			function loadGameState() {
				var xhttp = new XMLHttpRequest();

				xhttp.onreadystatechange = function() {
					if (this.readyState == 4 && this.status == 200) {
						var game = JSON.parse(this.responseText);

						console.log(game);

						var state = document.getElementById('state');

						while (state.firstChild) {
							state.removeChild(state.firstChild);
						}
						
						if (game.finished) {
							var msg = {
								1: "It's a tie",
								2: "Player 1 won",
								3: "Player 2 won"
							}[game.winner];

							state.appendChild(
								document.createTextNode(msg)
							);
						} else {
							var player_name = {
								2: "Player 1",
								3: "Player 2"
							}[game.player];

							state.appendChild(
								document.createTextNode("Now playing: " + player_name)
							);
						}

						var table = document.getElementById('board');

						while (table.firstChild) {
							table.removeChild(table.firstChild);
						}

						var table_head = document.createElement('thead');
						table.appendChild(table_head);
						var table_head_row = document.createElement('tr');
						table_head.appendChild(table_head_row);
						for (let x = 0; x < game.width; x++) {
							let newCell = document.createElement('th');
							table_head_row.appendChild(newCell);

							let button = document.createElement('button');
							newCell.appendChild(button);

							button.appendChild(
								document.createTextNode('v')
							);

							button.onclick = function() {
								insert(x);
							}
						}

						var table_body = document.createElement('tbody');
						table.appendChild(table_body);
						for (let y = 0; y < game.height; y++) {
							let newRow = document.createElement('tr');
							table_body.appendChild(newRow);

							for (let x = 0; x < game.width; x++) {
								let newCell = document.createElement('td');

								let disc = game.grid.lines[y][x];

								let color = {
									1: '-', 2:'X', 3:'O'
								}[disc.color];

	                            newCell.appendChild(
									document.createTextNode(color)
								);

								if (disc.marked) {
									newCell.style.backgroundColor = '#ff0000';
								}

								newRow.appendChild(newCell);
							}
						}
					}
				};

				<?php
					echo 'var id = ' . $_GET['id'] . ";\n";
				?>
				xhttp.open('GET', '../res/php/game_state.php?id=' + id + ', true');
				xhttp.send();
			}

			function insert(column) {
				var xhttp = new XMLHttpRequest();

				xhttp.onreadystatechange = function() {
					if (this.readyState == 4 && this.status == 200) {
						loadGameState();
					}
				};

				<?php
					echo 'var id = ' . $_GET['id'] . ";\n";
				?>
				xhttp.open('GET', '../res/php/make_move.php?id=' + id + '&column=' + column + ', true');
				xhttp.send();
			}
		</script>
	</head>
	<body onload='loadGameState();'>
		<h1>Game Board</h1>
		<div id='state'></div>
		<table border='1' id='board'></table>
	</body>
</html>
