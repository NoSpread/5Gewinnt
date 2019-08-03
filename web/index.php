<?php
session_start();
require_once 'res/php/config.php';
require_once 'res/includes/auth_validate.php';
//$db->getInstance();
?>
<!DOCTYPE html>
<html lang="en">
<head>


    <title>Lobby</title>

    </head>
<body id="body" onload="getglist()">
    LOBBY!!1!
	<br/>
	<div id="joingame">
		<form>
			<button id="update" type="submit" onclick="getglist()">Update!</button>
				<script>
					function getglist() {
						  var xhttp = new XMLHttpRequest();
						  xhttp.onreadystatechange = function() {
							if (this.readyState == 4 && this.status == 200) {
							 document.getElementById("ogames").innerHTML = this.responseText;
							}
						  };
						  xhttp.open("GET", "res/php/list_open_games.php", true);
						  xhttp.send();
					}
				</script>
			<table id="ogames" name="opengames">
			</table>
		</form>
		<form>
			<input type="text" placeholder="123465789">
			<button id="join" type="submit" onclick="findg()">Join Game</button>

			<script>
				function findg(){
					var xhttp = new XMLHttpRequest();
					  xhttp.onreadystatechange = function() {
						if (this.readyState == 4 && this.status == 200) {
						 alert(this.responseText);
						}
					  };
					  xhttp.open("GET", "res/php/findgame.php", true);
					  xhttp.send();
				}
			</script>
		</form>
	</div>
	<form action="pages/creategame oder so.php" method="post">
		<button id="create" type="submit">Create Game</button>
	</form>
</body>
</html>
