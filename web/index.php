<?php
//session_start();
//require_once 'res/php/config.php';
//require_once 'res/includes/auth_validate.php';
//$db->getInstance();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    

    <title>Lobby</title>

    </head>
<body id="body">
    LOBBY!!1!
	<br/>
	<table>
	<tr>
		<th>
			<div id="gamelist">
				<button id="update" type="submit" onclick="opengame()">Update!</button>
				<script language="Javascript">
					function opengame(){
						document.getElementById("ogames").innerHTML = "";
						
						}
				</script>
				
				<select id="ogames" name="opengames">
					<?php 
						for($i = 0; $i < 10; $i++){
							//DB Abfrage 
							echo "<option seleced=\"selected\">Spiel". $i . "</option>";
						}
					?>
				</select>
				<button id="join" type="submit">Join Game</button>
				
			</div>
		</th>
		<th>
			Join with Game Code!
			<form include="pages/index.php">
			
	</tr>
	<form action="pages/index.php" method="post">
		<select name="gamemode" size="2">
			<option selected^="selected">public</option>
			<option>private</option>
		</select>
		
		<button id="create" type="submit">Create Game</button>
	</form>
	<form action="pages/index.php" method="post">
		<input type="text" id="gameid" placeholder="gameid" required>
		<button id="search" type="submit">suchen</button>
	</form>
	<form action="pages/index.php" method="post">
		<button id="remove" type="submit">remove invite</button>
	</form>
</body>
</html>