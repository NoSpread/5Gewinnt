<?php
	session_start();
    require_once '../res/includes/auth_validate.php';
?>
<html>
    <head>
        <title>Game Lobby</title>
        <script>
            /**
              * @param game Game object to be added to the game table
              */
            function addGame(game) {
                var table = document.getElementById('gameTable');

                var tableRow = document.createElement('tr');
                table.appendChild(tableRow);

                var idCell = document.createElement('td');
                tableRow.appendChild(idCell);
                idCell.appendChild(document.createTextNode(game.id))

                var player1Cell = document.createElement('td');
                tableRow.appendChild(player1Cell);
                var player2Cell = document.createElement('td');
                tableRow.appendChild(player2Cell);

                var button = document.createElement('input');
                button.type = 'button';
                button.value = 'Join Game';
                button.onclick = function() { joinGame(game.id); }

                if (game.player1 !== null) {
					player1Cell.appendChild(document.createTextNode(game.username + ' (id:' + game.player1 + ')'));
					player2Cell.appendChild(button);
				} else {
					player1Cell.appendChild(button);
					player2Cell.appendChild(document.createTextNode(game.username + ' (id:' + game.player2 + ')'));
				}

                loadedGameIds.push(game.id);
            }

			function joinGame(id) {
				var xhttp = new XMLHttpRequest();

                xhttp.onreadystatechange = function() {
                    if (this.readyState == 4 && this.status == 200) {
						if(this.responseText == '1') {
							// TODO make form invisible
							var url = 'play.php';
							var form = document.createElement('form');
							form.action = url;
							form.method = 'get';
							document.getElementById('body').appendChild(form);

							var idField = document.createElement('input');
							idField.type = 'text';
							idField.name = 'id';
							idField.value = id;
							form.appendChild(idField);

							form.submit();
						}
                    }
                };

                xhttp.open('GET', '../res/php/join_game.php?id=' + id, true);
                xhttp.send();
			}
            /**
              * @param id Game id to be removed from the game table
              */
            function removeGame(id) {
				var table = document.getElementById('gameTable');
                var tableRows = document.getElementById('gameTable').children;

                // Iterate over game table, search for the given game id and remove the entry
                for (let i = 0; i < loadedGameIds.length; i++) {
                    var curId = tableRows[i] // <tr> tag
                        .children[0] // first <td> tag ([0] -> game id, [1] -> player1)
                        .firstChild // text node
                        .nodeValue; // game id

                    if (curId == id) {
                        table.removeChild(tableRows[i]);

                        loadedGameIds.splice(loadedGameIds.indexOf(id), 1);
                        return;
                    }
                }
            }

            /**
              * Request a json object with all open games from the server and list them in the game table
              */
            function updateGames() {
                var xhttp = new XMLHttpRequest();

                xhttp.onreadystatechange = function() {
                    if (this.readyState == 4 && this.status == 200) {
                        var games = JSON.parse(this.responseText);

                        // Create a copy of all loaded game ids and remove those that are still open
                        // The games that are left after that need to be removed from the game table
                        var oldGameIds = loadedGameIds.slice();

                        for (let i = 0; i < games.length; i++) {
                            var game = games[i];
                            if (!loadedGameIds.includes(game.id)) {
                                addGame(game);
                            } else {
                                oldGameIds.splice(oldGameIds.indexOf(game.id), 1);
                            }
                        }

                        for (let i = 0; i < oldGameIds.length; i++) {
                            removeGame(oldGameIds[i]);
                        }
                    }
                };

                xhttp.open('GET', '../res/php/list_open_games.php', true);
                xhttp.send();
            }
			
            /**
              * Create new Challenge and wait for oponnent
              */
			function createChallenge() {
					createGame();
					var IntervalId = setInterval(wait, 1000);
					
					
					var button = document.getElementById('manage');
					button.onclick = "function() { revokeChallenge(" + IntervalId + ");}";
					button.value = "Revoke Challenge";
					document.getElementById('player').style.visibility = 'hidden';
					document.getElementById('player').value = '';
			}
			
			function wait() {
				var buttons = document.getElementsByTagName('input');
				for (var i = 0; i < buttons.length -1; i++) {
					buttons[i].disabled = true;
				}
				
				// TODO ID from PLAYER
				// Oder wissen das nur die Skripte in ../res ?
				var playerId = 42;
				var xhttp = new XMLHttpRequest();

                xhttp.onreadystatechange = function() {
                    if (this.readyState == 4 && this.status == 200) {
						if (parseInt(this.responseText) != 0) {
							
							
							console.log(this.responseText);
							
							// TODO make form invisible
							var url = 'play.php';
							var form = document.createElement('form');
							form.action = url;
							form.method = 'get';
							document.getElementById('body').appendChild(form);

							var idField = document.createElement('input');
							idField.type = 'text';
							idField.name = 'id';
							idField.value = parseInt(this.responseText);
							form.appendChild(idField);

							form.submit();
						}
					}
                };

                xhttp.open('GET', '../res/php/is_open.php', true);
                xhttp.send();
				
			}
			/**
			  *
			  */
			function revokeChallenge(IntervalId) {
				var buttons = document.getElementsByTagName('input');
				for (var i = 0; i < buttons.length; i++) {
					buttons[i].disabled = false;
				}
				
				button = document.getElementById('manage')
				button.onclick = "function() { createChallenge();}";
				button.name = "create Challenge";
				document.getElementById('player').style.visibility = 'visible';
				document.getElementById('player').value = 'White';
			}
			
			
            /**
              * Create new Challenge and wait for oponnent
              */
			function createChallenge() {
					createGame();
					var IntervalId = setInterval(wait, 1000);


					var button = document.getElementById('manage');
					button.onclick = "function() { revokeChallenge(" + IntervalId + ");}";
					button.value = "Revoke Challenge";
					document.getElementById('player').style.visibility = 'hidden';
					document.getElementById('player').value = '';
			}

			function wait() {
				var buttons = document.getElementsByTagName('input');
				for (var i = 0; i < buttons.length -1; i++) {
					buttons[i].disabled = true;
				}


				var xhttp = new XMLHttpRequest();

                xhttp.onreadystatechange = function() {
                    if (this.readyState == 4 && this.status == 200) {
						if (parseInt(this.responseText) != 0) {


							console.log(this.responseText);

							// TODO make form invisible
							var url = 'play.php';
							var form = document.createElement('form');
							form.action = url;
							form.method = 'get';
							document.getElementById('body').appendChild(form);

							var idField = document.createElement('input');
							idField.type = 'text';
							idField.name = 'id';
							idField.value = parseInt(this.responseText);
							form.appendChild(idField);

							form.submit();
						}
					}
                };

                xhttp.open('GET', '../res/php/is_open.php', true);
                xhttp.send();

			}
			/**
			  *
			  */
			function revokeChallenge(IntervalId) {
				var buttons = document.getElementsByTagName('input');
				for (var i = 0; i < buttons.length; i++) {
					buttons[i].disabled = false;
				}

				button = document.getElementById('manage')
				button.onclick = "function() { createChallenge();}";
				button.name = "create Challenge";
				document.getElementById('player').style.visibility = 'visible';
				document.getElementById('player').value = 'White';
			}


            /**
              * Set an interval that updates the game list every second
              */
            function startUpdateLoop() {
                setInterval(updateGames, 1000);
            }

            /**
              * Add a fresh new game to the database
              */
            function createGame() {
				// Pls make Checkbox to Togglebutton

                var xhttp = new XMLHttpRequest();

				var player = {
					true: '1',
					false: '2'
				}[document.getElementById('player').checked];

                xhttp.open('GET', '../res/php/create_game.php?player=' + player , true);
                xhttp.send();
            }

            var loadedGameIds = [];
        </script>
    </head>
    <body id='body' onload='startUpdateLoop();'>
        <h1>Game Lobby &#127976;</h1>
        <table border='1'>
            <thead>
                <tr>
                    <th>Game ID</th>
                    <th>First Player</th>
					<th>Second Player</th>
                <tr>
            </thead>
            <tbody id='gameTable'></tbody>
        </table>
		<form>
			<input id="player" type="checkbox" checked="checked">White</input>
			<input id="manage" type='button' onclick='createChallenge();' value="Create Challenge"/>
		</form>
	</body>
</html>
